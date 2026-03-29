<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserCourse;
use App\Models\ModuleProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ChatController extends Controller
{
  public function index()
  {
    $user = Auth::user();
    $userCourses = UserCourse::with(['course.modules.progresses' => function ($query) use ($user) {
      $query->where('user_id', $user->id);
    }])->where('user_id', $user->id)->get();

    $sessionKey = 'chat_history_' . $user->id;
    $history = Session::get($sessionKey, []);

    return view('chat', compact('user', 'userCourses', 'history'));
  }

  public function sendMessage(Request $request)
  {
    $user = Auth::user();
    $message = $request->input('message');

    if (!$message) {
      return response()->json(['error' => 'Message is required'], 400);
    }

    $sessionKey = 'chat_history_' . $user->id;
    $history = Session::get($sessionKey, []);

    // Fetch comprehensive user data for system prompt
    $userCourses = UserCourse::with(['course.modules.progresses' => function ($query) use ($user) {
      $query->where('user_id', $user->id);
    }])->where('user_id', $user->id)->get();

    $systemPrompt = $this->buildSystemPrompt($user, $userCourses);

    $messages = [
      ['role' => 'system', 'content' => $systemPrompt],
    ];

    // Add history (last 10 messages to keep context reasonable)
    foreach (array_slice($history, -10) as $chat) {
      $messages[] = ['role' => 'user', 'content' => $chat['user']];
      $messages[] = ['role' => 'assistant', 'content' => $chat['bot']];
    }

    $messages[] = ['role' => 'user', 'content' => $message];

    $apiKey = config('services.groq.api_key');
    $baseUrl = config('services.groq.base_url');
    $model = config('services.groq.model');

    try {
      $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $apiKey,
      ])->post($baseUrl . '/chat/completions', [
        'model' => $model,
        'messages' => $messages,
        'max_tokens' => 1024,
        'temperature' => 0.7,
      ]);

      if ($response->failed()) {
        return response()->json(['error' => 'Groq API failed: ' . $response->body()], 500);
      }

      $botReply = $response->json()['choices'][0]['message']['content'];

      // Save to history
      $history[] = [
        'user' => $message,
        'bot' => $botReply,
        'timestamp' => now()->toDateTimeString(),
      ];
      Session::put($sessionKey, $history);

      return response()->json(['reply' => $botReply]);
    } catch (\Exception $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }
  }

  public function clearHistory()
  {
    $user = Auth::user();
    Session::forget('chat_history_' . $user->id);
    return redirect()->route('chat');
  }

  private function buildSystemPrompt($user, $userCourses)
  {
    $prompt = "Anda adalah \"SpiritBot\", Asisten Belajar AI untuk Platform Kursus Online Spirit.\n";
    $prompt .= "Anda membantu seorang siswa bernama {$user->name}.\n\n";
    $prompt .= "=== PROFIL SISWA ===\n";
    $prompt .= "Nama: {$user->name}\n";
    $prompt .= "Peran: {$user->role}\n";
    $prompt .= "Bergabung sejak: {$user->joined_at}\n\n";
    $prompt .= "=== KURSUS YANG DIIKUTI & PROGRES ===\n\n";

    foreach ($userCourses as $uc) {
      $course = $uc->course;
      $prompt .= "[KURSUS: {$course->title}]\n";
      $prompt .= "Kategori: " . ucfirst($course->category) . " | Level: " . ucfirst($course->level) . "\n";
      $prompt .= "Progres Keseluruhan: {$uc->overall_progress}%\n";
      $prompt .= "Terdaftar: {$uc->enrolled_at->toDateString()} | Terakhir Diakses: " . ($uc->last_accessed_at ? $uc->last_accessed_at->toDateString() : 'N/A') . "\n";
      $prompt .= "Status: " . ($uc->overall_progress == 100 ? 'Selesai' : 'Sedang Berjalan') . "\n\n";
      $prompt .= "Progres Modul:\n";

      foreach ($course->modules as $module) {
        $progress = $module->progresses->first();
        $statusIcon = '⬜';
        if ($progress) {
          if ($progress->status == 'completed') $statusIcon = '✅';
          elseif ($progress->status == 'in_progress') $statusIcon = '🔄';
        }
        $statusLabel = $progress ? ($progress->status == 'completed' ? 'selesai' : 'sedang_berjalan') : 'belum_dimulai';
        $timeLabel = $progress ? ", {$progress->time_spent_minutes} menit dihabiskan" : "";
        $prompt .= "{$statusIcon} {$module->order_number}. {$module->title} ({$statusLabel}{$timeLabel})\n";
      }
      $prompt .= "\n";
    }

    $prompt .= "=== PERAN ANDA ===\n";
    $prompt .= "- Jawab pertanyaan tentang kursus, progres, dan rekomendasi belajar mereka\n";
    $prompt .= "- Berikan saran yang dipersonalisasi berdasarkan data progres aktual mereka di atas\n";
    $prompt .= "- Sarankan apa yang harus dipelajari selanjutnya berdasarkan di mana mereka berhenti\n";
    $prompt .= "- Jelaskan konsep dari topik kursus mereka\n";
    $prompt .= "- Motivasi dan bimbing mereka\n";
    $prompt .= "- Selalu merespons dalam bahasa Indonesia yang ramah, sopan, dan membantu\n";
    $prompt .= "- Gunakan istilah teknis (seperti Backend, Frontend, API, Database, dll) tetap dalam bahasa Inggris agar akurat secara konteks industri\n";
    $prompt .= "- Bersikaplah ramah, menyemangati, dan spesifik — gunakan referensi data aktual mereka bila relevan\n";
    return $prompt;
  }
}
