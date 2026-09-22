<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use App\Models\Product;
use App\Services\MediaService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(private MediaService $media)
    {
    }

    public function index(Request $request)
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:191'],
            'level' => ['nullable', 'in:top,sub'],
            'status' => ['nullable', 'in:active,inactive'],
            'sort' => ['nullable', 'in:order,name_asc,name_desc,newest,oldest'],
        ]);

        $categories = Category::query()
            ->with('parent')
            ->withCount('children')
            ->when(! empty($filters['q']), function ($query) use ($filters) {
                $search = trim($filters['q']);

                $query->where(function ($nested) use ($search) {
                    $nested
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%")
                        ->orWhere('tagline', 'like', "%{$search}%")
                        ->orWhere('heading', 'like', "%{$search}%")
                        ->orWhereHas('parent', function ($parentQuery) use ($search) {
                            $parentQuery->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when(($filters['level'] ?? null) === 'top', function ($query) {
                $query->whereNull('parent_id');
            })
            ->when(($filters['level'] ?? null) === 'sub', function ($query) {
                $query->whereNotNull('parent_id');
            })
            ->when(($filters['status'] ?? null) === 'active', function ($query) {
                $query->where('is_active', true);
            })
            ->when(($filters['status'] ?? null) === 'inactive', function ($query) {
                $query->where('is_active', false);
            });

        switch ($filters['sort'] ?? 'order') {
            case 'name_asc':
                $categories->orderBy('name');
                break;

            case 'name_desc':
                $categories->orderByDesc('name');
                break;

            case 'newest':
                $categories->latest();
                break;

            case 'oldest':
                $categories->oldest();
                break;

            case 'order':
            default:
                $categories->ordered();
                break;
        }

        $categories = $categories
            ->paginate(30)
            ->withQueryString();

        return view(
            'admin.categories.index',
            compact('categories')
        );
    }

    public function create()
    {
        return view(
            'admin.categories.form',
            [
                'category' => new Category,

                'parents' => Category::whereNull('parent_id')
                    ->ordered()
                    ->get(),
            ]
        );
    }

    public function store(CategoryRequest $request)
    {
        $this->save(
            new Category,
            $request
        );

        return redirect()
            ->route('admin.categories.index')
            ->with(
                'success',
                'Category created successfully.'
            );
    }

    public function edit(Category $category)
    {
        $category->loadCount('children');

        return view(
            'admin.categories.form',
            [
                'category' => $category,

                'parents' => Category::whereNull('parent_id')
                    ->where(
                        'id',
                        '!=',
                        $category->id
                    )
                    ->ordered()
                    ->get(),
            ]
        );
    }

    public function update(
        CategoryRequest $request,
        Category $category
    ) {
        /*
        |--------------------------------------------------------------------------
        | Keep the category hierarchy at two levels
        |--------------------------------------------------------------------------
        */
        if (
            $category->children()->exists()
            &&
            $request->filled('parent_id')
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'parent_id' =>
                        'A category that already has child categories must remain a top-level category.',
                ]);
        }

        $this->save(
            $category,
            $request
        );

        return redirect()
            ->route('admin.categories.index')
            ->with(
                'success',
                'Category updated successfully.'
            );
    }

    public function destroy(Category $category)
    {
        /*
        |--------------------------------------------------------------------------
        | Do not delete a category that still has children
        |--------------------------------------------------------------------------
        */
        if ($category->children()->exists()) {
            return back()
                ->withErrors([
                    'category' =>
                        'This category has child categories. Move or delete the child categories first.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Check both category_id and subcategory_id.
        |
        | withTrashed() is important because products use soft deletes and
        | database foreign keys can still reference soft-deleted products.
        |--------------------------------------------------------------------------
        */
        $hasProducts = Product::withTrashed()
            ->where(
                function ($query) use ($category) {
                    $query
                        ->where(
                            'category_id',
                            $category->id
                        )
                        ->orWhere(
                            'subcategory_id',
                            $category->id
                        );
                }
            )
            ->exists();

        if ($hasProducts) {
            return back()
                ->withErrors([
                    'category' =>
                        'This category is still used by one or more products. Move those products to another category first.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Save media paths before deleting DB record
        |--------------------------------------------------------------------------
        */
        $image =
            $category->image;

        $bannerImage =
            $category->banner_image;

        /*
        |--------------------------------------------------------------------------
        | Delete category
        |--------------------------------------------------------------------------
        */
        $category->delete();

        /*
        |--------------------------------------------------------------------------
        | Remove unused media
        |--------------------------------------------------------------------------
        */
        $this->media->delete(
            $image
        );

        $this->media->delete(
            $bannerImage
        );

        return back()
            ->with(
                'success',
                'Category deleted successfully.'
            );
    }

    private function save(
        Category $category,
        CategoryRequest $request
    ): Category {
        $data =
            $request->validated();

        /*
        |--------------------------------------------------------------------------
        | Remove existing tile image
        |--------------------------------------------------------------------------
        */
        if (
            $request->boolean('remove_image')
            &&
            ! $request->hasFile('image')
        ) {
            $this->media->delete(
                $category->image
            );

            $data['image'] = null;
        }

        /*
        |--------------------------------------------------------------------------
        | Remove existing banner
        |--------------------------------------------------------------------------
        */
        if (
            $request->boolean(
                'remove_banner_image'
            )
            &&
            ! $request->hasFile(
                'banner_image'
            )
        ) {
            $this->media->delete(
                $category->banner_image
            );

            $data['banner_image'] = null;
        }

        /*
        |--------------------------------------------------------------------------
        | Replace tile image
        |--------------------------------------------------------------------------
        */
        if (
            $request->hasFile('image')
        ) {
            $this->media->delete(
                $category->image
            );

            $data['image'] =
                $this->media->store(
                    $request->file('image'),
                    'categories'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Replace banner image
        |--------------------------------------------------------------------------
        */
        if (
            $request->hasFile(
                'banner_image'
            )
        ) {
            $this->media->delete(
                $category->banner_image
            );

            $data['banner_image'] =
                $this->media->store(
                    $request->file(
                        'banner_image'
                    ),
                    'categories'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Boolean values
        |--------------------------------------------------------------------------
        */
        $data['is_active'] =
            $request->boolean(
                'is_active'
            );

        $data['show_in_menu'] =
            $request->boolean(
                'show_in_menu'
            );

        /*
        |--------------------------------------------------------------------------
        | Save
        |--------------------------------------------------------------------------
        */
        $category
            ->fill($data)
            ->save();

        return $category;
    }
}