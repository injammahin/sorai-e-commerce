<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(){
  Schema::create('pages',function(Blueprint $t){$t->id();$t->string('title');$t->string('slug')->unique();$t->longText('content')->nullable();$t->string('hero_image')->nullable();$t->string('meta_title')->nullable();$t->text('meta_description')->nullable();$t->boolean('is_active')->default(true);$t->timestamps();});
  Schema::create('posts',function(Blueprint $t){$t->id();$t->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();$t->string('title');$t->string('slug')->unique();$t->string('category')->nullable();$t->text('excerpt')->nullable();$t->longText('content');$t->string('image')->nullable();$t->string('meta_title')->nullable();$t->text('meta_description')->nullable();$t->boolean('is_published')->default(false);$t->timestamp('published_at')->nullable();$t->timestamps();});
  Schema::create('banners',function(Blueprint $t){$t->id();$t->string('placement')->default('home_hero')->index();$t->string('eyebrow')->nullable();$t->string('title');$t->string('subtitle')->nullable();$t->text('description')->nullable();$t->string('image');$t->string('mobile_image')->nullable();$t->string('button_text')->nullable();$t->string('button_url')->nullable();$t->unsignedInteger('sort_order')->default(0);$t->boolean('is_active')->default(true);$t->timestamp('starts_at')->nullable();$t->timestamp('ends_at')->nullable();$t->timestamps();});
  Schema::create('settings',function(Blueprint $t){$t->id();$t->string('group')->default('general')->index();$t->string('key')->unique();$t->longText('value')->nullable();$t->string('type')->default('text');$t->boolean('is_public')->default(false);$t->timestamps();});
  Schema::create('contact_messages',function(Blueprint $t){$t->id();$t->string('name');$t->string('email');$t->string('phone')->nullable();$t->string('subject')->nullable();$t->text('message');$t->enum('status',['new','read','replied','closed'])->default('new');$t->timestamps();});
  Schema::create('subscribers',function(Blueprint $t){$t->id();$t->string('email')->unique();$t->boolean('is_active')->default(true);$t->timestamp('subscribed_at')->nullable();$t->timestamp('unsubscribed_at')->nullable();$t->timestamps();});
  Schema::create('jobs',function(Blueprint $t){$t->bigIncrements('id');$t->string('queue')->index();$t->longText('payload');$t->unsignedTinyInteger('attempts');$t->unsignedInteger('reserved_at')->nullable();$t->unsignedInteger('available_at');$t->unsignedInteger('created_at');});
  Schema::create('failed_jobs',function(Blueprint $t){$t->id();$t->string('uuid')->unique();$t->text('connection');$t->text('queue');$t->longText('payload');$t->longText('exception');$t->timestamp('failed_at')->useCurrent();});
 }
 public function down(){Schema::dropIfExists('failed_jobs');Schema::dropIfExists('jobs');Schema::dropIfExists('subscribers');Schema::dropIfExists('contact_messages');Schema::dropIfExists('settings');Schema::dropIfExists('banners');Schema::dropIfExists('posts');Schema::dropIfExists('pages');}
};
