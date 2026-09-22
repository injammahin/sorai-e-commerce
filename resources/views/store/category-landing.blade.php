@extends('layouts.store')
@section('title',$category->meta_title?:$category->name.' | SARAI')
@section('description',$category->meta_description?:$category->description)
@section('og_image',asset($category->banner_image?:$category->image))
@section('content')
<section class="page-hero"><img src="{{ asset($category->banner_image?:$category->image) }}" alt="{{ $category->name }}"><div class="wrap page-hero-content"><p class="eyebrow !text-white/70">{{ $category->tagline }}</p><h1 class="display">{{ $category->name }}</h1></div></section>
<section class="section"><div class="wrap"><div class="grid lg:grid-cols-2 gap-12 mb-16"><h2 class="heading">{{ $category->heading }}</h2><p class="muted text-lg max-w-xl">{{ $category->description }}</p></div><div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">@foreach($children as $child)<a href="{{ route('products.index',[$category,$child]) }}" class="group"><div class="aspect-[4/5] overflow-hidden bg-linen"><img class="w-full h-full object-cover duration-700 group-hover:scale-105" src="{{ asset($child->image) }}" alt="{{ $child->name }}" loading="lazy"></div><h3 class="text-2xl mt-3">{{ $child->name }}</h3></a>@endforeach</div></div></section>
<section class="section bg-white"><div class="wrap"><div class="flex justify-between items-end mb-10"><div><p class="eyebrow">From the collection</p><h2 class="heading mt-2">Selected pieces</h2></div><a href="{{ route('products.index',$category) }}" class="text-xs uppercase tracking-widest2">Shop all</a></div><div class="product-grid">@foreach($products as $product)<x-product-card :product="$product"/>@endforeach</div></div></section>
@endsection
