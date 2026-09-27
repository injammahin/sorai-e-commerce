<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Collection;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function sitemap(): Response
    {
        return response()
            ->view(
                'sitemap',
                [

                    'products' => Product::active()
                        ->select(
                            'slug',
                            'updated_at'
                        )
                        ->get(),


                    'categories' => Category::active()
                        ->select(
                            'slug',
                            'updated_at',
                            'parent_id'
                        )
                        ->get(),


                    'collections' => Collection::where(
                        'is_active',
                        true
                    )
                        ->select(
                            'slug',
                            'updated_at'
                        )
                        ->get(),


                    /*
                    |--------------------------------------------------------------------------
                    | Static information pages
                    |--------------------------------------------------------------------------
                    */

                    'pages' => array_keys(
                        config(
                            'aatchala_pages.pages',
                            []
                        )
                    ),


                    'posts' => Post::published()
                        ->select(
                            'slug',
                            'updated_at'
                        )
                        ->get(),

                ]
            )
            ->header(
                'Content-Type',
                'application/xml'
            );
    }



    public function robots(): Response
    {
        return response(
            "User-agent: *\n" .
            "Allow: /\n" .
            "Disallow: /admin\n" .
            "Disallow: /account\n" .
            "Disallow: /checkout\n" .
            'Sitemap: ' .
            url('/sitemap.xml') .
            "\n",
            200,
            [
                'Content-Type' =>
                    'text/plain',
            ]
        );
    }
}