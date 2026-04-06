<?php

namespace App\Http\Livewire\Admin;

use App\Models\Survey;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class SurveyCreator extends Component
{
  use AuthorizesRequests;

  public ?int $surveyId = null;
  public ?Survey $survey = null;

  #[Validate('required|string|min:3|max:191')]
  public string $titleInput = '';

  #[Validate('nullable|string')]
  public ?string $descriptionInput = null;

  public bool $isActive = true;
  public string $jsonSchema = '{}';

  public function mount(?Survey $survey = null): void
  {
    if ($survey?->exists) {
      $this->survey = $survey;
      $this->surveyId = $survey->id;
      $this->titleInput = $survey->title;
      $this->descriptionInput = $survey->description;
      $this->jsonSchema = json_encode($survey->json_schema, JSON_UNESCAPED_UNICODE) ?: '{}';
      $this->isActive = $survey->is_active;
    }
  }

  public function updateSchema(string $json): void
  {
    $this->jsonSchema = $json;
  }

  public function save(): void
  {
    $this->survey
      ? $this->authorize('update', $this->survey)
      : $this->authorize('create', Survey::class);

    $this->validate();

    Survey::updateOrCreate(
      ['id' => $this->surveyId],
      [
        'title' => $this->titleInput,
        'description' => $this->descriptionInput,
        'json_schema' => json_decode($this->jsonSchema, true) ?: [],
        'is_active' => $this->isActive,
        'created_by' => Auth::id(),
      ],
    );

    session()->flash('success', 'Survey saved.');

    $this->redirect(route('admin.surveys.index'), navigate: true);
  }

  public function render()
  {
    return view('livewire.admin.survey-creator');
  }
}
