<?php
namespace App\Services;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
class MediaService { public function store(UploadedFile $file,string $folder): string {return 'storage/'.$file->store($folder,'public');} public function delete(?string $path): void {if($path&&str_starts_with($path,'storage/'))Storage::disk('public')->delete(substr($path,8));} }
