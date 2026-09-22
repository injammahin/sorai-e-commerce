<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display the customer directory.
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->input('q', ''));
        $status = (string) $request->input('status', '');
        $sort = (string) $request->input('sort', 'newest');

        $stats = User::query()
            ->where('role', 'customer')
            ->selectRaw('COUNT(*) as total_customers')
            ->selectRaw('SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active_customers')
            ->selectRaw('SUM(CASE WHEN is_active = 0 THEN 1 ELSE 0 END) as disabled_customers')
            ->selectRaw('SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as new_this_month', [
                now()->startOfMonth(),
            ])
            ->first();

        $customers = User::query()
            ->where('role', 'customer')
            ->withCount('orders')
            ->when($search !== '', function ($query) use ($search) {
                $term = '%' . addcslashes($search, '%_\\') . '%';

                $query->where(function ($customerQuery) use ($term) {
                    $customerQuery
                        ->where('name', 'like', $term)
                        ->orWhere('email', 'like', $term)
                        ->orWhere('phone', 'like', $term);
                });
            })
            ->when($status === 'active', function ($query) {
                $query->where('is_active', true);
            })
            ->when($status === 'disabled', function ($query) {
                $query->where('is_active', false);
            });

        switch ($sort) {
            case 'oldest':
                $customers->oldest();
                break;

            case 'name_asc':
                $customers->orderBy('name')->orderByDesc('id');
                break;

            case 'name_desc':
                $customers->orderByDesc('name')->orderByDesc('id');
                break;

            case 'orders_desc':
                $customers->orderByDesc('orders_count')->orderByDesc('id');
                break;

            default:
                $customers->latest();
                break;
        }

        $customers = $customers
            ->paginate(25)
            ->withQueryString();

        return view('admin.customers.index', compact('customers', 'stats'));
    }

    /**
     * Display one customer and their order history.
     */
    public function show(User $customer)
    {
        abort_unless($customer->role === 'customer', 404);

        $customer->load('addresses');

        $orderStats = $customer->orders()
            ->selectRaw('COUNT(*) as total_orders')
            ->selectRaw("SUM(CASE WHEN payment_status = 'paid' THEN total ELSE 0 END) as total_spent")
            ->selectRaw("SUM(CASE WHEN status = 'delivered' THEN 1 ELSE 0 END) as delivered_orders")
            ->selectRaw("SUM(CASE WHEN status IN ('pending', 'confirmed', 'processing', 'shipped') THEN 1 ELSE 0 END) as open_orders")
            ->first();

        $orders = $customer->orders()
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.customers.show', compact(
            'customer',
            'orders',
            'orderStats'
        ));
    }

    /**
     * Enable or disable a customer account.
     */
    public function toggle(User $customer)
    {
        abort_unless($customer->role === 'customer', 403);

        $customer->update([
            'is_active' => ! $customer->is_active,
        ]);

        return back()->with('success', 'Customer status updated.');
    }
}
