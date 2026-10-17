<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CourseVideo extends Model
{
    protected $fillable = ['course_id', 'title', 'caption', 'coursevideo', 'status'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public static function statusArray()
    {
        return [
            1 => 'Active',
            0 => 'Inactive'
        ];
    }
}
