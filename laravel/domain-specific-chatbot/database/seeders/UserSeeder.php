<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\ModuleProgress;
use App\Models\User;
use App\Models\UserCourse;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = Hash::make('password');

        $usersData = [
            ['name' => 'Andi Pratama', 'email' => 'andi@example.com', 'role' => 'student'],
            ['name' => 'Sari Dewi', 'email' => 'sari@example.com', 'role' => 'student'],
            ['name' => 'Budi Santoso', 'email' => 'budi@example.com', 'role' => 'student'],
            ['name' => 'Rina Kusuma', 'email' => 'rina@example.com', 'role' => 'student'],
            ['name' => 'Dimas Raharja', 'email' => 'dimas@example.com', 'role' => 'student'],
            ['name' => 'Admin Spirit', 'email' => 'admin@example.com', 'role' => 'admin'],
        ];

        $users = [];
        foreach ($usersData as $userData) {
            $users[$userData['email']] = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => $password,
                'role' => $userData['role'],
                'joined_at' => Carbon::now()->subMonths(3),
            ]);
        }

        $backendCourse = Course::where('slug', 'backend-engineering-mastery')->first();
        $frontendCourse = Course::where('slug', 'frontend-development-bootcamp')->first();
        $aiCourse = Course::where('slug', 'ai-engineer-fundamentals')->first();

        // Andi Pratama: Backend (75%, modul 9), Frontend (100%, completed)
        $this->enrollUser($users['andi@example.com'], $backendCourse, 75);
        $this->enrollUser($users['andi@example.com'], $frontendCourse, 100);

        // Sari Dewi: AI Engineer (30%, modul 4), Frontend (55%, modul 6)
        $this->enrollUser($users['sari@example.com'], $aiCourse, 30);
        $this->enrollUser($users['sari@example.com'], $frontendCourse, 55);

        // Budi Santoso: Backend (10%, modul 1-2), AI Engineer (0%, enrolled)
        $this->enrollUser($users['budi@example.com'], $backendCourse, 10);
        $this->enrollUser($users['budi@example.com'], $aiCourse, 0);

        // Rina Kusuma: Frontend (90%, modul terakhir), Backend (45%, modul 6), AI Engineer (60%, modul 7)
        $this->enrollUser($users['rina@example.com'], $frontendCourse, 90);
        $this->enrollUser($users['rina@example.com'], $backendCourse, 45);
        $this->enrollUser($users['rina@example.com'], $aiCourse, 60);

        // Dimas Raharja: AI Engineer (85%, modul 10), Backend (20%)
        $this->enrollUser($users['dimas@example.com'], $aiCourse, 85);
        $this->enrollUser($users['dimas@example.com'], $backendCourse, 20);
    }

    private function enrollUser($user, $course, $progressPercent)
    {
        $enrolledAt = Carbon::now()->subDays(rand(30, 60));
        $completedAt = $progressPercent == 100 ? Carbon::now()->subDays(rand(1, 10)) : null;

        UserCourse::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'enrolled_at' => $enrolledAt,
            'completed_at' => $completedAt,
            'overall_progress' => $progressPercent,
            'last_accessed_at' => Carbon::now()->subHours(rand(1, 72)),
        ]);

        $modules = $course->modules;
        $totalModules = $modules->count();
        $completedCount = floor(($progressPercent / 100) * $totalModules);

        foreach ($modules as $index => $module) {
            $order = $index + 1;
            $status = 'not_started';
            $startedAt = null;
            $completedAtModule = null;
            $timeSpent = 0;

            if ($order <= $completedCount) {
                $status = 'completed';
                $startedAt = (clone $enrolledAt)->addMinutes($index * 60);
                $completedAtModule = (clone $startedAt)->addMinutes($module->duration_minutes);
                $timeSpent = $module->duration_minutes + rand(-5, 10);
            } elseif ($order == $completedCount + 1 && $progressPercent < 100 && $progressPercent > 0) {
                $status = 'in_progress';
                $startedAt = Carbon::now()->subMinutes(rand(10, 60));
                $timeSpent = rand(5, 15);
            }

            ModuleProgress::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'course_module_id' => $module->id,
                'status' => $status,
                'started_at' => $startedAt,
                'completed_at' => $completedAtModule,
                'time_spent_minutes' => $timeSpent,
                'quiz_score' => $status == 'completed' && $module->type == 'quiz' ? rand(80, 100) : null,
            ]);
        }
    }
}
