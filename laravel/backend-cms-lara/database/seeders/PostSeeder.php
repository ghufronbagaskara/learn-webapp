<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\SeoMeta;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
  public function run(): void
  {
    $author = User::where('email', 'editor@example.com')->firstOrFail();

    $posts = [
      ['Tech Trends 2026', 'tech-trends-2026', 'Explore emerging technology trends shaping digital products in 2026.'],
      ['Remote Work That Actually Works', 'remote-work-that-actually-works', 'A practical framework for productive and sustainable remote collaboration.'],
      ['Inside Our Product Launch Playbook', 'inside-our-product-launch-playbook', 'How we launch products with confidence using clear milestones and feedback loops.'],
      ['Web Performance Essentials for Modern Teams', 'web-performance-essentials-modern-teams', 'Simple performance habits that improve UX and conversion rates.'],
      ['Why Open Source Builds Better Teams', 'why-open-source-builds-better-teams', 'How contribution culture strengthens engineering capability and innovation.'],
    ];

    foreach ($posts as [$title, $slug, $excerpt]) {
      $body = '<p>' . $excerpt . '</p><p>Our team applies this approach in real projects, balancing speed, quality, and long-term maintainability for client success.</p>';

      $post = Post::updateOrCreate([
        'slug' => $slug,
      ], [
        'title' => $title,
        'excerpt' => $excerpt,
        'body' => $body,
        'cover_image' => null,
        'category' => Str::of($title)->before(' ')->toString(),
        'is_published' => true,
        'published_at' => now(),
        'author_id' => $author->id,
      ]);

      SeoMeta::updateOrCreate([
        'seoable_type' => Post::class,
        'seoable_id' => $post->id,
      ], [
        'meta_title' => $title . ' | Maxian Blog',
        'meta_description' => $excerpt,
        'meta_keywords' => 'blog, article, insights',
        'og_title' => $title,
        'og_description' => $excerpt,
        'canonical_url' => url('/blog/' . $slug),
      ]);
    }
  }
}
