<?php

namespace Database\Seeders;

use App\Models\Survey;
use Illuminate\Database\Seeder;

class SurveySeeder extends Seeder
{
  public function run(): void
  {
    Survey::updateOrCreate([
      'title' => 'Customer Satisfaction Survey',
    ], [
      'description' => 'Help us improve our services.',
      'json_schema' => [
        'title' => 'Customer Satisfaction',
        'pages' => [[
          'name' => 'page1',
          'elements' => [
            [
              'type' => 'rating',
              'name' => 'overall_satisfaction',
              'title' => 'How satisfied are you with our service?',
              'rateMin' => 1,
              'rateMax' => 5,
            ],
            [
              'type' => 'comment',
              'name' => 'feedback',
              'title' => 'Any additional feedback?',
            ],
          ],
        ]],
      ],
      'is_active' => true,
      'created_by' => 1,
    ]);
  }
}
