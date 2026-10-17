<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoursePreview extends Model
{
    use HasFactory;

      // Define the relationship with CourseModule, filtering by status 1
      public function courseModule()
      {
          return $this->belongsTo(CourseModule::class, 'course_module_id')
                      ->where('status', 1);
      }
}
