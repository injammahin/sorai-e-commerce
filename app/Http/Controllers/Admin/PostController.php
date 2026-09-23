<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Services\HtmlSanitizer;
use App\Services\MediaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    public function __construct(
        private MediaService $media,
        private HtmlSanitizer $sanitizer
    ) {}


    /* =========================================================
        LIST
    ========================================================== */
    public function index(Request $request)
    {
        $query = Post::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('is_published', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('published_at', $request->date);
        }

        $posts = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $categories = Post::whereNotNull('category')
            ->distinct()
            ->pluck('category');

        return view('admin.posts.index', compact('posts', 'categories'));
    }


    /* =========================================================
        SUMMERNOTE IMAGE UPLOAD
    ========================================================== */
    public function editorImage(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,png,webp', 'max:4096'],
        ]);

        $path = $request->file('image')->store('posts/content', 'public');

        return response()->json([
            'url' => Storage::url($path),
        ]);
    }


    /* =========================================================
        CREATE
    ========================================================== */
    public function create()
    {
        return view('admin.posts.form', [
            'post' => new Post(),
        ]);
    }


    /* =========================================================
        STORE  →  redirect to list
    ========================================================== */
    public function store(Request $request)
    {
        $post = $this->save(new Post(), $request);

        return redirect()
            ->route('admin.posts.index')
            ->with('success', "Journal \"{$post->title}\" created successfully.");
    }


    /* =========================================================
        EDIT
    ========================================================== */
    public function edit(Post $post)
    {
        return view('admin.posts.form', compact('post'));
    }


    /* =========================================================
        UPDATE  →  redirect to list
    ========================================================== */
    public function update(Request $request, Post $post)
    {
        $this->save($post, $request);

        return redirect()
            ->route('admin.posts.index')
            ->with('success', "Journal \"{$post->title}\" updated successfully.");
    }


    /* =========================================================
        DELETE  →  redirect to list
    ========================================================== */
    public function destroy(Post $post)
    {
        if ($post->image) {
            $this->media->delete($post->image);
        }

        $post->delete();

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Journal deleted successfully.');
    }


    /* =========================================================
        SHARED SAVE LOGIC
    ========================================================== */
    private function save(Post $post, Request $request): Post
    {
        $data = $request->validate([
            'title'            => ['required', 'string', 'max:191'],
            'slug'             => ['required', 'alpha_dash', 'max:191', Rule::unique('posts')->ignore($post->id)],
            'category'         => ['nullable', 'string', 'max:100'],
            'excerpt'          => ['nullable', 'string', 'max:500'],
            'content'          => ['required', 'string'],
            'image'            => [$post->exists ? 'nullable' : 'required', 'image', 'mimes:jpeg,png,webp', 'max:6144'],
            'meta_title'       => ['nullable', 'string', 'max:70'],
            'meta_description' => ['nullable', 'string', 'max:170'],
            'published_at'     => ['nullable', 'date'],
        ]);

        // Clean article HTML
        $data['content'] = $this->sanitizer->clean($data['content']);

        // Feature image: store new first, then delete old
        if ($request->hasFile('image')) {
            $oldImage = $post->image;

            $data['image'] = $this->media->store($request->file('image'), 'journal');

            if ($oldImage) {
                $this->media->delete($oldImage);
            }
        } else {
            unset($data['image']);
        }

        // Keep the original author on update
        if (! $post->exists) {
            $data['author_id'] = auth()->id();
        }

        // Publishing
        $data['is_published'] = $request->boolean('is_published');

        $data['published_at'] = $data['is_published']
            ? ($data['published_at'] ?? $post->published_at ?? now())
            : null;

        $post->fill($data)->save();

        return $post;
    }
    public function show(Post $post)
{
    abort_unless($post->is_published && $post->published_at && $post->published_at->lte(now()), 404);

    $related = Post::where('is_published', true)
        ->where('published_at', '<=', now())
        ->whereKeyNot($post->id)
        ->when($post->category, fn ($q) => $q->where('category', $post->category))
        ->latest('published_at')
        ->take(3)
        ->get();

    return view('journal.show', compact('post', 'related'));
}
}