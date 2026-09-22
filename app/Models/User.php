<?php
namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class User extends Authenticatable implements MustVerifyEmail { use HasFactory,Notifiable; protected $fillable=['name','email','password','role','phone','avatar','google_id','email_verified_at','is_active']; protected $hidden=['password','remember_token']; protected $casts=['email_verified_at'=>'datetime','is_active'=>'boolean']; public function isAdmin(){return $this->role==='admin';} public function orders(){return $this->hasMany(Order::class);} public function addresses(){return $this->hasMany(Address::class);} public function wishlistProducts(){return $this->belongsToMany(Product::class,'wishlists')->withTimestamps();} }
