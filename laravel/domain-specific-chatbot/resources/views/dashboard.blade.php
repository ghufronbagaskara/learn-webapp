@extends('layouts.app')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Welcome back, {{ $user->name }}! 👋</h1>
    <p class="mt-2 text-gray-600 italic">"The beautiful thing about learning is that nobody can take it away from you."</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center space-x-4">
        <div class="p-3 bg-blue-100 rounded-lg text-blue-600">
            <i class="fas fa-book-open text-xl"></i>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Enrolled Courses</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['enrolled_count'] }}</p>
        </div>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center space-x-4">
        <div class="p-3 bg-green-100 rounded-lg text-green-600">
            <i class="fas fa-check-circle text-xl"></i>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Completed</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['completed_count'] }}</p>
        </div>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center space-x-4">
        <div class="p-3 bg-indigo-100 rounded-lg text-indigo-600">
            <i class="fas fa-chart-line text-xl"></i>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Average Progress</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['average_progress'], 1) }}%</p>
        </div>
    </div>
</div>

<div class="mb-6 flex justify-between items-center">
    <h2 class="text-xl font-bold text-gray-900">Your Learning Path</h2>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    @foreach($userCourses as $uc)
    @php
        $course = $uc->course;
        $categoryColors = [
            'backend' => 'bg-blue-100 text-blue-800',
            'frontend' => 'bg-green-100 text-green-800',
            'ai_engineer' => 'bg-purple-100 text-purple-800'
        ];
        $color = $categoryColors[$course->category] ?? 'bg-gray-100 text-gray-800';
    @endphp
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col transition hover:shadow-md">
        <div class="h-32 bg-gray-200 relative">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-500 to-purple-600 opacity-20"></div>
            <div class="absolute top-4 left-4">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                    {{ ucfirst(str_replace('_', ' ', $course->category)) }}
                </span>
            </div>
        </div>
        <div class="p-6 flex-grow">
            <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $course->title }}</h3>
            <p class="text-sm text-gray-500 line-clamp-2 mb-4">{{ $course->description }}</p>
            
            <div class="mb-4">
                <div class="flex justify-between items-center mb-1">
                    <span class="text-xs font-semibold text-gray-600">Progress</span>
                    <span class="text-xs font-semibold text-indigo-600">{{ number_format($uc->overall_progress, 0) }}%</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2">
                    <div class="bg-indigo-600 h-2 rounded-full transition-all duration-500" style="width: {{ $uc->overall_progress }}%"></div>
                </div>
            </div>

            <div class="flex items-center justify-between text-xs text-gray-400">
                <span>{{ $course->total_modules }} Modules</span>
                <span>Last accessed: {{ $uc->last_accessed_at ? $uc->last_accessed_at->diffForHumans() : 'Never' }}</span>
            </div>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
            <a href="#" class="block w-full text-center py-2 px-4 bg-white border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                {{ $uc->overall_progress == 100 ? 'Review Course' : 'Continue Learning' }}
            </a>
        </div>
    </div>
    @endforeach
</div>

<div class="fixed bottom-8 right-8">
    <a href="{{ route('chat') }}" class="flex items-center space-x-2 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-4 rounded-full shadow-lg transition-all transform hover:scale-105 active:scale-95">
        <i class="fas fa-robot text-xl"></i>
        <span class="font-bold">Tanya SpiritBot 🤖</span>
    </a>
</div>
@endsection
