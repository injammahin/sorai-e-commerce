<?php
namespace Tests\Unit;
use App\Models\Coupon;
use PHPUnit\Framework\TestCase;
class CouponTest extends TestCase {public function test_percent_coupon_respects_maximum_discount():void{$c=new Coupon(['type'=>'percent','value'=>20,'maximum_discount'=>500]);$this->assertEquals(500,$c->discountFor(5000));}}
