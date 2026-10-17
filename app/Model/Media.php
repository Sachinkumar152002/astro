<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Image;
use File;


class Media extends Model
{
    protected $fillable = ['file','title','caption','attributes','sizes','type'];

    use SoftDeletes;

    public function image($width = '',$height = '')
    {
      // Check if this is a video or document (e.g. PDF) - return original path, no image processing
      $videoExtensions = ['mp4', 'mov', 'avi', 'wmv', 'flv', 'webm', 'mkv'];
      $documentExtensions = ['pdf'];
      $fileExtension = strtolower(pathinfo($this->file, PATHINFO_EXTENSION));

      if(in_array($fileExtension, $videoExtensions)) {
        // For video files, check onetoonepuja first, then media directory
        $videoPath = public_path('onetoonepuja/' . $this->file);
        if(File::exists($videoPath)) {
          return asset('onetoonepuja/' . $this->file);
        }
        if(File::exists(public_path('media/'.$this->id.'/').$this->file)) {
          return asset('media/'.$this->id.'/'.$this->file);
        }
        return asset('images/noimage.jpg');
      }

      if(in_array($fileExtension, $documentExtensions)) {
        if(File::exists(public_path('media/'.$this->id.'/').$this->file)) {
          return asset('media/'.$this->id.'/'.$this->file);
        }
        return asset('images/noimage.jpg');
      }

      $size = '';
      if(File::exists(public_path('media/'.$this->id.'/').$this->file)) {
      if($height ||  $width) {
        $width  = !$width   ? $height : $width;
        $height = !$height  ? $width  : $height;
        $size = $width .'-'. $height;
        $sizes = $this->sizes ? json_decode($this->sizes) : [];

        if(!in_array($size, $sizes)) {
          try {
            $img = Image::make(public_path('media/'.$this->id.'/').$this->file);
            $img->resize($width, $height,function ($constraint) {
                  $constraint->aspectRatio();
                   $constraint->upsize();
              });
            $path = public_path('media/'.$this->id.'/'.$size);
            if(!File::exists($path)) {
              File::makeDirectory($path, 0755, true);
            }
            $img->save($path.'/'.$this->file);
            $sizes[] = $size;
            $this->sizes = json_encode($sizes);
            $this->save();
          } catch (\Exception $e) {
            // If image processing fails, return original or no image
            \Log::error('Image processing failed: ' . $e->getMessage());
            if(File::exists(public_path('media/'.$this->id.'/').$this->file)) {
              return asset('media/'.$this->id.'/'.$this->file);
            }
            return asset('images/noimage.jpg');
          }
        }

        $mediaPath = public_path('media/'.$this->id.'/'.$size.'/'.$this->file);
        if(!File::exists($mediaPath)) {
          try {
            $img = Image::make(public_path('media/'.$this->id.'/').$this->file);
            $img->resize($width, $height,function ($constraint) {
                  $constraint->aspectRatio();
                   $constraint->upsize();
              });
            $path = public_path('media/'.$this->id.'/'.$size);
             if(!File::exists($path)) {
                File::makeDirectory($path, 0755, true);
             }
            $img->save($path.'/'.$this->file);
          } catch (\Exception $e) {
            // If image processing fails, return original or no image
            \Log::error('Image processing failed: ' . $e->getMessage());
            if(File::exists(public_path('media/'.$this->id.'/').$this->file)) {
              return asset('media/'.$this->id.'/'.$this->file);
            }
            return asset('images/noimage.jpg');
          }
        }
        $size = $size.'/';
      }
        if(File::exists(public_path('media/'.$this->id.'/'.$size.$this->file)))
          return asset('media/'.$this->id.'/'.$size.$this->file);
      }

      return asset('images/noimage.jpg');

    }

    public static function types() {

    return ['none'=>'None', 'article' => 'Article', 'banner'=>'Banner', 'cms'=>'Pages', 'advertisement'=>'Advertisement', 'poll'=>'Polls', 'profile' => 'Profile', 'testimonial' => 'Testimonial','users' => 'Users', 'instructor' => 'Instructor'
      ,'courseBenifits' => 'CourseBenifits', 'categories' => 'Categories', 'productCategories' => 'ProductCategories'];
    }


    public function getOriginalPathAttribute() {
      // Check if this is a URL (starts with http:// or https://)
      if(filter_var($this->file, FILTER_VALIDATE_URL)) {
        return $this->file;
      }

      // Check if video exists in onetoonepuja directory
      $videoPath = public_path('onetoonepuja/' . $this->file);
      if(File::exists($videoPath)) {
        return asset('onetoonepuja/' . $this->file);
      }
      return asset('media/'.$this->id.'/'.$this->file);
    }

    public static function no_image(){
      return asset('images/noimage.png');
    }
}
