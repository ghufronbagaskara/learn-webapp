<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
            [
                'title' => 'Backend Engineering Mastery',
                'description' => 'Master backend development with Laravel and MySQL.',
                'category' => 'backend',
                'level' => 'intermediate',
                'total_modules' => 12,
            ],
            [
                'title' => 'Frontend Development Bootcamp',
                'description' => 'Learn modern frontend development with Vue.js and Tailwind CSS.',
                'category' => 'frontend',
                'level' => 'beginner',
                'total_modules' => 10,
            ],
            [
                'title' => 'AI Engineer Fundamentals',
                'description' => 'Deep dive into AI, Machine Learning, and LLMs.',
                'category' => 'ai_engineer',
                'level' => 'advanced',
                'total_modules' => 11,
            ],
        ];

        foreach ($courses as $course) {
            Course::create(array_merge($course, [
                'slug' => Str::slug($course['title']),
            ]));
        }
    }
}
