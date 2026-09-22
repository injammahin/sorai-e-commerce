<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Mail\OrderStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with('user')
            ->when($request->filled('q'), fn ($query) => $query->where(fn ($nested) => $nested
                ->where('order_number', 'like', '%'.$request->q.'%')
                ->orWhere('shipping_address->email', 'like', '%'.$request->q.'%')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->latest()->paginate(25)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        return view('admin.orders.show', ['order' => $order->load(['items', 'user', 'histories.user'])]);
    }

    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled,refunded',
            'payment_status' => 'required|in:unpaid,pending,paid,failed,refunded',
            'admin_note' => 'nullable|string|max:3000',
        ]);

        abort_if($order->stock_restored_at && ! in_array($data['status'], ['cancelled', 'refunded'], true), 422, 'A restocked order cannot be reopened. Create a new order instead.');

        $originalStatus = $order->status;
        DB::transaction(function () use ($order, $data, $request) {
            $order = Order::with('items')->whereKey($order->id)->lockForUpdate()->firstOrFail();
            $oldStatus = $order->status;

            if (in_array($data['status'], ['cancelled', 'refunded'], true) && ! $order->stock_restored_at) {
                foreach ($order->items as $item) {
                    if ($item->product_variant_id) {
                        ProductVariant::whereKey($item->product_variant_id)->increment('stock', $item->quantity);
                    } elseif ($item->product_id) {
                        Product::withTrashed()->whereKey($item->product_id)->increment('stock', $item->quantity);
                    }
                }
                $order->stock_restored_at = now();
            }

            $order->fill($data);
            if ($data['payment_status'] === 'paid' && ! $order->paid_at) $order->paid_at = now();
            if ($data['status'] === 'delivered' && ! $order->delivered_at) $order->delivered_at = now();
            $order->save();

            if ($oldStatus !== $data['status']) {
                $order->histories()->create(['user_id' => $request->user()->id, 'status' => $data['status'], 'note' => $data['admin_note'] ?? null]);
            }
        });

        if ($originalStatus !== $data['status']) {
            $order->refresh();
            rescue(fn () => Mail::to(data_get($order->shipping_address, 'email'))->queue(new OrderStatusUpdated($order)));
        }

        return back()->with('success', 'Order updated.');
    }
}
