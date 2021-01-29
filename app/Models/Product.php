<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
//use Intervention\Image\Facades\Image;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];
    public static $image_path = "images/products";

    public function getImageUrlAttribute()
    {
        if(Storage::disk('public')->exists($this->image))
            return Storage::disk('public')->url($this->image).'?'.Storage::disk('public')->lastModified($this->image);
        else
            return config('app.url').'/img/default.jpg';
    }
    
    public static function uploadImage(UploadedFile $file)
    {
        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $filename = pathinfo($filename, PATHINFO_FILENAME);
        $filename = substr($filename, 0, 24).'_'.time().'.'.$extension;
        $folder = self::$image_path.'/'.date('Y').'/'.date('m');
        $path = $file->storeAs($folder, $filename, 'public');
        /*$img = Image::make( storage_path('app/public/').$path );
        $w = $img->width();
        $h = $img->height();
        $w = floor(($w/$h)*900);
        $img = $img->resize(900, 900, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $img->save( storage_path('app/public/').$path, 70, 'jpg');*/
        //$this->generateThumb(640,360);
        //$this->generateThumb(1280,720);
        return $path;
    }

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
