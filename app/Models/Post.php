<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Post extends Model { protected $guarded=[]; protected $casts=['is_published'=>'boolean','published_at'=>'datetime']; public function author(){return $this->belongsTo(User::class,'author_id');} public function scopePublished($q){return $q->where('is_published',true)->where('published_at','<=',now());} public function getRouteKeyName(){return 'slug';} }
