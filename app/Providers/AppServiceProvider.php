<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Setting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register application services.
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap application services.
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Paginator::useTailwind();

        /*
        |--------------------------------------------------------------------------
        | Storefront shared data
        |--------------------------------------------------------------------------
        |
        | These variables are required by the storefront layout, header, footer
        | and storefront pages such as the homepage.
        |
        */

        View::composer([
            'layouts.store',
            'components.header',
            'components.footer',
            'store.*',
        ], function ($view) {
            /*
             * Static variables prevent Laravel from executing the same database
             * queries multiple times while rendering the layout, header,
             * footer and current storefront page.
             */
            static $navCategories = null;
            static $siteSettings = null;

            if ($navCategories === null) {
                $navCategories = rescue(
                    function () {
                        return Category::query()
                            ->active()
                            ->whereNull('parent_id')
                            ->with([
                                'children' => function ($query) {
                                    $query
                                        ->active()
                                        ->ordered();
                                },
                            ])
                            ->ordered()
                            ->get();
                    },
                    collect()
                );
            }

            if ($siteSettings === null) {
                $siteSettings = rescue(
                    fn () => Setting::publicMap(),
                    []
                );
            }

            $view->with([
                'navCategories' => $navCategories,
                'siteSettings'  => $siteSettings,
            ]);
        });
    }
}