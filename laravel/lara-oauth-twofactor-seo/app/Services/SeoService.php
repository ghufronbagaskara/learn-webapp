<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\BlogPost;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Support\Str;

class SeoService
{
  /**
   * Set SEO metadata for blog index pages.
   */
  public function setForListing(?string $title = null, ?string $description = null): void
  {
    $seoTitle = $title ?? 'ABC Blog';
    $seoDescription = $description ?? 'Artikel teknologi, pemrograman, dan praktik pengembangan modern.';

    SEOMeta::setTitle($seoTitle);
    SEOMeta::setDescription($seoDescription);
    OpenGraph::setTitle($seoTitle);
    OpenGraph::setDescription($seoDescription);
    OpenGraph::setType('website');
    JsonLd::setTitle($seoTitle);
    JsonLd::setDescription($seoDescription);
  }

  /**
   * Set SEO metadata for a single blog post.
   */
  public function setForPost(BlogPost $post): void
  {
    $title = $post->meta_title ?? $post->title;
    $description = $post->meta_description ?? Str::limit(strip_tags($post->content), 160);

    SEOMeta::setTitle($title);
    SEOMeta::setDescription($description);
    SEOMeta::setCanonical($post->canonical_url ?: route('blog.show', $post));

    OpenGraph::setTitle($title);
    OpenGraph::setDescription($description);
    OpenGraph::setType('article');
    OpenGraph::addImage($post->og_image ?: asset('images/default-og.png'));

    JsonLd::setType('Article');
    JsonLd::setTitle($post->title);
    JsonLd::setDescription($description);
    JsonLd::addValue('datePublished', $post->published_at?->toIso8601String());
    JsonLd::addValue('author', [
      '@type' => 'Person',
      'name' => $post->user->name,
    ]);
  }
}
