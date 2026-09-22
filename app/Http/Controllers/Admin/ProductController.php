<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Services\MediaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function __construct(private MediaService $media)
    {
    }

    public function index(Request $request)
    {
        $query = Product::query()
            ->with(['category', 'primaryImage'])
            ->search($request->input('q'));

        /*
        |--------------------------------------------------------------------------
        | Status filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('status')) {
            if ($request->input('status') === 'active') {
                $query->where('is_active', true);
            }

            if ($request->input('status') === 'inactive') {
                $query->where('is_active', false);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Category filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('category')) {
            $query->where(
                'category_id',
                $request->integer('category')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Stock filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('stock')) {
            switch ($request->input('stock')) {

                case 'in_stock':
                    $query->where('stock', '>', 0);
                    break;

                case 'low_stock':
                    $query
                        ->where('stock', '>', 0)
                        ->whereColumn(
                            'stock',
                            '<=',
                            'low_stock_threshold'
                        );
                    break;

                case 'out_of_stock':
                    $query->where('stock', 0);
                    break;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */
        switch ($request->input('sort')) {

            case 'oldest':
                $query->oldest();
                break;

            case 'name_asc':
                $query->orderBy('name');
                break;

            case 'name_desc':
                $query->orderByDesc('name');
                break;

            case 'price_asc':
                $query->orderBy('price');
                break;

            case 'price_desc':
                $query->orderByDesc('price');
                break;

            case 'stock_asc':
                $query->orderBy('stock');
                break;

            case 'stock_desc':
                $query->orderByDesc('stock');
                break;

            default:
                $query->latest();
                break;
        }

        /*
        |--------------------------------------------------------------------------
        | Products
        |--------------------------------------------------------------------------
        */
        $products = $query
            ->paginate(20)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Parent categories for filter
        |--------------------------------------------------------------------------
        */
        $categories = Category::query()
            ->whereNull('parent_id')
            ->ordered()
            ->get([
                'id',
                'name',
            ]);

        return view(
            'admin.products.index',
            compact(
                'products',
                'categories'
            )
        );
    }

    public function create()
    {
        return view(
            'admin.products.form',
            [
                'product' => new Product,

                'categories' => Category::whereNull('parent_id')
                    ->with('children')
                    ->ordered()
                    ->get(),
            ]
        );
    }

    public function store(ProductRequest $request)
    {
        $this->save(
            new Product,
            $request
        );

        /*
        |--------------------------------------------------------------------------
        | After create return to list
        |--------------------------------------------------------------------------
        */
        return redirect()
            ->route('admin.products.index')
            ->with(
                'success',
                'Product created successfully.'
            );
    }

    public function edit(Product $product)
    {
        $product->load('images');

        return view(
            'admin.products.form',
            [
                'product' => $product,

                'categories' => Category::whereNull('parent_id')
                    ->with('children')
                    ->ordered()
                    ->get(),
            ]
        );
    }

    public function update(
        ProductRequest $request,
        Product $product
    ) {
        $this->save(
            $product,
            $request
        );

        /*
        |--------------------------------------------------------------------------
        | After update return to list
        |--------------------------------------------------------------------------
        */
        return redirect()
            ->route('admin.products.index')
            ->with(
                'success',
                'Product updated successfully.'
            );
    }

    public function destroy(Product $product)
    {
        /*
        |--------------------------------------------------------------------------
        | Soft delete
        |--------------------------------------------------------------------------
        */
        $product->delete();

        return back()
            ->with(
                'success',
                'Product moved to trash.'
            );
    }

    public function removeImage(
        Product $product,
        $image
    ) {
        $img = $product
            ->images()
            ->findOrFail($image);

        /*
        |--------------------------------------------------------------------------
        | Remove physical image
        |--------------------------------------------------------------------------
        */
        $this->media->delete(
            $img->path
        );

        /*
        |--------------------------------------------------------------------------
        | Remove database image
        |--------------------------------------------------------------------------
        */
        $img->delete();

        /*
        |--------------------------------------------------------------------------
        | If primary image was removed,
        | make next available image primary
        |--------------------------------------------------------------------------
        */
        if (
            ! $product
                ->images()
                ->where(
                    'is_primary',
                    true
                )
                ->exists()
        ) {
            $product
                ->images()
                ->first()
                ?->update([
                    'is_primary' => true,
                ]);
        }

        return back()
            ->with(
                'success',
                'Image removed.'
            );
    }

    private function save(
        Product $product,
        ProductRequest $request
    ) {
        return DB::transaction(
            function () use (
                $product,
                $request
            ) {

                /*
                |--------------------------------------------------------------------------
                | Validated data
                |--------------------------------------------------------------------------
                */
                $data = $request->validated();

                /*
                |--------------------------------------------------------------------------
                | Product colour palette
                |--------------------------------------------------------------------------
                */
                $palette = [

                    'Ivory' =>
                        '#efe6d6',

                    'Cream' =>
                        '#e8ddc9',

                    'Indigo' =>
                        '#22344f',

                    'Madder' =>
                        '#8e2f2a',

                    'Terracotta' =>
                        '#b4572f',

                    'Copper' =>
                        '#a8542a',

                    'Gold' =>
                        '#b98d33',

                    'Wine' =>
                        '#6b2233',

                    'Teal' =>
                        '#245b56',

                    'Olive' =>
                        '#5f6a3c',

                    'Charcoal' =>
                        '#2c2f33',

                    'Sage' =>
                        '#8c9a86',

                    'Sand' =>
                        '#c8b394',
                ];

                /*
                |--------------------------------------------------------------------------
                | Convert colour string into structured JSON
                |--------------------------------------------------------------------------
                */
                $data['colors'] = collect(
                    explode(
                        ',',
                        $data['colors'] ?? ''
                    )
                )
                    ->map(
                        fn ($item) =>
                            trim($item)
                    )
                    ->filter()
                    ->map(
                        fn ($name) => [
                            'name' =>
                                $name,

                            'hex' =>
                                $palette[$name]
                                ??
                                '#c9c4bb',
                        ]
                    )
                    ->values()
                    ->all();

                /*
                |--------------------------------------------------------------------------
                | Convert sizes & tags to arrays
                |--------------------------------------------------------------------------
                */
                foreach (
                    [
                        'sizes',
                        'tags',
                    ]
                    as $field
                ) {
                    $data[$field] = collect(
                        explode(
                            ',',
                            $data[$field] ?? ''
                        )
                    )
                        ->map(
                            fn ($item) =>
                                trim($item)
                        )
                        ->filter()
                        ->values()
                        ->all();
                }

                /*
                |--------------------------------------------------------------------------
                | Boolean fields
                |--------------------------------------------------------------------------
                */
                foreach (
                    [
                        'is_active',
                        'is_featured',
                        'is_new',
                        'is_bestseller',
                        'is_limited',
                    ]
                    as $field
                ) {
                    $data[$field] =
                        $request->boolean(
                            $field
                        );
                }

                /*
                |--------------------------------------------------------------------------
                | Save product
                |--------------------------------------------------------------------------
                */
                $product
                    ->fill($data)
                    ->save();

                /*
                |--------------------------------------------------------------------------
                | Existing images
                |--------------------------------------------------------------------------
                */
                $existingImages =
                    $product
                        ->images()
                        ->count();

                /*
                |--------------------------------------------------------------------------
                | New uploaded images
                |--------------------------------------------------------------------------
                */
                $files =
                    $request->file(
                        'images',
                        []
                    );

                /*
                |--------------------------------------------------------------------------
                | Maximum images = 6
                |--------------------------------------------------------------------------
                */
                abort_if(
                    $existingImages
                    +
                    count($files)
                    >
                    6,
                    422,
                    'A product can have no more than six images.'
                );

                /*
                |--------------------------------------------------------------------------
                | Next sort order
                |--------------------------------------------------------------------------
                */
                $nextSortOrder =
                    (int) $product
                        ->images()
                        ->max('sort_order')
                    +
                    1;

                /*
                |--------------------------------------------------------------------------
                | Store images
                |--------------------------------------------------------------------------
                */
                foreach (
                    $files
                    as
                    $index => $file
                ) {
                    $product
                        ->images()
                        ->create([
                            'path' =>
                                $this
                                    ->media
                                    ->store(
                                        $file,
                                        'products'
                                    ),

                            'alt_text' =>
                                $request->input(
                                    "image_alt.$index"
                                )
                                ?:
                                $product->name,

                            'sort_order' =>
                                $nextSortOrder
                                +
                                $index,

                            'is_primary' =>
                                $existingImages === 0
                                &&
                                $index === 0,
                        ]);
                }

                return $product;
            }
        );
    }
}