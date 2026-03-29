<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseModule;
use Illuminate\Database\Seeder;

class CourseModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $backendCourse = Course::where('slug', 'backend-engineering-mastery')->first();
        $frontendCourse = Course::where('slug', 'frontend-development-bootcamp')->first();
        $aiCourse = Course::where('slug', 'ai-engineer-fundamentals')->first();

        // Backend Modules
        $backendModules = [
            ['title' => 'Introduction to REST APIs', 'duration' => 30, 'type' => 'video'],
            ['title' => 'Authentication & Authorization', 'duration' => 45, 'type' => 'video'],
            ['title' => 'Database Design with MySQL', 'duration' => 60, 'type' => 'reading'],
            ['title' => 'Laravel Eloquent ORM', 'duration' => 45, 'type' => 'video'],
            ['title' => 'API Versioning', 'duration' => 20, 'type' => 'reading'],
            ['title' => 'Caching Strategies with Redis', 'duration' => 40, 'type' => 'video'],
            ['title' => 'Queue & Jobs', 'duration' => 50, 'type' => 'video'],
            ['title' => 'Unit Testing', 'duration' => 45, 'type' => 'video'],
            ['title' => 'Docker for Backend', 'duration' => 60, 'type' => 'reading'],
            ['title' => 'CI/CD Pipeline', 'duration' => 45, 'type' => 'video'],
            ['title' => 'Performance Optimization', 'duration' => 30, 'type' => 'quiz'],
            ['title' => 'Deployment to VPS', 'duration' => 90, 'type' => 'video'],
        ];

        foreach ($backendModules as $index => $module) {
            CourseModule::create([
                'course_id' => $backendCourse->id,
                'order_number' => $index + 1,
                'title' => $module['title'],
                'description' => 'Description for ' . $module['title'],
                'duration_minutes' => $module['duration'],
                'type' => $module['type'],
            ]);
        }

        // Frontend Modules
        $frontendModules = [
            ['title' => 'HTML & CSS Fundamentals', 'duration' => 30, 'type' => 'video'],
            ['title' => 'JavaScript ES6+', 'duration' => 45, 'type' => 'video'],
            ['title' => 'DOM Manipulation', 'duration' => 30, 'type' => 'reading'],
            ['title' => 'Fetch API & Async', 'duration' => 40, 'type' => 'video'],
            ['title' => 'Intro to Vue.js', 'duration' => 30, 'type' => 'video'],
            ['title' => 'Vue Components & Props', 'duration' => 45, 'type' => 'reading'],
            ['title' => 'State Management (Pinia)', 'duration' => 60, 'type' => 'video'],
            ['title' => 'Routing dengan Vue Router', 'duration' => 30, 'type' => 'video'],
            ['title' => 'Build & Deploy', 'duration' => 15, 'type' => 'quiz'],
            ['title' => 'CSS Framework (Tailwind)', 'duration' => 45, 'type' => 'video'],
        ];

        foreach ($frontendModules as $index => $module) {
            CourseModule::create([
                'course_id' => $frontendCourse->id,
                'order_number' => $index + 1,
                'title' => $module['title'],
                'description' => 'Description for ' . $module['title'],
                'duration_minutes' => $module['duration'],
                'type' => $module['type'],
            ]);
        }

        // AI Modules
        $aiModules = [
            ['title' => 'Python for AI', 'duration' => 45, 'type' => 'video'],
            ['title' => 'Machine Learning Basics', 'duration' => 60, 'type' => 'reading'],
            ['title' => 'Neural Networks', 'duration' => 90, 'type' => 'video'],
            ['title' => 'PyTorch Fundamentals', 'duration' => 120, 'type' => 'video'],
            ['title' => 'NLP & Transformers', 'duration' => 90, 'type' => 'reading'],
            ['title' => 'Working with LLM APIs', 'duration' => 45, 'type' => 'video'],
            ['title' => 'Prompt Engineering', 'duration' => 30, 'type' => 'reading'],
            ['title' => 'Fine-tuning Models', 'duration' => 120, 'type' => 'video'],
            ['title' => 'Building AI Applications', 'duration' => 60, 'type' => 'quiz'],
            ['title' => 'Vector Databases', 'duration' => 45, 'type' => 'reading'],
            ['title' => 'Deploying AI Models', 'duration' => 60, 'type' => 'video'],
        ];

        foreach ($aiModules as $index => $module) {
            CourseModule::create([
                'course_id' => $aiCourse->id,
                'order_number' => $index + 1,
                'title' => $module['title'],
                'description' => 'Description for ' . $module['title'],
                'duration_minutes' => $module['duration'],
                'type' => $module['type'],
            ]);
        }
    }
}
