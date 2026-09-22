<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Services\MediaService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BannerController extends Controller
{
    public function __construct(
        private MediaService $media
    ) {
    }


    /* =========================================================
        LIST
    ========================================================== */

    public function index(
        Request $request
    ) {
        $filters = $request->validate([

            'q' => [
                'nullable',
                'string',
                'max:191',
            ],

            'placement' => [
                'nullable',
                Rule::in([
                    'home_hero',
                    'home_promo',
                    'category',
                ]),
            ],

            'status' => [
                'nullable',
                Rule::in([
                    'active',
                    'inactive',
                ]),
            ],

            'sort' => [
                'nullable',
                Rule::in([
                    'placement_order',
                    'newest',
                    'oldest',
                    'title_asc',
                    'title_desc',
                ]),
            ],

        ]);


        $query = Banner::query()

            /* =====================================================
                SEARCH
            ====================================================== */
            ->when(
                ! empty(
                    $filters['q']
                ),
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
                                    'eyebrow',
                                    'like',
                                    "%{$search}%"
                                )

                                ->orWhere(
                                    'subtitle',
                                    'like',
                                    "%{$search}%"
                                )

                                ->orWhere(
                                    'description',
                                    'like',
                                    "%{$search}%"
                                )

                                ->orWhere(
                                    'button_text',
                                    'like',
                                    "%{$search}%"
                                )

                                ->orWhere(
                                    'button_url',
                                    'like',
                                    "%{$search}%"
                                );

                        }
                    );

                }
            )


            /* =====================================================
                PLACEMENT
            ====================================================== */
            ->when(
                ! empty(
                    $filters['placement']
                ),
                function ($query) use ($filters) {

                    $query->where(
                        'placement',
                        $filters['placement']
                    );

                }
            )


            /* =====================================================
                STATUS
            ====================================================== */
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


        /* =========================================================
            SORT
        ========================================================== */

        switch (
            $filters['sort']
            ??
            'placement_order'
        ) {

            case 'newest':

                $query->latest();

                break;


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


            case 'placement_order':

            default:

                $query
                    ->orderBy(
                        'placement'
                    )
                    ->orderBy(
                        'sort_order'
                    )
                    ->orderByDesc(
                        'id'
                    );

                break;

        }


        $banners = $query
            ->paginate(25)
            ->withQueryString();


        return view(
            'admin.banners.index',
            compact(
                'banners'
            )
        );
    }


    /* =========================================================
        CREATE
    ========================================================== */

    public function create()
    {
        return view(
            'admin.banners.form',
            [
                'banner' =>
                    new Banner,
            ]
        );
    }


    /* =========================================================
        STORE
    ========================================================== */

    public function store(
        Request $request
    ) {
        $this->save(
            new Banner,
            $request
        );


        return redirect()
            ->route(
                'admin.banners.index'
            )
            ->with(
                'success',
                'Banner created successfully.'
            );
    }


    /* =========================================================
        EDIT
    ========================================================== */

    public function edit(
        Banner $banner
    ) {
        return view(
            'admin.banners.form',
            compact(
                'banner'
            )
        );
    }


    /* =========================================================
        UPDATE
    ========================================================== */

    public function update(
        Request $request,
        Banner $banner
    ) {
        $this->save(
            $banner,
            $request
        );


        return redirect()
            ->route(
                'admin.banners.index'
            )
            ->with(
                'success',
                'Banner updated successfully.'
            );
    }


    /* =========================================================
        DELETE
    ========================================================== */

    public function destroy(
        Banner $banner
    ) {
        /*
        |--------------------------------------------------------------------------
        | Keep paths before deleting record
        |--------------------------------------------------------------------------
        */

        $desktopImage =
            $banner->image;


        $mobileImage =
            $banner->mobile_image;


        /*
        |--------------------------------------------------------------------------
        | Delete record
        |--------------------------------------------------------------------------
        */

        $banner->delete();


        /*
        |--------------------------------------------------------------------------
        | Delete stored images
        |--------------------------------------------------------------------------
        */

        $this->media->delete(
            $desktopImage
        );


        $this->media->delete(
            $mobileImage
        );


        return back()
            ->with(
                'success',
                'Banner deleted successfully.'
            );
    }


    /* =========================================================
        SAVE
    ========================================================== */

    private function save(
        Banner $banner,
        Request $request
    ): Banner {

        $data = $request->validate([

            'placement' => [
                'required',

                Rule::in([
                    'home_hero',
                    'home_promo',
                    'category',
                ]),
            ],


            'eyebrow' => [
                'nullable',
                'string',
                'max:100',
            ],


            'title' => [
                'required',
                'string',
                'max:191',
            ],


            'subtitle' => [
                'nullable',
                'string',
                'max:191',
            ],


            'description' => [
                'nullable',
                'string',
                'max:500',
            ],


            'image' => [

                $banner->exists
                    ? 'nullable'
                    : 'required',

                'image',

                'mimes:jpg,jpeg,png,webp',

                'max:6144',
            ],


            'mobile_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:6144',
            ],


            'remove_mobile_image' => [
                'nullable',
                'boolean',
            ],


            'button_text' => [
                'nullable',
                'string',
                'max:60',
            ],


            'button_url' => [
                'nullable',
                'string',
                'max:255',
            ],


            'sort_order' => [
                'required',
                'integer',
                'min:0',
            ],


            'is_active' => [
                'nullable',
                'boolean',
            ],


            'starts_at' => [
                'nullable',
                'date',
            ],


            'ends_at' => [
                'nullable',
                'date',
                'after:starts_at',
            ],

        ]);


        /* =========================================================
            DESKTOP IMAGE
        ========================================================== */

        if (
            $request->hasFile(
                'image'
            )
        ) {

            $oldImage =
                $banner->image;


            $data['image'] =
                $this->media->store(
                    $request->file(
                        'image'
                    ),
                    'banners'
                );


            $this->media->delete(
                $oldImage
            );

        }


        /* =========================================================
            MOBILE IMAGE
        ========================================================== */

        if (
            $request->hasFile(
                'mobile_image'
            )
        ) {

            $oldMobileImage =
                $banner->mobile_image;


            $data['mobile_image'] =
                $this->media->store(
                    $request->file(
                        'mobile_image'
                    ),
                    'banners'
                );


            $this->media->delete(
                $oldMobileImage
            );

        }
        elseif (
            $request->boolean(
                'remove_mobile_image'
            )
        ) {

            $this->media->delete(
                $banner->mobile_image
            );


            $data['mobile_image'] =
                null;

        }


        /*
        |--------------------------------------------------------------------------
        | This is not a DB column
        |--------------------------------------------------------------------------
        */

        unset(
            $data['remove_mobile_image']
        );


        /* =========================================================
            ACTIVE
        ========================================================== */

        $data['is_active'] =
            $request->boolean(
                'is_active'
            );


        /* =========================================================
            SAVE
        ========================================================== */

        $banner
            ->fill(
                $data
            )
            ->save();


        return $banner;
    }
}