@extends('admin.layouts.app')

@section('title','Journal')

@section('page_title','Journal')


@section('content')


{{-- Header --}}
<div class="flex justify-between items-center mb-6">

    <div>
        <h2 class="text-xl font-semibold text-gray-800">
            Journal Articles
        </h2>

        <p class="text-sm text-gray-500 mt-1">
            Manage your journal posts and stories
        </p>
    </div>


    <a href="{{ route('admin.posts.create') }}"
       class="admin-btn">

        <i class="fa-solid fa-plus mr-2"></i>

        New Article

    </a>


</div>




{{-- Filter Card --}}
<div class="admin-card p-5 mb-6">


<form method="GET"
      action="{{ route('admin.posts.index') }}"
      class="grid md:grid-cols-4 gap-4">


{{-- Search --}}
<div>

<label class="admin-label">
Search
</label>


<input

type="text"

name="search"

value="{{ request('search') }}"

placeholder="Search title..."

class="admin-input">

</div>




{{-- Category --}}
<div>

<label class="admin-label">
Category
</label>


<select name="category"
        class="admin-input">


<option value="">
All Categories
</option>


@foreach($categories as $category)

<option

value="{{ $category }}"

@selected(request('category')==$category)

>

{{ $category }}

</option>


@endforeach


</select>


</div>





{{-- Status --}}
<div>

<label class="admin-label">
Status
</label>


<select name="status"
        class="admin-input">


<option value="">
All Status
</option>


<option value="1"
@selected(request('status')==='1')>

Published

</option>


<option value="0"
@selected(request('status')==='0')>

Draft

</option>


</select>


</div>





{{-- Date --}}
<div>

<label class="admin-label">
Published Date
</label>


<input

type="date"

name="date"

value="{{ request('date') }}"

class="admin-input">


</div>




<div class="md:col-span-4 flex gap-3">


<button class="admin-btn">

<i class="fa-solid fa-filter mr-2"></i>

Filter

</button>



<a href="{{ route('admin.posts.index') }}"
class="admin-btn admin-btn-light">

Reset

</a>



</div>



</form>


</div>






{{-- Table --}}

<div class="admin-card overflow-hidden">


<div class="overflow-x-auto">


<table class="admin-table">


<thead>

<tr>

<th>
Article
</th>


<th>
Category
</th>


<th>
Status
</th>


<th>
Published
</th>


<th class="text-right">
Action
</th>


</tr>


</thead>



<tbody>



@forelse($posts as $post)



<tr>


<td>


<div class="flex items-center gap-4">


@if($post->image)

<img

src="{{ asset($post->image) }}"

class="w-20 h-12 rounded-lg object-cover border"

>


@else

<div class="w-20 h-12 rounded-lg bg-gray-100 flex items-center justify-center">

<i class="fa-solid fa-image text-gray-400"></i>

</div>


@endif




<div>


<a

href="{{ route('admin.posts.edit',$post) }}"

class="font-semibold text-gray-800 hover:text-orange-600">

{{ $post->title }}

</a>


<p class="text-xs text-gray-500 mt-1">

{{ Str::limit($post->excerpt,60) }}

</p>


</div>



</div>


</td>





<td>

@if($post->category)

<span class="admin-badge">

{{ $post->category }}

</span>

@else

—

@endif

</td>





<td>


@if($post->is_published)

<span class="admin-badge">

Published

</span>


@else

<span class="admin-badge">

Draft

</span>


@endif


</td>





<td>


{{ $post->published_at?->format('d M Y') ?? '—' }}


</td>





<td>


<div class="flex justify-end gap-2">


<a

href="{{ route('admin.posts.edit',$post) }}"

class="admin-btn admin-btn-light !p-2">


<i class="fa-solid fa-pen"></i>


</a>





<button
type="button"

class="
js-delete-post

grid
h-9
w-9
place-items-center
rounded-lg
border
border-red-200
bg-red-50
text-red-600
transition

hover:border-red-300
hover:bg-red-100
"

data-delete-url="{{ route('admin.posts.destroy',$post) }}"

data-post-name="{{ $post->title }}"

title="Delete article"
>

<i class="fa-solid fa-trash-can text-xs"></i>

</button>



</div>


</td>




</tr>



@empty


<tr>

<td colspan="5" class="!py-16 text-center">


<div class="mx-auto max-w-sm">


<span
class="
mx-auto
grid
h-14
w-14
place-items-center
rounded-full
bg-slate-100
text-slate-400
"
>

<i class="fa-solid fa-newspaper text-xl"></i>

</span>



<h3
class="
mt-4
text-base
font-semibold
text-slate-800
"
>

No journal articles found

</h3>



<p
class="
mt-1
text-xs
text-slate-500
"
>

Start by creating your first journal article.

</p>



<a
href="{{ route('admin.posts.create') }}"
class="admin-btn mt-4"
>

<i class="fa-solid fa-plus"></i>

New Article

</a>


</div>


</td>

</tr>


@endforelse



</tbody>



</table>


</div>



</div>




{{-- =============================================================
    PREMIUM ADMIN PAGINATION
============================================================== --}}
@if($posts->hasPages())

<div
    class="
        mt-5
        flex
        flex-col
        gap-4
        rounded-xl
        border
        border-slate-200
        bg-white
        px-5
        py-4
        shadow-sm

        sm:flex-row
        sm:items-center
        sm:justify-between
    "
>


    {{-- RESULT INFO --}}
    <div class="text-xs text-slate-500">

        Showing

        <span class="font-semibold text-slate-800">
            {{ $posts->firstItem() }}
        </span>

        to

        <span class="font-semibold text-slate-800">
            {{ $posts->lastItem() }}
        </span>

        of

        <span class="font-semibold text-slate-800">
            {{ $posts->total() }}
        </span>

        articles

    </div>



    {{-- PAGINATION --}}
    <nav
        class="
            flex
            flex-wrap
            items-center
            gap-1
        "
    >


        {{-- Previous --}}
        @if($posts->onFirstPage())


            <span
                class="
                    inline-flex
                    h-9
                    min-w-[36px]
                    cursor-not-allowed
                    items-center
                    justify-center
                    rounded-lg
                    border
                    border-slate-200
                    bg-slate-50
                    px-3
                    text-xs
                    text-slate-300
                "
            >

                <i class="fa-solid fa-chevron-left"></i>

            </span>


        @else


            <a
                href="{{ $posts->previousPageUrl() }}"
                class="
                    inline-flex
                    h-9
                    min-w-[36px]
                    items-center
                    justify-center
                    rounded-lg
                    border
                    border-slate-200
                    bg-white
                    px-3
                    text-xs
                    font-semibold
                    text-slate-600

                    hover:border-copper-300
                    hover:bg-orange-50
                    hover:text-copper-600
                "
            >

                <i class="fa-solid fa-chevron-left"></i>

            </a>


        @endif




        {{-- Page Numbers --}}
        @foreach(
            $posts->getUrlRange(
                max(1,$posts->currentPage()-2),
                min(
                    $posts->lastPage(),
                    $posts->currentPage()+2
                )
            )
            as $page=>$url
        )


            @if($page == $posts->currentPage())


                <span
                    class="
                        inline-flex
                        h-9
                        min-w-[36px]
                        items-center
                        justify-center
                        rounded-lg
                        bg-forest
                        px-3
                        text-xs
                        font-semibold
                        text-white
                    "
                >

                    {{ $page }}

                </span>


            @else


                <a
                    href="{{ $url }}"
                    class="
                        inline-flex
                        h-9
                        min-w-[36px]
                        items-center
                        justify-center
                        rounded-lg
                        border
                        border-slate-200
                        bg-white
                        px-3
                        text-xs
                        font-semibold
                        text-slate-600

                        hover:border-copper-300
                        hover:bg-orange-50
                        hover:text-copper-600
                    "
                >

                    {{ $page }}

                </a>


            @endif


        @endforeach





        {{-- Next --}}
        @if($posts->hasMorePages())


            <a
                href="{{ $posts->nextPageUrl() }}"
                class="
                    inline-flex
                    h-9
                    min-w-[36px]
                    items-center
                    justify-center
                    rounded-lg
                    border
                    border-slate-200
                    bg-white
                    px-3
                    text-xs
                    font-semibold
                    text-slate-600

                    hover:border-copper-300
                    hover:bg-orange-50
                    hover:text-copper-600
                "
            >

                <i class="fa-solid fa-chevron-right"></i>

            </a>


        @else


            <span
                class="
                    inline-flex
                    h-9
                    min-w-[36px]
                    cursor-not-allowed
                    items-center
                    justify-center
                    rounded-lg
                    border
                    border-slate-200
                    bg-slate-50
                    px-3
                    text-xs
                    text-slate-300
                "
            >

                <i class="fa-solid fa-chevron-right"></i>

            </span>


        @endif


    </nav>


</div>

@endif






{{-- =============================================================
    DELETE CONFIRMATION MODAL
============================================================== --}}

<div
    id="delete-post-modal"

    class="
        fixed
        inset-0
        z-[100]
        hidden
        items-center
        justify-center
        p-4
    "

    role="dialog"
    aria-modal="true"
>


    {{-- BACKDROP --}}
    <div
        class="
            js-delete-backdrop
            absolute
            inset-0
            bg-slate-950/50
            backdrop-blur-[2px]
        "
    ></div>



    {{-- MODAL --}}
    <div
        class="
            relative
            z-10
            w-full
            max-w-md
            overflow-hidden
            rounded-2xl
            border
            border-slate-200
            bg-white
            shadow-2xl
        "
    >


        <div class="p-6">


            <div class="flex items-start gap-4">


                <span
                    class="
                        grid
                        h-12
                        w-12
                        shrink-0
                        place-items-center
                        rounded-full
                        bg-red-50
                        text-red-600
                    "
                >

                    <i class="fa-solid fa-trash-can"></i>

                </span>



                <div class="flex-1">


                    <h2
                        class="
                            text-lg
                            font-semibold
                            text-slate-900
                        "
                    >

                        Delete article?

                    </h2>



                    <p
                        class="
                            mt-2
                            text-sm
                            leading-6
                            text-slate-500
                        "
                    >

                        You are about to delete

                        <strong
                            id="delete-post-name"
                            class="
                                font-semibold
                                text-slate-800
                            "
                        ></strong>


                        from journal.

                        This action cannot be undone.

                    </p>


                </div>



                <button
                    type="button"

                    class="
                        js-close-delete-modal
                        grid
                        h-8
                        w-8
                        place-items-center
                        rounded-lg
                        text-slate-400

                        hover:bg-slate-100
                        hover:text-slate-600
                    "
                >

                    <i class="fa-solid fa-xmark"></i>

                </button>


            </div>


        </div>





        {{-- ACTION FOOTER --}}
        <div
            class="
                flex
                flex-col-reverse
                gap-2
                border-t
                border-slate-100
                bg-slate-50
                px-6
                py-4

                sm:flex-row
                sm:justify-end
            "
        >


            <button
                type="button"

                class="
                    js-close-delete-modal
                    admin-btn
                    admin-btn-light
                "
            >

                Cancel

            </button>





            <form
                id="delete-post-form"

                method="POST"
            >

                @csrf

                @method('DELETE')


                <button
                    type="submit"

                    class="
                        admin-btn
                        !bg-red-700
                        !text-white
                        hover:!bg-red-800
                    "
                >

                    <i class="fa-solid fa-trash-can"></i>

                    Yes, delete

                </button>


            </form>


        </div>



    </div>


</div>






@push('scripts')

<script>

document.addEventListener(
'DOMContentLoaded',
function(){


const modal =
document.getElementById(
'delete-post-modal'
);



const form =
document.getElementById(
'delete-post-form'
);



const postName =
document.getElementById(
'delete-post-name'
);



const deleteButtons =
document.querySelectorAll(
'.js-delete-post'
);



const closeButtons =
document.querySelectorAll(
'.js-close-delete-modal'
);



const backdrop =
document.querySelector(
'.js-delete-backdrop'
);



let lastButton = null;



/*
|--------------------------------------------------------------------------
| OPEN MODAL
|--------------------------------------------------------------------------
*/


deleteButtons.forEach(
button=>{


button.addEventListener(
'click',
()=>{


lastButton = button;



form.action =
button.dataset.deleteUrl;



postName.textContent =
button.dataset.postName;



modal.classList.remove(
'hidden'
);



modal.classList.add(
'flex'
);



document.body.classList.add(
'overflow-hidden'
);



}
);


});





/*
|--------------------------------------------------------------------------
| CLOSE MODAL
|--------------------------------------------------------------------------
*/


function closeModal(){


modal.classList.add(
'hidden'
);


modal.classList.remove(
'flex'
);



document.body.classList.remove(
'overflow-hidden'
);



form.action='';


postName.textContent='';



lastButton?.focus();


}





closeButtons.forEach(
button=>{


button.addEventListener(
'click',
closeModal
);


});





backdrop?.addEventListener(
'click',
closeModal
);






/*
|--------------------------------------------------------------------------
| ESC KEY
|--------------------------------------------------------------------------
*/


document.addEventListener(
'keydown',
event=>{


if(
event.key === 'Escape'
&&
!modal.classList.contains('hidden')
){

closeModal();

}


}
);



});

</script>


@endpush

@endsection