<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderStatusUpdated;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:191'],
            'status' => [
                'nullable',
                'in:pending,confirmed,processing,shipped,delivered,cancelled,refunded'
            ],
            'payment_status' => [
                'nullable',
                'in:unpaid,pending,paid,failed,refunded'
            ],
            'payment_method' => [
                'nullable',
                'in:cod,bank,sslcommerz'
            ],
            'date_from' => [
                'nullable',
                'date'
            ],
            'date_to' => [
                'nullable',
                'date',
                'after_or_equal:date_from'
            ],
            'sort' => [
                'nullable',
                'in:newest,oldest,total_high,total_low'
            ],
        ]);

        $orders = Order::query()
            ->with('user')
            ->withCount('items')

            /*
            |--------------------------------------------------------------------------
            | Search
            |--------------------------------------------------------------------------
            */
            ->when(
                ! empty($filters['q']),
                function ($query) use ($filters) {

                    $search = trim(
                        $filters['q']
                    );

                    $query->where(
                        function ($nested) use ($search) {

                            $nested
                                ->where(
                                    'order_number',
                                    'like',
                                    "%{$search}%"
                                )

                                ->orWhere(
                                    'transaction_id',
                                    'like',
                                    "%{$search}%"
                                )

                                ->orWhere(
                                    'shipping_address->name',
                                    'like',
                                    "%{$search}%"
                                )

                                ->orWhere(
                                    'shipping_address->email',
                                    'like',
                                    "%{$search}%"
                                )

                                ->orWhere(
                                    'shipping_address->phone',
                                    'like',
                                    "%{$search}%"
                                )

                                ->orWhereHas(
                                    'user',
                                    function ($userQuery) use ($search) {

                                        $userQuery
                                            ->where(
                                                'name',
                                                'like',
                                                "%{$search}%"
                                            )

                                            ->orWhere(
                                                'email',
                                                'like',
                                                "%{$search}%"
                                            );

                                    }
                                );

                        }
                    );

                }
            )

            /*
            |--------------------------------------------------------------------------
            | Order status
            |--------------------------------------------------------------------------
            */
            ->when(
                ! empty($filters['status']),
                function ($query) use ($filters) {

                    $query->where(
                        'status',
                        $filters['status']
                    );

                }
            )

            /*
            |--------------------------------------------------------------------------
            | Payment status
            |--------------------------------------------------------------------------
            */
            ->when(
                ! empty($filters['payment_status']),
                function ($query) use ($filters) {

                    $query->where(
                        'payment_status',
                        $filters['payment_status']
                    );

                }
            )

            /*
            |--------------------------------------------------------------------------
            | Payment method
            |--------------------------------------------------------------------------
            */
            ->when(
                ! empty($filters['payment_method']),
                function ($query) use ($filters) {

                    $query->where(
                        'payment_method',
                        $filters['payment_method']
                    );

                }
            )

            /*
            |--------------------------------------------------------------------------
            | Date from
            |--------------------------------------------------------------------------
            */
            ->when(
                ! empty($filters['date_from']),
                function ($query) use ($filters) {

                    $query->whereDate(
                        'created_at',
                        '>=',
                        $filters['date_from']
                    );

                }
            )

            /*
            |--------------------------------------------------------------------------
            | Date to
            |--------------------------------------------------------------------------
            */
            ->when(
                ! empty($filters['date_to']),
                function ($query) use ($filters) {

                    $query->whereDate(
                        'created_at',
                        '<=',
                        $filters['date_to']
                    );

                }
            );


        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */
        switch (
            $filters['sort']
            ??
            'newest'
        ) {

            case 'oldest':

                $orders->oldest();

                break;


            case 'total_high':

                $orders
                    ->orderByDesc('total')
                    ->latest('id');

                break;


            case 'total_low':

                $orders
                    ->orderBy('total')
                    ->latest('id');

                break;


            case 'newest':

            default:

                $orders->latest();

                break;

        }


        $orders = $orders
            ->paginate(25)
            ->withQueryString();


        return view(
            'admin.orders.index',
            compact('orders')
        );
    }


    public function show(Order $order)
    {
        $order->load([
            'items.product',
            'items.variant',
            'user',
            'coupon',
            'histories.user',
        ]);


        return view(
            'admin.orders.show',
            compact('order')
        );
    }


    public function update(
        Request $request,
        Order $order
    ) {
        $data = $request->validate([
            'status' => [
                'required',
                'in:pending,confirmed,processing,shipped,delivered,cancelled,refunded'
            ],

            'payment_status' => [
                'required',
                'in:unpaid,pending,paid,failed,refunded'
            ],

            'admin_note' => [
                'nullable',
                'string',
                'max:3000'
            ],
        ]);


        $statusChanged = false;


        DB::transaction(
            function () use (
                $order,
                $data,
                $request,
                &$statusChanged
            ) {

                /*
                |--------------------------------------------------------------------------
                | Lock order to prevent concurrent stock/status changes
                |--------------------------------------------------------------------------
                */
                $lockedOrder = Order::query()
                    ->with('items')
                    ->whereKey(
                        $order->id
                    )
                    ->lockForUpdate()
                    ->firstOrFail();


                /*
                |--------------------------------------------------------------------------
                | Prevent reopening orders whose stock has already been restored
                |--------------------------------------------------------------------------
                */
                abort_if(
                    $lockedOrder->stock_restored_at
                    &&
                    ! in_array(
                        $data['status'],
                        [
                            'cancelled',
                            'refunded'
                        ],
                        true
                    ),
                    422,
                    'A restocked order cannot be reopened. Create a new order instead.'
                );


                $oldStatus =
                    $lockedOrder->status;


                /*
                |--------------------------------------------------------------------------
                | Restore stock after cancellation/refund
                |--------------------------------------------------------------------------
                */
                if (
                    in_array(
                        $data['status'],
                        [
                            'cancelled',
                            'refunded'
                        ],
                        true
                    )
                    &&
                    ! $lockedOrder->stock_restored_at
                ) {

                    foreach (
                        $lockedOrder->items
                        as
                        $item
                    ) {

                        if (
                            $item->product_variant_id
                        ) {

                            ProductVariant::whereKey(
                                $item->product_variant_id
                            )
                                ->increment(
                                    'stock',
                                    $item->quantity
                                );

                        }
                        elseif (
                            $item->product_id
                        ) {

                            Product::withTrashed()
                                ->whereKey(
                                    $item->product_id
                                )
                                ->increment(
                                    'stock',
                                    $item->quantity
                                );

                        }

                    }


                    $lockedOrder->stock_restored_at =
                        now();

                }


                /*
                |--------------------------------------------------------------------------
                | Update order
                |--------------------------------------------------------------------------
                */
                $lockedOrder->status =
                    $data['status'];


                $lockedOrder->payment_status =
                    $data['payment_status'];


                $lockedOrder->admin_note =
                    $data['admin_note']
                    ??
                    null;


                /*
                |--------------------------------------------------------------------------
                | Payment timestamp
                |--------------------------------------------------------------------------
                */
                if (
                    $data['payment_status']
                    ===
                    'paid'
                    &&
                    ! $lockedOrder->paid_at
                ) {

                    $lockedOrder->paid_at =
                        now();

                }


                /*
                |--------------------------------------------------------------------------
                | Delivery timestamp
                |--------------------------------------------------------------------------
                */
                if (
                    $data['status']
                    ===
                    'delivered'
                    &&
                    ! $lockedOrder->delivered_at
                ) {

                    $lockedOrder->delivered_at =
                        now();

                }


                $lockedOrder->save();


                /*
                |--------------------------------------------------------------------------
                | Status history
                |--------------------------------------------------------------------------
                */
                $statusChanged =
                    $oldStatus
                    !==
                    $data['status'];


                if ($statusChanged) {

                    $lockedOrder
                        ->histories()
                        ->create([
                            'user_id' =>
                                $request->user()->id,

                            'status' =>
                                $data['status'],

                            'note' =>
                                $data['admin_note']
                                ??
                                null,
                        ]);

                }

            }
        );


        /*
        |--------------------------------------------------------------------------
        | Refresh order
        |--------------------------------------------------------------------------
        */
        $order->refresh();


        /*
        |--------------------------------------------------------------------------
        | Email customer after status change
        |--------------------------------------------------------------------------
        */
        if ($statusChanged) {

            $email =
                data_get(
                    $order->shipping_address,
                    'email'
                );


            if ($email) {

                rescue(
                    fn () =>
                    Mail::to($email)
                        ->queue(
                            new OrderStatusUpdated(
                                $order
                            )
                        )
                );

            }

        }


        return redirect()
            ->route(
                'admin.orders.show',
                $order
            )
            ->with(
                'success',
                'Order updated successfully.'
            );
    }
}