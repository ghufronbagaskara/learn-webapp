<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title',
        'description',
        'category',
        'level',
        'thumbnail',
        'total_modules',
    ];

    public function modules()
    {
        return $this->hasMany(CourseModule::class)->orderBy('order_number');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_courses')
            ->withPivot(['enrolled_at', 'completed_at', 'overall_progress', 'last_accessed_at'])
            ->withTimestamps();
    }
}
