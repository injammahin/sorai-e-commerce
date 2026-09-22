<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ProductImage extends Model { protected $fillable=['product_id','path','alt_text','sort_order','is_primary']; protected $casts=['is_primary'=>'boolean']; public function product(){return $this->belongsTo(Product::class);} public function getUrlAttribute(){return str_starts_with($this->path,'http')?$this->path:asset($this->path);} }
