@extends('admin.layouts.app')

@section('title', $post->exists ? 'Edit Article' : 'New Article')
@section('page_title', $post->exists ? 'Edit Article' : 'Create Article')

@section('content')

@php
    $hasEditorUpload = Route::has('admin.posts.editor-image');
    $publicUrl       = ($post->exists && Route::has('blog.show')) ? route('blog.show', $post) : null;
    $canDelete       = $post->exists && Route::has('admin.posts.destroy');
    $currentImage    = $post->image ? asset($post->image) : null;
    $isPublished     = (bool) old('is_published', $post->is_published ?? false);
@endphp

<div class="max-w-screen-2xl mx-auto">

    {{-- =========================================================
        PAGE HEADING
    ========================================================== --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <div class="mb-1 flex items-center gap-2 text-xs text-slate-500">
                <a href="{{ route('admin.posts.index') }}" class="transition hover:text-copper-500">Articles</a>
                <i class="fa-solid fa-chevron-right text-[9px]"></i>
                <span>{{ $post->exists ? 'Edit' : 'Create' }}</span>
            </div>

            <h2 class="text-2xl font-semibold text-slate-900">
                {{ $post->exists ? $post->title : 'Write a new article' }}
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                {{ $post->exists
                    ? 'Update the article content, feature image, SEO and publishing settings.'
                    : 'Write, format and publish a new article for the AATCHALA journal.' }}
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.posts.index') }}" class="admin-btn admin-btn-light">
                <i class="fa-solid fa-arrow-left"></i>
                Back to articles
            </a>

            @if($publicUrl)
                <a href="{{ $publicUrl }}" target="_blank" class="admin-btn admin-btn-light">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    View article
                </a>
            @endif
        </div>

    </div>


    {{-- =========================================================
        VALIDATION SUMMARY
    ========================================================== --}}
    @if($errors->any())
        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-red-800">
            <div class="flex items-start gap-3">
                <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
                <div>
                    <p class="text-sm font-semibold">Please correct the highlighted fields.</p>
                    <ul class="mt-2 list-disc space-y-1 pl-5 text-xs">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    @if(!$post->exists || $errors->has('image'))
                        <p class="mt-2 text-[11px] text-red-600">
                            Note: browsers cannot keep selected files after a failed submit — please choose the feature image again.
                        </p>
                    @endif
                </div>
            </div>
        </div>
    @endif


    {{-- =========================================================
        MAIN ARTICLE FORM
    ========================================================== --}}
    <form
        id="post-form"
        action="{{ $post->exists ? route('admin.posts.update', $post) : route('admin.posts.store') }}"
        method="POST"
        enctype="multipart/form-data"
        novalidate
    >
        @csrf
        @if($post->exists)
            @method('PUT')
        @endif

        <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_350px] xl:items-start">

            {{-- =================================================
                LEFT CONTENT
            ================================================== --}}
            <div class="grid min-w-0 gap-6">

                {{-- =============================================
                    ARTICLE DETAILS
                ============================================== --}}
                <section class="admin-card overflow-hidden">

                    <div class="flex items-center gap-3 border-b border-slate-100 px-5 py-4 sm:px-6">
                        <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-orange-50 text-copper-500">
                            <i class="fa-solid fa-feather-pointed"></i>
                        </span>
                        <div>
                            <h3 class="font-semibold text-slate-900">Article details</h3>
                            <p class="mt-0.5 text-xs text-slate-500">Headline, URL and the summary shown on article cards.</p>
                        </div>
                    </div>

                    <div class="grid gap-5 p-5 sm:p-6">

                        {{-- TITLE --}}
                        <label class="block">
                            <span class="admin-label">
                                Title <span class="text-red-500">*</span>
                            </span>

                            <input
                                id="post-title"
                                class="admin-input !py-3 text-base font-medium @error('title') !border-red-400 @enderror"
                                name="title"
                                value="{{ old('title', $post->title) }}"
                                maxlength="191"
                                autocomplete="off"
                                placeholder="e.g. The story behind our hand-woven jamdani"
                                required
                            >

                            @error('title')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </label>

                        {{-- SLUG --}}
                        <label class="block">
                            <span class="admin-label">
                                Slug <span class="text-red-500">*</span>
                            </span>

                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 grid w-10 place-items-center text-slate-400">
                                    <i class="fa-solid fa-link text-xs"></i>
                                </span>

                                <input
                                    id="post-slug"
                                    class="admin-input !pl-10 @error('slug') !border-red-400 @enderror"
                                    name="slug"
                                    value="{{ old('slug', $post->slug) }}"
                                    maxlength="191"
                                    autocomplete="off"
                                    required
                                >
                            </div>

                            <span class="mt-1 block text-[11px] text-slate-400">
                                Generated from the title. Used in the article URL.
                            </span>

                            @error('slug')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </label>

                        {{-- EXCERPT --}}
                        <label class="block">
                            <div class="flex items-center justify-between gap-3">
                                <span class="admin-label">Excerpt</span>
                                <span id="excerpt-count" class="text-[10px] text-slate-400">0 / 300</span>
                            </div>

                            <textarea
                                id="post-excerpt"
                                class="admin-input min-h-[92px] resize-y @error('excerpt') !border-red-400 @enderror"
                                name="excerpt"
                                rows="3"
                                maxlength="300"
                                placeholder="A short summary shown on the blog listing and article cards."
                            >{{ old('excerpt', $post->excerpt) }}</textarea>

                            @error('excerpt')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </label>

                    </div>
                </section>


                {{-- =============================================
                    ARTICLE CONTENT (SUMMERNOTE)
                ============================================== --}}
                <section class="admin-card overflow-hidden">

                    <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">

                        <div class="flex items-center gap-3">
                            <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-violet-50 text-violet-600">
                                <i class="fa-solid fa-pen-nib"></i>
                            </span>
                            <div>
                                <h3 class="font-semibold text-slate-900">
                                    Article content <span class="text-red-500">*</span>
                                </h3>
                                <p class="mt-0.5 text-xs text-slate-500">
                                    Rich text editor. Safe HTML is supported — scripts are removed on save.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-[11px] font-semibold text-slate-600">
                                <i class="fa-solid fa-font text-[10px] text-slate-400"></i>
                                <span id="word-count">0</span> words
                            </span>
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-[11px] font-semibold text-slate-600">
                                <i class="fa-regular fa-clock text-[10px] text-slate-400"></i>
                                <span id="read-time">1</span> min read
                            </span>
                        </div>

                    </div>

                    <div class="p-5 sm:p-6">

                        <div id="editor-wrapper" class="@error('content') is-invalid @enderror">
                            <textarea
                                id="article-content"
                                name="content"
                                class="admin-input font-mono"
                                rows="20"
                            >{{ old('content', $post->content) }}</textarea>
                        </div>

                        <p id="editor-notice" class="mt-2 hidden text-xs"></p>

                        @error('content')
                            <span class="error">{{ $message }}</span>
                        @enderror

                        <p class="mt-3 text-[11px] text-slate-400">
                            <i class="fa-solid fa-lightbulb mr-1"></i>
                            Tip: use <strong>Heading 2</strong> for main sections and <strong>Heading 3</strong> for sub-sections — it helps SEO.
                            @if($hasEditorUpload)
                                Drag images straight into the editor to upload them.
                            @endif
                        </p>

                    </div>
                </section>


                {{-- =============================================
                    SEO
                ============================================== --}}
                <section class="admin-card overflow-hidden">

                    <div class="flex items-center gap-3 border-b border-slate-100 px-5 py-4 sm:px-6">
                        <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-sky-50 text-sky-600">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                        <div>
                            <h3 class="font-semibold text-slate-900">Search engine optimization</h3>
                            <p class="mt-0.5 text-xs text-slate-500">Leave empty to fall back to the title and excerpt.</p>
                        </div>
                    </div>

                    <div class="grid gap-5 p-5 sm:p-6">

                        {{-- GOOGLE PREVIEW --}}
                        <div class="rounded-xl border border-slate-200 bg-slate-50/60 p-4">
                            <p class="mb-2 text-[10px] font-semibold uppercase tracking-wider text-slate-400">
                                Search preview
                            </p>
                            <p id="seo-preview-url" class="truncate text-xs text-emerald-700">
                                {{ url('blog') }}/
                            </p>
                            <p id="seo-preview-title" class="mt-1 truncate text-lg leading-6 text-[#1a0dab]">
                                Article title
                            </p>
                            <p id="seo-preview-description" class="mt-1 line-clamp-2 text-xs leading-5 text-slate-600">
                                Add a meta description to control how this article appears in search results.
                            </p>
                        </div>

                        {{-- META TITLE --}}
                        <label class="block">
                            <div class="flex items-center justify-between gap-3">
                                <span class="admin-label">Meta title</span>
                                <span id="meta-title-count" class="text-[10px] text-slate-400">0 / 70</span>
                            </div>

                            <input
                                id="meta-title"
                                class="admin-input @error('meta_title') !border-red-400 @enderror"
                                maxlength="70"
                                name="meta_title"
                                value="{{ old('meta_title', $post->meta_title) }}"
                            >

                            @error('meta_title')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </label>

                        {{-- META DESCRIPTION --}}
                        <label class="block">
                            <div class="flex items-center justify-between gap-3">
                                <span class="admin-label">Meta description</span>
                                <span id="meta-description-count" class="text-[10px] text-slate-400">0 / 170</span>
                            </div>

                            <textarea
                                id="meta-description"
                                class="admin-input min-h-[100px] resize-y @error('meta_description') !border-red-400 @enderror"
                                maxlength="170"
                                name="meta_description"
                                rows="4"
                            >{{ old('meta_description', $post->meta_description) }}</textarea>

                            @error('meta_description')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </label>

                    </div>
                </section>

            </div>


            {{-- =================================================
                RIGHT SIDEBAR
            ================================================== --}}
            <aside class="grid gap-6 xl:sticky xl:top-[94px]">

                {{-- =============================================
                    PUBLISH
                ============================================== --}}
                <section class="admin-card overflow-hidden">

                    <div class="flex items-center justify-between gap-3 border-b border-slate-100 px-5 py-4">
                        <div>
                            <h3 class="font-semibold text-slate-900">Publish</h3>
                            <p class="mt-0.5 text-xs text-slate-500">Control visibility and timing.</p>
                        </div>

                        <span id="publish-status" class="rounded-full px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider">
                            Draft
                        </span>
                    </div>

                    <div class="grid gap-4 p-5">

                        {{-- PUBLISH TOGGLE --}}
                        <input type="hidden" name="is_published" value="0">

                        <label
                            for="is-published"
                            class="flex cursor-pointer items-center justify-between gap-4 rounded-xl border border-slate-200 p-3.5 transition hover:border-slate-300"
                        >
                            <span>
                                <span class="block text-sm font-medium text-slate-800">Publish article</span>
                                <span class="mt-0.5 block text-[11px] text-slate-500">Make it visible on the blog.</span>
                            </span>

                            <span class="relative inline-flex shrink-0">
                                <input
                                    id="is-published"
                                    type="checkbox"
                                    name="is_published"
                                    value="1"
                                    class="peer sr-only"
                                    @checked($isPublished)
                                >
                                <span class="h-6 w-11 rounded-full bg-slate-200 transition peer-checked:bg-forest peer-focus-visible:ring-2 peer-focus-visible:ring-copper-500 peer-focus-visible:ring-offset-2"></span>
                                <span class="pointer-events-none absolute left-0.5 top-0.5 h-5 w-5 rounded-full bg-white shadow transition peer-checked:translate-x-5"></span>
                            </span>
                        </label>

                        {{-- PUBLISH DATE --}}
                        <label class="block">
                            <span class="admin-label">Publish date</span>

                            <input
                                id="published-at"
                                class="admin-input @error('published_at') !border-red-400 @enderror"
                                type="datetime-local"
                                name="published_at"
                                value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i')) }}"
                            >

                            <span class="mt-1 block text-[11px] text-slate-400">
                                Leave empty to publish immediately. A future date schedules the article.
                            </span>

                            @error('published_at')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </label>

                        @if($post->exists)
                            <div class="grid gap-1.5 rounded-lg bg-slate-50 px-3 py-2.5 text-[11px] text-slate-500">
                                <div class="flex justify-between gap-2">
                                    <span>Created</span>
                                    <span class="font-medium text-slate-700">{{ $post->created_at?->format('d M Y, h:i A') }}</span>
                                </div>
                                <div class="flex justify-between gap-2">
                                    <span>Last updated</span>
                                    <span class="font-medium text-slate-700">{{ $post->updated_at?->diffForHumans() }}</span>
                                </div>
                            </div>
                        @endif

                        <div class="border-t border-slate-100 pt-4">
                            <button type="submit" class="js-submit-btn admin-btn w-full !py-3">
                                <i class="fa-solid {{ $post->exists ? 'fa-floppy-disk' : 'fa-paper-plane' }}"></i>
                                <span>{{ $post->exists ? 'Save changes' : 'Save article' }}</span>
                            </button>
                        </div>

                    </div>
                </section>


                {{-- =============================================
                    FEATURE IMAGE
                ============================================== --}}
                <section class="admin-card overflow-hidden">

                    <div class="flex items-center justify-between gap-3 border-b border-slate-100 px-5 py-4">
                        <div>
                            <h3 class="font-semibold text-slate-900">
                                Feature image
                                @unless($post->exists)
                                    <span class="text-red-500">*</span>
                                @endunless
                            </h3>
                            <p class="mt-0.5 text-xs text-slate-500">Cover image for cards and social sharing.</p>
                        </div>

                        <span class="grid h-9 w-9 place-items-center rounded-xl bg-violet-50 text-violet-600">
                            <i class="fa-regular fa-image"></i>
                        </span>
                    </div>

                    <div class="p-5">

                        <div
                            id="feature-dropzone"
                            class="relative overflow-hidden rounded-xl border-2 border-dashed border-slate-200 bg-slate-50 transition hover:border-copper-300 @error('image') !border-red-300 @enderror"
                        >
                            <img
                                id="feature-preview"
                                src="{{ $currentImage }}"
                                alt="{{ $post->title }}"
                                class="aspect-[16/10] w-full object-cover {{ $currentImage ? '' : 'hidden' }}"
                            >

                            <label
                                for="feature-image"
                                id="feature-empty"
                                class="flex aspect-[16/10] cursor-pointer flex-col items-center justify-center p-4 text-center {{ $currentImage ? 'hidden' : '' }}"
                            >
                                <span class="mb-2 grid h-11 w-11 place-items-center rounded-full bg-white text-copper-500 shadow-sm">
                                    <i class="fa-solid fa-cloud-arrow-up"></i>
                                </span>
                                <span class="text-sm font-semibold text-slate-800">Drop image here</span>
                                <span class="mt-0.5 text-[11px] text-slate-500">
                                    or <span class="font-semibold text-copper-500 underline">browse</span>
                                </span>
                            </label>

                            <span
                                id="feature-new-badge"
                                class="absolute left-2 top-2 hidden rounded-full bg-slate-950/75 px-2 py-1 text-[9px] font-bold uppercase tracking-wide text-white backdrop-blur"
                            >
                                New
                            </span>
                        </div>

                        <input
                            id="feature-image"
                            type="file"
                            name="image"
                            accept="image/jpeg,image/png,image/webp"
                            class="hidden"
                        >

                        <div class="mt-3 flex gap-2">
                            <label
                                for="feature-image"
                                class="inline-flex flex-1 cursor-pointer items-center justify-center gap-2 rounded-lg bg-forest px-3 py-2.5 text-xs font-semibold text-white transition hover:bg-copper-500"
                            >
                                <i class="fa-solid fa-arrow-up-from-bracket"></i>
                                <span id="feature-choose-text">{{ $currentImage ? 'Replace image' : 'Choose image' }}</span>
                            </label>

                            <button
                                id="feature-reset"
                                type="button"
                                class="hidden items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-50"
                                title="Undo image selection"
                            >
                                <i class="fa-solid fa-rotate-left"></i>
                                Undo
                            </button>
                        </div>

                        <p id="feature-meta" class="mt-2 text-[11px] text-slate-400">
                            JPG, PNG or WebP · max 4 MB · 1200×750 recommended
                        </p>

                        <p id="feature-error" class="mt-2 hidden rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs font-medium text-red-700"></p>

                        @error('image')
                            <span class="error">{{ $message }}</span>
                        @enderror

                    </div>
                </section>


                {{-- =============================================
                    ORGANIZATION
                ============================================== --}}
                <section class="admin-card overflow-hidden">

                    <div class="flex items-center justify-between gap-3 border-b border-slate-100 px-5 py-4">
                        <div>
                            <h3 class="font-semibold text-slate-900">Organization</h3>
                            <p class="mt-0.5 text-xs text-slate-500">Group the article on the blog.</p>
                        </div>
                        <span class="grid h-9 w-9 place-items-center rounded-xl bg-orange-50 text-copper-500">
                            <i class="fa-solid fa-layer-group"></i>
                        </span>
                    </div>

                    <div class="p-5">
                        <label class="block">
                            <span class="admin-label">Category</span>

                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 grid w-10 place-items-center text-slate-400">
                                    <i class="fa-solid fa-folder text-xs"></i>
                                </span>

                                <input
                                    class="admin-input !pl-10 @error('category') !border-red-400 @enderror"
                                    name="category"
                                    value="{{ old('category', $post->category) }}"
                                    maxlength="100"
                                    placeholder="e.g. Craft stories, Style guide"
                                >
                            </div>

                            @error('category')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </label>
                    </div>
                </section>


                {{-- =============================================
                    DANGER ZONE
                ============================================== --}}
                @if($canDelete)
                    <section class="rounded-xl border border-red-200 bg-red-50 p-5">
                        <div class="flex items-start gap-3">
                            <span class="grid h-9 w-9 shrink-0 place-items-center rounded-lg bg-white text-red-600 shadow-sm">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                            </span>

                            <div>
                                <h3 class="text-sm font-semibold text-red-900">Danger zone</h3>
                                <p class="mt-1 text-xs leading-5 text-red-700">
                                    Deleting removes this article from the blog.
                                </p>

                                <button
                                    type="submit"
                                    form="delete-post-form"
                                    onclick="return confirm('Delete this article?')"
                                    class="mt-3 inline-flex items-center gap-2 rounded-lg bg-red-700 px-3 py-2 text-xs font-semibold text-white transition hover:bg-red-800"
                                >
                                    <i class="fa-solid fa-trash-can"></i>
                                    Delete article
                                </button>
                            </div>
                        </div>
                    </section>
                @endif

            </aside>
        </div>


        {{-- =====================================================
            BOTTOM ACTION BAR
        ====================================================== --}}
        <div class="mt-6 flex flex-col-reverse gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm sm:flex-row sm:items-center sm:justify-between">

            <p class="text-xs text-slate-500">
                <i class="fa-solid fa-circle-info mr-1 text-slate-400"></i>
                Required fields are marked with an asterisk (*).
            </p>

            <div class="flex gap-2">
                <a href="{{ route('admin.posts.index') }}" class="admin-btn admin-btn-light">Cancel</a>

                <button type="submit" class="js-submit-btn admin-btn">
                    <i class="fa-solid {{ $post->exists ? 'fa-floppy-disk' : 'fa-paper-plane' }}"></i>
                    <span>{{ $post->exists ? 'Save changes' : 'Save article' }}</span>
                </button>
            </div>

        </div>

    </form>


    {{-- =========================================================
        DELETE FORM (outside main form — no nested forms)
    ========================================================== --}}
    @if($canDelete)
        <form
            id="delete-post-form"
            method="POST"
            action="{{ route('admin.posts.destroy', $post) }}"
            class="hidden"
        >
            @csrf
            @method('DELETE')
        </form>
    @endif

</div>

@endsection



{{-- =============================================================
    SUMMERNOTE + ARTICLE FORM JAVASCRIPT
============================================================== --}}
@push('scripts')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css">

<style>
    /* ---------- Editor frame ---------- */
    #editor-wrapper .note-editor.note-frame {
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        overflow: hidden;
        box-shadow: none;
        background: #fff;
    }
    #editor-wrapper .note-editor.note-frame:focus-within {
        border-color: #cbd5e1;
        box-shadow: 0 0 0 3px rgba(184, 115, 51, 0.12);
    }
    #editor-wrapper.is-invalid .note-editor.note-frame {
        border-color: #f87171;
    }

    /* ---------- Toolbar ---------- */
    #editor-wrapper .note-toolbar {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 0.5rem 0.625rem;
        position: sticky;
        top: 0;
        z-index: 5;
    }
    #editor-wrapper .note-btn {
        background: #fff;
        border-color: #e2e8f0;
        color: #334155;
        font-size: 12px;
        padding: 5px 9px;
    }
    #editor-wrapper .note-btn:hover,
    #editor-wrapper .note-btn.active {
        background: #f1f5f9;
        color: #0f172a;
    }

    /* ---------- Writing area ---------- */
    #editor-wrapper .note-editable {
        padding: 1.5rem 1.75rem !important;
        font-size: 15px;
        line-height: 1.8;
        color: #1e293b;
    }
    #editor-wrapper .note-placeholder {
        padding: 1.5rem 1.75rem !important;
        color: #94a3b8;
    }
    #editor-wrapper .note-editable p          { margin: 0 0 1rem; }
    #editor-wrapper .note-editable h2         { font-size: 1.5rem;  font-weight: 600; margin: 1.75rem 0 0.75rem; color: #0f172a; }
    #editor-wrapper .note-editable h3         { font-size: 1.25rem; font-weight: 600; margin: 1.5rem 0 0.5rem;   color: #0f172a; }
    #editor-wrapper .note-editable h4         { font-size: 1.05rem; font-weight: 600; margin: 1.25rem 0 0.5rem;  color: #0f172a; }
    #editor-wrapper .note-editable ul         { list-style: disc;    padding-left: 1.5rem; margin: 0 0 1rem; }
    #editor-wrapper .note-editable ol         { list-style: decimal; padding-left: 1.5rem; margin: 0 0 1rem; }
    #editor-wrapper .note-editable a          { color: #b87333; text-decoration: underline; }
    #editor-wrapper .note-editable blockquote { border-left: 3px solid #b87333; padding: 0.25rem 0 0.25rem 1rem; margin: 1.25rem 0; font-style: italic; color: #475569; }
    #editor-wrapper .note-editable pre        { background: #0f172a; color: #e2e8f0; padding: 1rem; border-radius: 0.5rem; font-size: 13px; overflow-x: auto; }
    #editor-wrapper .note-editable img        { max-width: 100%; height: auto; border-radius: 0.5rem; }
    #editor-wrapper .note-editable table      { width: 100%; border-collapse: collapse; margin: 1rem 0; }
    #editor-wrapper .note-editable td,
    #editor-wrapper .note-editable th         { border: 1px solid #e2e8f0; padding: 0.5rem 0.75rem; }

    /* ---------- Code view & status bar ---------- */
    #editor-wrapper .note-codable {
        background: #0f172a;
        color: #e2e8f0;
        font-size: 13px;
        padding: 1.25rem !important;
    }
    #editor-wrapper .note-statusbar {
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
    }

    /* ---------- Fullscreen ---------- */
    .note-editor.fullscreen {
        z-index: 9999 !important;
        background: #fff;
    }
</style>

{{-- Load jQuery only if the admin layout has not already loaded it --}}
<script>
    window.jQuery || document.write('<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"><\/script>');
</script>

<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>

<script>
jQuery(function ($) {

    /* =========================================================
        CONFIG
    ========================================================== */

    const postExists    = @json($post->exists);
    const uploadUrl     = @json(Route::has('admin.posts.editor-image') ? route('admin.posts.editor-image') : null);
    const csrfToken     = @json(csrf_token());
    const blogBaseUrl   = @json(url('blog'));
    const originalImage = @json($post->image ? asset($post->image) : null);

    const MAX_FILE_SIZE = 4 * 1024 * 1024;
    const ALLOWED_TYPES = ['image/jpeg', 'image/png', 'image/webp'];

    const form = document.getElementById('post-form');


    /* =========================================================
        HELPERS
    ========================================================== */

    function formatFileSize(bytes) {
        if (bytes < 1024) return `${bytes} B`;
        if (bytes < 1024 * 1024) return `${(bytes / 1024).toFixed(1)} KB`;
        return `${(bytes / (1024 * 1024)).toFixed(1)} MB`;
    }

    function validateImage(file) {
        if (!ALLOWED_TYPES.includes(file.type)) {
            return `"${file.name}" must be a JPG, PNG or WebP image.`;
        }
        if (file.size > MAX_FILE_SIZE) {
            return `"${file.name}" is larger than the 4 MB limit.`;
        }
        return null;
    }


    /* =========================================================
        AUTO SLUG
    ========================================================== */

    const titleInput   = document.getElementById('post-title');
    const slugInput    = document.getElementById('post-slug');
    let slugManuallySet = postExists || Boolean(slugInput.value);

    function makeSlug(value) {
        return value.toString()
            .normalize('NFKD')
            .replace(/[\u0300-\u036f]/g, '')
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');
    }

    slugInput.addEventListener('input', function () {
        slugManuallySet = this.value.trim() !== '';
        updateSeoPreview();
    });

    titleInput.addEventListener('input', function () {
        if (!slugManuallySet) {
            slugInput.value = makeSlug(this.value);
        }
        updateSeoPreview();
    });


    /* =========================================================
        CHARACTER COUNTERS
    ========================================================== */

    function bindCounter(inputId, counterId, max) {
        const input   = document.getElementById(inputId);
        const counter = document.getElementById(counterId);
        if (!input || !counter) return;

        function update() {
            const length = input.value.length;
            counter.textContent = `${length} / ${max}`;
            counter.classList.toggle('text-amber-600', length > max * 0.9);
            counter.classList.toggle('text-slate-400', length <= max * 0.9);
        }

        input.addEventListener('input', update);
        update();
    }

    bindCounter('post-excerpt', 'excerpt-count', 300);
    bindCounter('meta-title', 'meta-title-count', 70);
    bindCounter('meta-description', 'meta-description-count', 170);


    /* =========================================================
        SEO SEARCH PREVIEW
    ========================================================== */

    const metaTitleInput = document.getElementById('meta-title');
    const metaDescInput  = document.getElementById('meta-description');
    const excerptInput   = document.getElementById('post-excerpt');

    function updateSeoPreview() {
        const title = metaTitleInput.value.trim() || titleInput.value.trim() || 'Article title';
        const desc  = metaDescInput.value.trim()  || excerptInput.value.trim()
                   || 'Add a meta description to control how this article appears in search results.';
        const slug  = slugInput.value.trim() || 'article-slug';

        document.getElementById('seo-preview-title').textContent       = title;
        document.getElementById('seo-preview-description').textContent = desc;
        document.getElementById('seo-preview-url').textContent         = `${blogBaseUrl}/${slug}`;
    }

    [metaTitleInput, metaDescInput, excerptInput].forEach(el => el.addEventListener('input', updateSeoPreview));
    updateSeoPreview();


    /* =========================================================
        PUBLISH STATUS BADGE
    ========================================================== */

    const publishToggle = document.getElementById('is-published');
    const publishedAt   = document.getElementById('published-at');
    const statusBadge   = document.getElementById('publish-status');

    const badgeBase = 'rounded-full px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider';

    function updatePublishStatus() {
        if (!publishToggle.checked) {
            statusBadge.className   = `${badgeBase} bg-slate-100 text-slate-600`;
            statusBadge.textContent = 'Draft';
            return;
        }

        const date = publishedAt.value ? new Date(publishedAt.value) : null;

        if (date && date > new Date()) {
            statusBadge.className   = `${badgeBase} bg-amber-50 text-amber-700`;
            statusBadge.textContent = 'Scheduled';
        } else {
            statusBadge.className   = `${badgeBase} bg-emerald-50 text-emerald-700`;
            statusBadge.textContent = 'Published';
        }
    }

    publishToggle.addEventListener('change', updatePublishStatus);
    publishedAt.addEventListener('input', updatePublishStatus);
    updatePublishStatus();


    /* =========================================================
        SUMMERNOTE EDITOR
    ========================================================== */

    const $editor       = $('#article-content');
    const editorWrapper = document.getElementById('editor-wrapper');
    const editorNotice  = document.getElementById('editor-notice');

    function showEditorNotice(message, type = 'error') {
        editorNotice.textContent = message;
        editorNotice.className = 'mt-2 text-xs ' + (type === 'error' ? 'text-red-600' : 'text-slate-500');
    }

    function hideEditorNotice() {
        editorNotice.className = 'mt-2 hidden text-xs';
    }

    function updateStats(html) {
        const text  = (new DOMParser().parseFromString(html || '', 'text/html').body.textContent || '').trim();
        const words = text ? text.split(/\s+/).length : 0;

        document.getElementById('word-count').textContent = words.toLocaleString();
        document.getElementById('read-time').textContent  = Math.max(1, Math.ceil(words / 200));
    }

    async function uploadEditorImage(file) {
        const error = validateImage(file);
        if (error) {
            showEditorNotice(error);
            return;
        }

        $editor.summernote('editor.saveRange');
        showEditorNotice(`Uploading ${file.name}…`, 'info');

        const formData = new FormData();
        formData.append('image', file);

        try {
            const response = await fetch(uploadUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: formData,
            });

            const data = await response.json().catch(() => ({}));

            if (!response.ok || !data.url) {
                throw new Error(data.message || 'Image upload failed.');
            }

            $editor.summernote('editor.restoreRange');
            $editor.summernote('insertImage', data.url, function ($image) {
                $image.attr('alt', file.name.replace(/\.[^.]+$/, ''));
                $image.css('width', '100%');
            });

            hideEditorNotice();
        } catch (e) {
            showEditorNotice(e.message);
        }
    }

    const insertButtons = uploadUrl
        ? ['link', 'picture', 'video', 'table', 'hr']
        : ['link', 'video', 'table', 'hr'];

    $editor.summernote({
        placeholder: 'Start writing your article…',
        height: 480,
        minHeight: 320,
        tabsize: 2,
        dialogsInBody: true,
        disableDragAndDrop: !uploadUrl,
        styleTags: [
            'p',
            { title: 'Heading 2', tag: 'h2', value: 'h2' },
            { title: 'Heading 3', tag: 'h3', value: 'h3' },
            { title: 'Heading 4', tag: 'h4', value: 'h4' },
            'blockquote',
            'pre',
        ],
        toolbar: [
            ['style',   ['style']],
            ['font',    ['bold', 'italic', 'underline', 'strikethrough', 'clear']],
            ['color',   ['forecolor']],
            ['para',    ['ul', 'ol', 'paragraph']],
            ['insert',  insertButtons],
            ['history', ['undo', 'redo']],
            ['view',    ['codeview', 'fullscreen']],
        ],
        callbacks: {
            onInit: function () {
                updateStats($editor.summernote('code'));
            },
            onChange: function (contents) {
                updateStats(contents);
                editorWrapper.classList.remove('is-invalid');
            },
            onImageUpload: function (files) {
                if (!uploadUrl) {
                    showEditorNotice('Image upload is not configured. Use an image URL instead.');
                    return;
                }
                Array.from(files).forEach(uploadEditorImage);
            },
        },
    });


    /* =========================================================
        FEATURE IMAGE
    ========================================================== */

    const featureInput   = document.getElementById('feature-image');
    const featurePreview = document.getElementById('feature-preview');
    const featureEmpty   = document.getElementById('feature-empty');
    const featureBadge   = document.getElementById('feature-new-badge');
    const featureReset   = document.getElementById('feature-reset');
    const featureMeta    = document.getElementById('feature-meta');
    const featureError   = document.getElementById('feature-error');
    const featureText    = document.getElementById('feature-choose-text');
    const featureZone    = document.getElementById('feature-dropzone');

    const defaultMetaText = featureMeta.textContent;
    let featureObjectUrl  = null;

    function showFeatureError(message) {
        featureError.textContent = message;
        featureError.classList.remove('hidden');
        featureZone.classList.add('!border-red-300');
    }

    function hideFeatureError() {
        featureError.classList.add('hidden');
        featureZone.classList.remove('!border-red-300');
    }

    function resetFeatureImage() {
        featureInput.value = '';

        if (featureObjectUrl) {
            URL.revokeObjectURL(featureObjectUrl);
            featureObjectUrl = null;
        }

        if (originalImage) {
            featurePreview.src = originalImage;
            featurePreview.classList.remove('hidden');
            featureEmpty.classList.add('hidden');
            featureText.textContent = 'Replace image';
        } else {
            featurePreview.removeAttribute('src');
            featurePreview.classList.add('hidden');
            featureEmpty.classList.remove('hidden');
            featureText.textContent = 'Choose image';
        }

        featureBadge.classList.add('hidden');
        featureReset.classList.add('hidden');
        featureReset.classList.remove('inline-flex');
        featureMeta.textContent = defaultMetaText;
    }

    function setFeatureImage(file) {
        hideFeatureError();

        if (!file) {
            resetFeatureImage();
            return;
        }

        const error = validateImage(file);
        if (error) {
            resetFeatureImage();
            showFeatureError(error);
            return;
        }

        // Put the file into the real input (needed for drag & drop)
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        featureInput.files = dataTransfer.files;

        if (featureObjectUrl) URL.revokeObjectURL(featureObjectUrl);
        featureObjectUrl = URL.createObjectURL(file);

        featurePreview.src = featureObjectUrl;
        featurePreview.classList.remove('hidden');
        featureEmpty.classList.add('hidden');
        featureBadge.classList.remove('hidden');
        featureReset.classList.remove('hidden');
        featureReset.classList.add('inline-flex');
        featureText.textContent = 'Change image';
        featureMeta.textContent = `${file.name} · ${formatFileSize(file.size)}`;
    }

    featureInput.addEventListener('change', function () {
        setFeatureImage(this.files[0]);
    });

    featureReset.addEventListener('click', function () {
        resetFeatureImage();
        hideFeatureError();
    });

    ['dragenter', 'dragover'].forEach(function (eventName) {
        featureZone.addEventListener(eventName, function (event) {
            event.preventDefault();
            featureZone.classList.add('border-copper-400', 'bg-orange-50');
        });
    });

    ['dragleave', 'drop'].forEach(function (eventName) {
        featureZone.addEventListener(eventName, function (event) {
            event.preventDefault();
            featureZone.classList.remove('border-copper-400', 'bg-orange-50');
        });
    });

    featureZone.addEventListener('drop', function (event) {
        const file = event.dataTransfer.files[0];
        if (file) setFeatureImage(file);
    });


    /* =========================================================
        SUBMIT: SYNC EDITOR + CLIENT VALIDATION
    ========================================================== */

    form.addEventListener('submit', function (event) {

        // Make sure code-view edits are applied, then sync to the textarea
        if ($editor.summernote('codeview.isActivated')) {
            $editor.summernote('codeview.deactivate');
        }
        $editor.val($editor.summernote('code'));

        let firstInvalid = null;

        // Required text fields
        [titleInput, slugInput].forEach(function (input) {
            const empty = input.value.trim() === '';
            input.classList.toggle('!border-red-400', empty);
            if (empty && !firstInvalid) firstInvalid = input;
        });

        // Article content
        if ($editor.summernote('isEmpty')) {
            editorWrapper.classList.add('is-invalid');
            showEditorNotice('Article content is required.');
            if (!firstInvalid) firstInvalid = editorWrapper;
        }

        // Feature image required when creating
        if (!postExists && featureInput.files.length === 0) {
            showFeatureError('Please add a feature image.');
            if (!firstInvalid) firstInvalid = featureZone;
        }

        if (firstInvalid) {
            event.preventDefault();
            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            if (firstInvalid.focus && firstInvalid.tagName === 'INPUT') firstInvalid.focus({ preventScroll: true });
            return;
        }

        // Loading state
        document.querySelectorAll('.js-submit-btn').forEach(function (button) {
            button.disabled = true;
            button.classList.add('opacity-70', 'cursor-wait');
            button.querySelector('i').className = 'fa-solid fa-spinner fa-spin';
            button.querySelector('span').textContent = 'Saving…';
        });
    });


    /* =========================================================
        CLEANUP
    ========================================================== */

    window.addEventListener('beforeunload', function () {
        if (featureObjectUrl) URL.revokeObjectURL(featureObjectUrl);
    });

});
</script>

@endpush