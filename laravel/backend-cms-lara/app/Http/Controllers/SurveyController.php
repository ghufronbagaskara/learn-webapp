<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\View\View;

class SurveyController extends Controller
{
  public function show(Survey $survey): View
  {
    abort_unless($survey->is_active, 404);

    return view('pages.survey-show', [
      'survey' => $survey,
      'pageTitle' => $survey->title,
    ]);
  }
}
