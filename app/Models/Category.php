<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'tagline',
        'heading',
        'description',
        'image',
        'banner_image',
        'meta_title',
        'meta_description',
        'sort_order',
        'is_active',
        'show_in_menu',
    ];


    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    |
    | parent_id MUST be integer.
    |
    | This prevents:
    |
    | "51" !== 51
    |
    | from incorrectly producing a 404.
    |
    */

    protected $casts = [
        'parent_id' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
        'show_in_menu' => 'boolean',
    ];


    /*
    |--------------------------------------------------------------------------
    | PARENT
    |--------------------------------------------------------------------------
    */

    public function parent()
    {
        return $this->belongsTo(
            self::class,
            'parent_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CHILDREN
    |--------------------------------------------------------------------------
    */

    public function children()
    {
        return $this->hasMany(
            self::class,
            'parent_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PRODUCTS
    |--------------------------------------------------------------------------
    */

    public function products()
    {
        return $this->hasMany(
            Product::class
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ACTIVE
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where(
            'is_active',
            true
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ORDERED
    |--------------------------------------------------------------------------
    */

    public function scopeOrdered($query)
    {
        return $query
            ->orderBy('sort_order')
            ->orderBy('name');
    }


    /*
    |--------------------------------------------------------------------------
    | ROUTE MODEL BINDING
    |--------------------------------------------------------------------------
    */

    public function getRouteKeyName()
    {
        return 'slug';
    }
}