<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveyResponse extends Model
{
  use HasFactory;

  protected $fillable = [
    'survey_id',
    'respondent_email',
    'response_data',
    'submitted_at',
  ];

  protected function casts(): array
  {
    return [
      'response_data' => 'array',
      'submitted_at' => 'datetime',
    ];
  }

  public function survey(): BelongsTo
  {
    return $this->belongsTo(Survey::class);
  }
}
