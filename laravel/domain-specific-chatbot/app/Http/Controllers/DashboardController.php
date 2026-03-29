<?php

namespace App\Http\Controllers;

use App\Models\UserCourse;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $userCourses = UserCourse::with('course')
            ->where('user_id', $user->id)
            ->get();

        $stats = [
            'enrolled_count' => $userCourses->count(),
            'completed_count' => $userCourses->where('overall_progress', 100)->count(),
            'average_progress' => $userCourses->avg('overall_progress') ?? 0,
        ];

        return view('dashboard', compact('user', 'userCourses', 'stats'));
    }
}
