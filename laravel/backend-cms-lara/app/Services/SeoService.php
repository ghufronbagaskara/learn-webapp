<?php

namespace App\Services;

use App\Models\SeoMeta;
use Illuminate\Database\Eloquent\Model;

class SeoService
{
  public function save(Model $model, array $data): SeoMeta
  {
    return $model->seoMeta()->updateOrCreate(
      [
        'seoable_type' => get_class($model),
        'seoable_id' => $model->id,
      ],
      [
        'meta_title' => $data['meta_title'] ?? null,
        'meta_description' => $data['meta_description'] ?? null,
        'meta_keywords' => $data['meta_keywords'] ?? null,
        'og_title' => $data['og_title'] ?? null,
        'og_description' => $data['og_description'] ?? null,
        'og_image' => $data['og_image'] ?? null,
        'canonical_url' => $data['canonical_url'] ?? null,
      ],
    );
  }
}
