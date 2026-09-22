@props(['product'])
@php($cardImages=$product->relationLoaded('images')?$product->images:$product->images()->get())
@php($primary=$cardImages->firstWhere('is_primary',true)?:$cardImages->first())
<article class="product-card reveal group">
 <a href="{{ route('products.show',$product) }}" class="product-media block">
  @if($product->discount_percent)<span class="badge absolute z-10 left-2 top-2">-{{ $product->discount_percent }}%</span>@elseif($product->is_new)<span class="badge absolute z-10 left-2 top-2">New</span>@endif
  <img src="{{ asset($primary?->path?:'images/placeholder.webp') }}" alt="{{ $primary?->alt_text?:$product->name }}" loading="lazy" width="640" height="800">
  @if(($second=$cardImages->where('id','!=',$primary?->id)->first()))<img src="{{ asset($second->path) }}" alt="{{ $second->alt_text?:$product->name.' alternate view' }}" loading="lazy" width="640" height="800">@endif
 </a>
 <div class="pt-3"><div class="flex gap-2 justify-between"><a href="{{ route('products.show',$product) }}" class="product-name">{{ $product->name }}</a>@auth<form action="{{ route('wishlist.toggle',$product) }}" method="post">@csrf<button aria-label="Add {{ $product->name }} to wishlist"><i class="fa-regular fa-heart"></i></button></form>@endauth</div><p class="text-xs muted mt-1">{{ $product->material }}</p><p class="price mt-2"><strong class="{{ $product->compare_price?'price-sale':'' }}">৳{{ number_format($product->price) }}</strong>@if($product->compare_price)<span class="price-old">৳{{ number_format($product->compare_price) }}</span>@endif</p></div>
</article>
