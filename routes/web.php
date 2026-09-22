<?php
use App\Http\Controllers\AccountController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

Route::get('/',[StoreController::class,'home'])->name('home');
Route::get('/new-arrivals',[StoreController::class,'newArrivals'])->name('new-arrivals');
Route::get('/category/{category}/{subcategory?}',[StoreController::class,'products'])->name('products.index');
Route::get('/collection/{collection}',[StoreController::class,'collection'])->name('collections.show');
Route::get('/product/{product}',[StoreController::class,'product'])->name('products.show');
Route::get('/search',[StoreController::class,'search'])->name('search');
Route::get('/journal',[StoreController::class,'journal'])->name('journal');
Route::get('/journal/{post}',[StoreController::class,'post'])->name('journal.show');

Route::get('/cart',[CartController::class,'index'])->name('cart.index');
Route::post('/cart/{product}',[CartController::class,'store'])->name('cart.store');
Route::patch('/cart/{key}',[CartController::class,'update'])->name('cart.update');
Route::delete('/cart/{key}',[CartController::class,'destroy'])->name('cart.destroy');
Route::post('/coupon',[CartController::class,'coupon'])->middleware('throttle:10,1')->name('coupon.apply');
Route::delete('/coupon',[CartController::class,'removeCoupon'])->name('coupon.remove');
Route::get('/checkout',[CheckoutController::class,'index'])->name('checkout');
Route::post('/checkout',[CheckoutController::class,'store'])->middleware('throttle:10,1')->name('checkout.store');
Route::get('/order/{order}/thank-you',[CheckoutController::class,'thankYou'])->name('order.thank-you');
Route::match(['get','post'],'/payment/sslcommerz/success',[CheckoutController::class,'success'])->name('payment.success');
Route::match(['get','post'],'/payment/sslcommerz/fail',[CheckoutController::class,'fail'])->name('payment.fail');
Route::match(['get','post'],'/payment/sslcommerz/cancel',[CheckoutController::class,'cancel'])->name('payment.cancel');
Route::post('/payment/sslcommerz/ipn',[CheckoutController::class,'ipn'])->name('payment.ipn');

Route::middleware('guest')->group(function () {
    Route::get('/login', [
        AuthenticatedSessionController::class,
        'create',
    ])->name('login');

    Route::post('/login', [
        AuthenticatedSessionController::class,
        'store',
    ])->middleware('throttle:login');

    /*
    |--------------------------------------------------------------------------
    | Admin login page
    |--------------------------------------------------------------------------
    |
    | It uses the same secure authentication form. After successful login,
    | AuthenticatedSessionController redirects administrators to /admin.
    |
    */
    Route::get('/admin/login', [
        AuthenticatedSessionController::class,
        'create',
    ])->name('admin.login');

    Route::get('/register', [
        RegisteredUserController::class,
        'create',
    ])->name('register');

    Route::post('/register', [
        RegisteredUserController::class,
        'store',
    ])->middleware('throttle:6,1');

    Route::get('/forgot-password', [
        PasswordResetController::class,
        'request',
    ])->name('password.request');

    Route::post('/forgot-password', [
        PasswordResetController::class,
        'email',
    ])->name('password.email');

    Route::get('/reset-password/{token}', [
        PasswordResetController::class,
        'resetForm',
    ])->name('password.reset');

    Route::post('/reset-password', [
        PasswordResetController::class,
        'reset',
    ])->name('password.update');

    Route::get('/auth/google', [
        GoogleController::class,
        'redirect',
    ])->name('google.redirect');

    Route::get('/auth/google/callback', [
        GoogleController::class,
        'callback',
    ])->name('google.callback');
});
Route::middleware('auth')->group(function(){Route::post('/logout',[AuthenticatedSessionController::class,'destroy'])->name('logout');Route::get('/verify-email',[EmailVerificationController::class,'notice'])->name('verification.notice');Route::get('/verify-email/{id}/{hash}',[EmailVerificationController::class,'verify'])->middleware('signed')->name('verification.verify');Route::post('/email/verification-notification',[EmailVerificationController::class,'send'])->middleware('throttle:6,1')->name('verification.send');});
Route::middleware(['auth','verified'])->group(function(){Route::get('/account',[AccountController::class,'index'])->name('account.index');Route::patch('/account',[AccountController::class,'update'])->name('account.update');Route::put('/account/password',[AccountController::class,'password'])->name('account.password');Route::get('/wishlist',[WishlistController::class,'index'])->name('wishlist.index');Route::post('/wishlist/{product}',[WishlistController::class,'toggle'])->name('wishlist.toggle');Route::post('/product/{product}/review',[ReviewController::class,'store'])->name('reviews.store');});

Route::post('/contact',[ContactController::class,'store'])->middleware('throttle:5,1')->name('contact.store');Route::post('/subscribe',[ContactController::class,'subscribe'])->middleware('throttle:5,1')->name('subscribe');
Route::get('/sitemap.xml',[SeoController::class,'sitemap'])->name('sitemap');Route::get('/robots.txt',[SeoController::class,'robots']);

Route::prefix('admin')->name('admin.')->middleware(['auth','admin'])->group(function(){
 Route::get('/',App\Http\Controllers\Admin\DashboardController::class)->name('dashboard');
 Route::resource('products',App\Http\Controllers\Admin\ProductController::class)->except('show');Route::delete('products/{product}/images/{image}',[App\Http\Controllers\Admin\ProductController::class,'removeImage'])->name('products.images.destroy');
 Route::resource('categories',App\Http\Controllers\Admin\CategoryController::class)->except('show');
 Route::resource('collections',App\Http\Controllers\Admin\CollectionController::class)->except('show');Route::resource('posts',App\Http\Controllers\Admin\PostController::class)->except('show');
 Route::resource('orders',App\Http\Controllers\Admin\OrderController::class)->only(['index','show','update']);
 Route::get('customers',[App\Http\Controllers\Admin\CustomerController::class,'index'])->name('customers.index');Route::get('customers/{customer}',[App\Http\Controllers\Admin\CustomerController::class,'show'])->name('customers.show');Route::patch('customers/{customer}/toggle',[App\Http\Controllers\Admin\CustomerController::class,'toggle'])->name('customers.toggle');
 Route::resource('pages',App\Http\Controllers\Admin\PageController::class)->except('show');Route::resource('banners',App\Http\Controllers\Admin\BannerController::class)->except('show');Route::resource('coupons',App\Http\Controllers\Admin\CouponController::class)->except('show');
 Route::get('reviews',[App\Http\Controllers\Admin\ReviewController::class,'index'])->name('reviews.index');Route::patch('reviews/{review}',[App\Http\Controllers\Admin\ReviewController::class,'update'])->name('reviews.update');Route::delete('reviews/{review}',[App\Http\Controllers\Admin\ReviewController::class,'destroy'])->name('reviews.destroy');
 Route::resource('messages',App\Http\Controllers\Admin\MessageController::class)->only(['index','show','update','destroy']);Route::get('settings',[App\Http\Controllers\Admin\SettingController::class,'index'])->name('settings.index');Route::put('settings',[App\Http\Controllers\Admin\SettingController::class,'update'])->name('settings.update');
 Route::get('subscribers',[App\Http\Controllers\Admin\SubscriberController::class,'index'])->name('subscribers.index');Route::get('subscribers/export',[App\Http\Controllers\Admin\SubscriberController::class,'export'])->name('subscribers.export');
});

Route::get('/{category}',[StoreController::class,'category'])->name('categories.show');
Route::get('/pages/{page}',[StoreController::class,'page'])->name('pages.show');
