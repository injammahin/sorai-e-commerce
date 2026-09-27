<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class StaticPageController extends Controller
{
    public function show(string $slug): View
    {
        $pages = config('aatchala_pages.pages', []);

        abort_unless(array_key_exists($slug, $pages), 404);

        return view('pages.static', [
            'slug' => $slug,
            'page' => $pages[$slug],
            'staticPages' => $pages,
        ]);
    }
}