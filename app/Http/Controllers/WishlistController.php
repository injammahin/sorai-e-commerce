<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function index(): View
    {
        return view('store.wishlist', [
            'products' => auth()
                ->user()
                ->wishlistProducts()
                ->active()
                ->with('images')
                ->paginate(20),
        ]);
    }

    public function toggle(Product $product): RedirectResponse
    {
        $user = auth()->user();

        $alreadyFavourite = $user
            ->wishlistProducts()
            ->whereKey($product->id)
            ->exists();

        if ($alreadyFavourite) {
            $user->wishlistProducts()->detach($product->id);

            return back()->with(
                'success',
                'Removed from favourites.'
            );
        }

        $user->wishlistProducts()->attach($product->id);

        return back()->with(
            'success',
            'Added to favourites.'
        );
    }
}