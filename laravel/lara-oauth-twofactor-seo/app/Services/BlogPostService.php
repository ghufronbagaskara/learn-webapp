<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\BlogPostStatus;
use App\Models\BlogPost;
use App\Services\Support\SlugService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogPostService
{
  public function __construct(private readonly SlugService $slugService) {}

  /**
   * Create a blog post.
   *
   * @param array<string, mixed> $data
   */
  public function create(array $data, int $authorId): BlogPost
  {
    return DB::transaction(function () use ($data, $authorId): BlogPost {
      $post = new BlogPost();
      $post->fill($this->mapData($data, $authorId));
      $post->save();

      $post->tags()->sync(Arr::get($data, 'tag_ids', []));

      return $post->fresh(['user', 'category', 'tags']) ?? $post;
    });
  }

  /**
   * Update a blog post.
   *
   * @param array<string, mixed> $data
   */
  public function update(BlogPost $post, array $data): BlogPost
  {
    return DB::transaction(function () use ($post, $data): BlogPost {
      $post->fill($this->mapData($data, (int) $post->user_id, $post));
      $post->save();

      $post->tags()->sync(Arr::get($data, 'tag_ids', []));

      return $post->fresh(['user', 'category', 'tags']) ?? $post;
    });
  }

  /**
   * Delete a blog post and attached media file.
   */
  public function delete(BlogPost $post): void
  {
    DB::transaction(function () use ($post): void {
      if ($post->featured_image !== null) {
        $path = Str::replaceStart('/storage/', 'public/', $post->featured_image);
        Storage::delete($path);
      }

      $post->tags()->detach();
      $post->delete();
    });
  }

  /**
   * @param array<string, mixed> $data
   * @return array<string, mixed>
   */
  private function mapData(array $data, int $authorId, ?BlogPost $post = null): array
  {
    $status = Arr::get($data, 'status', BlogPostStatus::Draft->value);
    $title = (string) Arr::get($data, 'title');

    $payload = [
      'user_id' => $authorId,
      'category_id' => Arr::get($data, 'category_id'),
      'title' => $title,
      'slug' => $this->slugService->uniqueSlug(BlogPost::class, $title, $post?->id),
      'content' => Arr::get($data, 'content'),
      'excerpt' => Arr::get($data, 'excerpt') ?: Str::limit(strip_tags((string) Arr::get($data, 'content')), 180),
      'status' => $status,
      'published_at' => $status === BlogPostStatus::Published->value ? now() : null,
      'meta_title' => Arr::get($data, 'meta_title') ?: $title,
      'meta_description' => Arr::get($data, 'meta_description'),
      'meta_keywords' => Arr::get($data, 'meta_keywords'),
      'og_image' => Arr::get($data, 'og_image'),
      'canonical_url' => Arr::get($data, 'canonical_url'),
    ];

    if (Arr::has($data, 'featured_image') && $data['featured_image'] instanceof UploadedFile) {
      $payload['featured_image'] = Storage::url($data['featured_image']->store('blog/images', 'public'));
    }

    return $payload;
  }
}
