<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Review extends Model { protected $guarded=[]; protected $casts=['is_verified_purchase'=>'boolean']; public function product(){return $this->belongsTo(Product::class);} public function user(){return $this->belongsTo(User::class);} public function order(){return $this->belongsTo(Order::class);} }
