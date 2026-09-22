<?php
namespace Tests\Feature;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class AdminSecurityTest extends TestCase {use RefreshDatabase;public function test_customer_cannot_access_admin():void{$user=User::create(['name'=>'Buyer','email'=>'buyer@example.com','password'=>bcrypt('Secret123'),'role'=>'customer','email_verified_at'=>now()]);$this->actingAs($user)->get(route('admin.dashboard'))->assertForbidden();}public function test_admin_can_access_dashboard():void{$admin=User::create(['name'=>'Admin','email'=>'admin@example.com','password'=>bcrypt('Secret123'),'role'=>'admin','email_verified_at'=>now()]);$this->actingAs($admin)->get(route('admin.dashboard'))->assertOk();}}
