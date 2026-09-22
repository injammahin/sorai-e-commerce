<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
class Setting extends Model { protected $guarded=[]; protected static function booted(){static::saved(fn()=>Cache::forget('settings.public'));static::deleted(fn()=>Cache::forget('settings.public'));} public static function valueOf($key,$default=null){return optional(static::where('key',$key)->first())->value??$default;} public static function publicMap(){return Cache::remember('settings.public',3600,fn()=>static::where('is_public',true)->pluck('value','key')->all());} }
