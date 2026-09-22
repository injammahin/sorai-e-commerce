<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Category extends Model { protected $fillable=['parent_id','name','slug','tagline','heading','description','image','banner_image','meta_title','meta_description','sort_order','is_active','show_in_menu']; protected $casts=['is_active'=>'boolean','show_in_menu'=>'boolean']; public function parent(){return $this->belongsTo(self::class,'parent_id');} public function children(){return $this->hasMany(self::class,'parent_id');} public function products(){return $this->hasMany(Product::class);} public function scopeActive($q){return $q->where('is_active',true);} public function scopeOrdered($q){return $q->orderBy('sort_order')->orderBy('name');} public function getRouteKeyName(){return 'slug';} }
