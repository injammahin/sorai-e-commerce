<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Collection extends Model { protected $fillable=['title','slug','tagline','description','image','is_active']; protected $casts=['is_active'=>'boolean']; public function products(){return $this->belongsToMany(Product::class)->withPivot('sort_order')->orderByPivot('sort_order');} public function getRouteKeyName(){return 'slug';} }
