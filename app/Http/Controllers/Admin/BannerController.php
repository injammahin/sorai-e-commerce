<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Services\MediaService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BannerController extends Controller
{
    private const PLACEMENTS = [
        'home_hero',
        'home_popup',
        'home_promo',
        'category',
    ];

    public function __construct(
        private MediaService $media
    ) {
    }

    public function index(Request $request)
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:191'],

            'placement' => [
                'nullable',
                Rule::in(self::PLACEMENTS),
            ],

            'status' => [
                'nullable',
                Rule::in(['active', 'inactive']),
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
            ->when(
                filled($filters['q'] ?? null),
                function ($query) use ($filters) {
                    $search = trim($filters['q']);

                    $query->where(function ($query) use ($search) {
                        $query
                            ->where('title', 'like', "%{$search}%")
                            ->orWhere('eyebrow', 'like', "%{$search}%")
                            ->orWhere('subtitle', 'like', "%{$search}%")
                            ->orWhere('description', 'like', "%{$search}%")
                            ->orWhere('button_text', 'like', "%{$search}%")
                            ->orWhere('button_url', 'like', "%{$search}%");
                    });
                }
            )
            ->when(
                filled($filters['placement'] ?? null),
                fn ($query) => $query->where(
                    'placement',
                    $filters['placement']
                )
            )
            ->when(
                ($filters['status'] ?? null) === 'active',
                fn ($query) => $query->where('is_active', true)
            )
            ->when(
                ($filters['status'] ?? null) === 'inactive',
                fn ($query) => $query->where('is_active', false)
            );

        match ($filters['sort'] ?? 'placement_order') {
            'newest' => $query->latest(),
            'oldest' => $query->oldest(),
            'title_asc' => $query->orderBy('title'),
            'title_desc' => $query->orderByDesc('title'),

            default => $query
                ->orderBy('placement')
                ->orderBy('sort_order')
                ->orderByDesc('id'),
        };

        return view('admin.banners.index', [
            'banners' => $query
                ->paginate(25)
                ->withQueryString(),
        ]);
    }

    public function create()
    {
        return view('admin.banners.form', [
            'banner' => new Banner,
        ]);
    }

    public function store(Request $request)
    {
        $this->save(new Banner, $request);

        return redirect()
            ->route('admin.banners.index')
            ->with('success', 'Banner created successfully.');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.form', compact('banner'));
    }

    public function update(
        Request $request,
        Banner $banner
    ) {
        $this->save($banner, $request);

        return redirect()
            ->route('admin.banners.index')
            ->with('success', 'Banner updated successfully.');
    }

    public function destroy(Banner $banner)
    {
        $desktopImage = $banner->image;
        $mobileImage = $banner->mobile_image;

        $banner->delete();

        $this->media->delete($desktopImage);
        $this->media->delete($mobileImage);

        return back()->with(
            'success',
            'Banner deleted successfully.'
        );
    }

    private function save(
        Banner $banner,
        Request $request
    ): Banner {
        $data = $request->validate([
            'placement' => [
                'required',
                Rule::in(self::PLACEMENTS),
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
                $banner->exists ? 'nullable' : 'required',
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
                'required_if:placement,home_popup',
                'nullable',
                'string',
                'max:255',

                function ($attribute, $value, $fail) {
                    $url = trim((string) $value);

                    $isInternalUrl = preg_match(
                        '#^/(?!/)#',
                        $url
                    );

                    $isExternalUrl = preg_match(
                        '/^https?:\/\//i',
                        $url
                    );

                    if ($isInternalUrl || $isExternalUrl) {
                        return;
                    }

                    $fail(
                        'The destination URL must start with /, http:// or https://.'
                    );
                },
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

        if ($request->hasFile('image')) {
            $oldImage = $banner->image;

            $data['image'] = $this->media->store(
                $request->file('image'),
                'banners'
            );

            $this->media->delete($oldImage);
        }

        if ($request->hasFile('mobile_image')) {
            $oldMobileImage = $banner->mobile_image;

            $data['mobile_image'] = $this->media->store(
                $request->file('mobile_image'),
                'banners'
            );

            $this->media->delete($oldMobileImage);
        } elseif ($request->boolean('remove_mobile_image')) {
            $this->media->delete($banner->mobile_image);

            $data['mobile_image'] = null;
        }

        unset($data['remove_mobile_image']);

        $data['is_active'] = $request->boolean('is_active');

        if (array_key_exists('button_url', $data)) {
            $data['button_url'] = trim(
                (string) $data['button_url']
            );
        }

        $banner->fill($data)->save();

        return $banner;
    }
}