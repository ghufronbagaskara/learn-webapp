<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\BlogPostStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBlogPostRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return $this->user() !== null;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'title' => ['required', 'string', 'max:255'],
      'category_id' => ['required', 'integer', 'exists:categories,id'],
      'content' => ['required', 'string'],
      'excerpt' => ['nullable', 'string', 'max:320'],
      'featured_image' => ['nullable', 'image', 'max:2048'],
      'status' => ['required', Rule::in(BlogPostStatus::values())],
      'tag_ids' => ['nullable', 'array'],
      'tag_ids.*' => ['integer', 'exists:tags,id'],
      'meta_title' => ['nullable', 'string', 'max:255'],
      'meta_description' => ['nullable', 'string', 'max:255'],
      'meta_keywords' => ['nullable', 'string', 'max:255'],
      'og_image' => ['nullable', 'url', 'max:2048'],
      'canonical_url' => ['nullable', 'url', 'max:2048'],
    ];
  }

  /**
   * @return array<string, string>
   */
  public function messages(): array
  {
    return [
      'title.required' => 'Judul artikel wajib diisi.',
      'category_id.required' => 'Kategori wajib dipilih.',
      'content.required' => 'Konten artikel wajib diisi.',
      'status.required' => 'Status artikel wajib dipilih.',
    ];
  }
}
