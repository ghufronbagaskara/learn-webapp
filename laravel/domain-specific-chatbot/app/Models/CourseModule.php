<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseModule extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'order_number',
        'title',
        'description',
        'duration_minutes',
        'type',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function progresses()
    {
        return $this->hasMany(ModuleProgress::class);
    }
}
