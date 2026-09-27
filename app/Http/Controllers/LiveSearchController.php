<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LiveSearchController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $term = trim((string) $request->query('q', ''));

        /*
        |--------------------------------------------------------------------------
        | Require at least 2 characters
        |--------------------------------------------------------------------------
        */

        if (mb_strlen($term) < 2) {

            return response()->json([
                'query' => $term,
                'products' => [],
            ]);

        }


        /*
        |--------------------------------------------------------------------------
        | Escape SQL LIKE wildcard characters
        |--------------------------------------------------------------------------
        */

        $escaped = addcslashes(
            $term,
            '%_\\'
        );

        $like = '%' . $escaped . '%';


        /*
        |--------------------------------------------------------------------------
        | Search products
        |--------------------------------------------------------------------------
        */

        $products = Product::query()
            ->active()

            ->with([
                'primaryImage',

                'category:id,name,slug',

                'subcategory:id,name,slug',
            ])

            ->where(function ($query) use ($like) {

                $query

                    ->where(
                        'name',
                        'like',
                        $like
                    )

                    ->orWhere(
                        'sku',
                        'like',
                        $like
                    )

                    ->orWhere(
                        'material',
                        'like',
                        $like
                    )

                    ->orWhere(
                        'short_description',
                        'like',
                        $like
                    )

                    ->orWhere(
                        'description',
                        'like',
                        $like
                    )

                    ->orWhereHas(
                        'category',
                        function ($categoryQuery) use ($like) {

                            $categoryQuery->where(
                                'name',
                                'like',
                                $like
                            );

                        }
                    )

                    ->orWhereHas(
                        'subcategory',
                        function ($subcategoryQuery) use ($like) {

                            $subcategoryQuery->where(
                                'name',
                                'like',
                                $like
                            );

                        }
                    );

            })


            /*
            |--------------------------------------------------------------------------
            | Exact / beginning matches first
            |--------------------------------------------------------------------------
            */

            ->orderByRaw(
                '
                CASE
                    WHEN name = ? THEN 0
                    WHEN name LIKE ? THEN 1
                    ELSE 2
                END
                ',
                [
                    $term,
                    $term . '%',
                ]
            )


            ->latest('id')

            ->limit(8)

            ->get();


        /*
        |--------------------------------------------------------------------------
        | JSON response
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'query' => $term,


            'products' => $products
                ->map(function (Product $product) {

                    return [

                        'id' =>
                            $product->id,


                        'name' =>
                            $product->name,


                        'url' =>
                            route(
                                'products.show',
                                $product
                            ),


                        'image' =>
                            asset(
                                optional(
                                    $product->primaryImage
                                )->path
                                ?: 'images/placeholder.webp'
                            ),


                        'category' =>
                            optional(
                                $product->subcategory
                            )->name

                            ?:
                            optional(
                                $product->category
                            )->name,


                        'material' =>
                            $product->material,


                        'price' =>
                            (float) $product->price,


                        'compare_price' =>
                            $product->compare_price
                                ? (float) $product->compare_price
                                : null,


                        'discount_percent' =>
                            (int) $product->discount_percent,


                        'is_new' =>
                            (bool) $product->is_new,


                        'stock' =>
                            (int) $product->stock,

                    ];

                })

                ->values(),

        ]);
    }
}