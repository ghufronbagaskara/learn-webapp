<?php

namespace App\Http\Livewire\Public;

use App\Models\Survey;
use App\Models\SurveyResponse;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class SurveyRunner extends Component
{
    public Survey $survey;
    public ?string $respondentEmail = null;
    public string $responseJson = '{}';

    public function mount(Survey $survey): void
    {
        abort_unless($survey->is_active, 404);

        $this->survey = $survey;
    }

    public function updateResponse(string $responseJson): void
    {
        $this->responseJson = $responseJson;
    }

    public function submit(): void
    {
        Validator::make([
            'respondentEmail' => $this->respondentEmail,
            'responseJson' => $this->responseJson,
        ], [
            'respondentEmail' => ['nullable', 'email'],
            'responseJson' => ['required', 'json'],
        ])->validate();

        SurveyResponse::create([
            'survey_id' => $this->survey->id,
            'respondent_email' => $this->respondentEmail,
            'response_data' => json_decode($this->responseJson, true),
            'submitted_at' => now(),
        ]);

        session()->flash('success', 'Thank you for your response.');

        $this->dispatch('survey-submitted');
    }

    public function render()
    {
        return view('livewire.public.survey-runner', [
            'surveySchemaJson' => json_encode($this->survey->json_schema, JSON_UNESCAPED_UNICODE),
        ]);
    }
}
