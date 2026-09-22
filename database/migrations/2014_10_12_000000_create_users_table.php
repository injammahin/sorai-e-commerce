<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration { public function up(){ Schema::create('users',function(Blueprint $t){$t->id();$t->string('name');$t->string('email')->unique();$t->timestamp('email_verified_at')->nullable();$t->string('password')->nullable();$t->enum('role',['admin','customer'])->default('customer')->index();$t->boolean('is_active')->default(true);$t->string('phone',30)->nullable();$t->string('avatar')->nullable();$t->string('google_id')->nullable()->unique();$t->rememberToken();$t->timestamps();}); Schema::create('password_reset_tokens',function(Blueprint $t){$t->string('email')->primary();$t->string('token');$t->timestamp('created_at')->nullable();}); } public function down(){Schema::dropIfExists('password_reset_tokens');Schema::dropIfExists('users');} };
