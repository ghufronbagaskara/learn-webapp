<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Survey;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SurveyController extends Controller
{
  public function index(): View
  {
    $this->authorize('viewAny', Survey::class);

    return view('admin.surveys.index', ['pageTitle' => 'Surveys']);
  }

  public function create(): View
  {
    $this->authorize('create', Survey::class);

    return view('admin.surveys.create', ['pageTitle' => 'Create Survey']);
  }

  public function store(): RedirectResponse
  {
    return redirect()->route('admin.surveys.index');
  }

  public function show(Survey $survey): RedirectResponse
  {
    return redirect()->route('admin.surveys.edit', $survey);
  }

  public function edit(Survey $survey): View
  {
    $this->authorize('update', $survey);

    return view('admin.surveys.edit', [
      'survey' => $survey,
      'pageTitle' => 'Edit Survey',
    ]);
  }

  public function update(Survey $survey): RedirectResponse
  {
    $this->authorize('update', $survey);

    return redirect()->route('admin.surveys.edit', $survey);
  }

  public function destroy(Survey $survey): RedirectResponse
  {
    $this->authorize('delete', $survey);

    $survey->delete();

    session()->flash('success', 'Survey deleted successfully.');

    return redirect()->route('admin.surveys.index');
  }
}
