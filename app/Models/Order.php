<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Order extends Model { protected $guarded=[]; protected $casts=['shipping_address'=>'array','billing_address'=>'array','subtotal'=>'decimal:2','discount'=>'decimal:2','shipping'=>'decimal:2','tax'=>'decimal:2','total'=>'decimal:2','paid_at'=>'datetime','delivered_at'=>'datetime','stock_restored_at'=>'datetime']; public function user(){return $this->belongsTo(User::class);} public function coupon(){return $this->belongsTo(Coupon::class);} public function items(){return $this->hasMany(OrderItem::class);} public function histories(){return $this->hasMany(OrderStatusHistory::class)->latest();} public function getRouteKeyName(){return 'order_number';} }
