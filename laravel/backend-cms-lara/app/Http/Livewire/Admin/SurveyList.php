<?php

namespace App\Http\Livewire\Admin;

use App\Models\Survey;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class SurveyList extends Component
{
  use AuthorizesRequests, WithPagination;

  public function toggleActive(int $surveyId): void
  {
    $survey = Survey::findOrFail($surveyId);

    $this->authorize('update', $survey);

    $survey->update([
      'is_active' => ! $survey->is_active,
    ]);

    session()->flash('success', 'Survey status updated.');
  }

  public function render()
  {
    $this->authorize('viewAny', Survey::class);

    return view('livewire.admin.survey-list', [
      'surveys' => Survey::with('creator')->latest()->paginate(10),
    ]);
  }
}
