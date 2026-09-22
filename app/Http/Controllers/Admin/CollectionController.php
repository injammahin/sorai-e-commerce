<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Product;
use App\Services\MediaService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CollectionController extends Controller
{
    public function __construct(private MediaService $media)
    {
    }

    public function index(Request $request)
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:191'],
            'status' => ['nullable', 'in:active,inactive'],
            'sort' => [
                'nullable',
                'in:newest,oldest,title_asc,title_desc,products_high,products_low'
            ],
        ]);

        $query = Collection::query()
            ->withCount('products')

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
                                    'title',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'slug',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'tagline',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'description',
                                    'like',
                                    "%{$search}%"
                                );

                        }
                    );

                }
            )

            ->when(
                ($filters['status'] ?? null)
                ===
                'active',
                function ($query) {

                    $query->where(
                        'is_active',
                        true
                    );

                }
            )

            ->when(
                ($filters['status'] ?? null)
                ===
                'inactive',
                function ($query) {

                    $query->where(
                        'is_active',
                        false
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

                $query->oldest();

                break;


            case 'title_asc':

                $query->orderBy(
                    'title'
                );

                break;


            case 'title_desc':

                $query->orderByDesc(
                    'title'
                );

                break;


            case 'products_high':

                $query
                    ->orderByDesc(
                        'products_count'
                    )
                    ->latest('id');

                break;


            case 'products_low':

                $query
                    ->orderBy(
                        'products_count'
                    )
                    ->latest('id');

                break;


            case 'newest':

            default:

                $query->latest();

                break;

        }


        $collections = $query
            ->paginate(25)
            ->withQueryString();


        return view(
            'admin.collections.index',
            compact('collections')
        );
    }


    public function create()
    {
        return view(
            'admin.collections.form',
            [
                'collection' =>
                    new Collection,

                'products' =>
                    Product::active()
                        ->with(
                            'primaryImage'
                        )
                        ->orderBy(
                            'name'
                        )
                        ->get(),
            ]
        );
    }


    public function store(
        Request $request
    ) {
        $this->save(
            new Collection,
            $request
        );


        return redirect()
            ->route(
                'admin.collections.index'
            )
            ->with(
                'success',
                'Collection created successfully.'
            );
    }


    public function edit(
        Collection $collection
    ) {
        $collection->load(
            'products'
        );


        /*
        |--------------------------------------------------------------------------
        | Keep selected products available even if they were later hidden.
        |--------------------------------------------------------------------------
        */
        $selectedIds =
            $collection
                ->products
                ->pluck('id');


        $products =
            Product::query()
                ->with(
                    'primaryImage'
                )
                ->where(
                    function ($query) use ($selectedIds) {

                        $query
                            ->where(
                                'is_active',
                                true
                            )
                            ->orWhereIn(
                                'id',
                                $selectedIds
                            );

                    }
                )
                ->orderBy(
                    'name'
                )
                ->get();


        return view(
            'admin.collections.form',
            [
                'collection' =>
                    $collection,

                'products' =>
                    $products,
            ]
        );
    }


    public function update(
        Request $request,
        Collection $collection
    ) {
        $this->save(
            $collection,
            $request
        );


        return redirect()
            ->route(
                'admin.collections.index'
            )
            ->with(
                'success',
                'Collection updated successfully.'
            );
    }


    public function destroy(
        Collection $collection
    ) {
        $image =
            $collection->image;


        /*
        |--------------------------------------------------------------------------
        | collection_product uses cascadeOnDelete().
        |
        | Deleting a collection removes only its pivot links.
        | The actual products are NOT deleted.
        |--------------------------------------------------------------------------
        */
        $collection->delete();


        /*
        |--------------------------------------------------------------------------
        | Delete collection media
        |--------------------------------------------------------------------------
        */
        $this->media->delete(
            $image
        );


        return back()
            ->with(
                'success',
                'Collection deleted successfully. Products were not deleted.'
            );
    }


    private function save(
        Collection $collection,
        Request $request
    ): Collection {
        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */
        $data =
            $request->validate([

                'title' => [
                    'required',
                    'string',
                    'max:191',
                ],


                'slug' => [
                    'required',
                    'alpha_dash',
                    'max:191',

                    Rule::unique(
                        'collections',
                        'slug'
                    )
                        ->ignore(
                            $collection->id
                        ),
                ],


                'tagline' => [
                    'nullable',
                    'string',
                    'max:191',
                ],


                'description' => [
                    'nullable',
                    'string',
                    'max:5000',
                ],


                'image' => [
                    $collection->exists
                        ? 'nullable'
                        : 'required',

                    'image',

                    'mimes:jpg,jpeg,png,webp',

                    'max:6144',
                ],


                'products' => [
                    'nullable',
                    'array',
                ],


                'products.*' => [
                    'integer',
                    'distinct',

                    Rule::exists(
                        'products',
                        'id'
                    )
                        ->whereNull(
                            'deleted_at'
                        ),
                ],


                'is_active' => [
                    'nullable',
                    'boolean',
                ],

            ]);


        /*
        |--------------------------------------------------------------------------
        | Cover image
        |--------------------------------------------------------------------------
        */
        if (
            $request->hasFile(
                'image'
            )
        ) {
            $oldImage =
                $collection->image;


            /*
            |--------------------------------------------------------------------------
            | Store new image first.
            |--------------------------------------------------------------------------
            */
            $data['image'] =
                $this->media->store(
                    $request->file(
                        'image'
                    ),
                    'collections'
                );


            /*
            |--------------------------------------------------------------------------
            | Then remove old image.
            |--------------------------------------------------------------------------
            */
            if ($oldImage) {

                $this->media->delete(
                    $oldImage
                );

            }
        }


        /*
        |--------------------------------------------------------------------------
        | Active flag
        |--------------------------------------------------------------------------
        */
        $data['is_active'] =
            $request->boolean(
                'is_active'
            );


        /*
        |--------------------------------------------------------------------------
        | products[] belongs to the pivot table,
        | not the collections table.
        |--------------------------------------------------------------------------
        */
        unset(
            $data['products']
        );


        /*
        |--------------------------------------------------------------------------
        | Save collection
        |--------------------------------------------------------------------------
        */
        $collection
            ->fill($data)
            ->save();


        /*
        |--------------------------------------------------------------------------
        | Selected products
        |--------------------------------------------------------------------------
        */
        $selectedProductIds =
            collect(
                $request->input(
                    'products',
                    []
                )
            )
                ->map(
                    fn ($id) =>
                        (int) $id
                )
                ->filter()
                ->unique()
                ->values();


        /*
        |--------------------------------------------------------------------------
        | Preserve collection_product.sort_order
        |--------------------------------------------------------------------------
        */
        $syncData =
            $selectedProductIds
                ->mapWithKeys(
                    function (
                        $id,
                        $index
                    ) {

                        return [
                            $id => [
                                'sort_order' =>
                                    $index,
                            ],
                        ];

                    }
                )
                ->all();


        /*
        |--------------------------------------------------------------------------
        | Sync products
        |--------------------------------------------------------------------------
        */
        $collection
            ->products()
            ->sync(
                $syncData
            );


        return $collection;
    }
}