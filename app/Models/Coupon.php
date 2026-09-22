<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Coupon extends Model { protected $guarded=[]; protected $casts=['starts_at'=>'datetime','expires_at'=>'datetime','is_active'=>'boolean','value'=>'decimal:2']; public function isValidFor($subtotal){return $this->is_active&&(!$this->starts_at||$this->starts_at->isPast())&&(!$this->expires_at||$this->expires_at->isFuture())&&(!$this->usage_limit||$this->used_count<$this->usage_limit)&&$subtotal>=$this->minimum_order;} public function discountFor($subtotal){$discount=$this->type==='percent'?$subtotal*($this->value/100):$this->value;if($this->maximum_discount)$discount=min($discount,$this->maximum_discount);return min($discount,$subtotal);} }
