<!doctype html><html lang="en"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="csrf-token" content="{{ csrf_token() }}">
@php($seoTitle=trim($__env->yieldContent('title')) ?: ($siteSettings['default_meta_title']??'SARAI — Authentic Bangladeshi Craft'))
@php($seoDescription=trim($__env->yieldContent('description')) ?: ($siteSettings['default_meta_description']??'Curated Bangladeshi handloom, craft, fashion and lifestyle.'))
<title>{{ $seoTitle }}</title><meta name="description" content="{{ $seoDescription }}"><link rel="canonical" href="@yield('canonical',url()->current())">
<meta property="og:type" content="@yield('og_type','website')"><meta property="og:title" content="{{ $seoTitle }}"><meta property="og:description" content="{{ $seoDescription }}"><meta property="og:url" content="{{ url()->current() }}"><meta property="og:image" content="@yield('og_image',asset('images/logo/sarai-mark.webp'))"><meta name="twitter:card" content="summary_large_image">
<meta name="theme-color" content="#faf8f5"><link rel="icon" href="{{ asset('favicon/favicon-32.png') }}"><link rel="manifest" href="{{ asset('site.webmanifest') }}">
@if(!empty($siteSettings['google_site_verification']))<meta name="google-site-verification" content="{{ $siteSettings['google_site_verification'] }}">@endif
<script type="application/ld+json">{!! json_encode(['@context'=>'https://schema.org','@type'=>'Organization','name'=>$siteSettings['site_name']??'SARAI','url'=>url('/'),'logo'=>asset('images/logo/sarai-lockup.webp'),'email'=>$siteSettings['contact_email']??null,'telephone'=>$siteSettings['contact_phone']??null],JSON_UNESCAPED_SLASHES) !!}</script>
@stack('schema') @vite(['resources/css/app.css','resources/js/app.js']) @stack('head')</head><body>
<a href="#main" class="sr-only focus:not-sr-only">Skip to content</a><x-header/><main id="main">@yield('content')</main><x-footer/><x-flash/>@stack('scripts')</body></html>
