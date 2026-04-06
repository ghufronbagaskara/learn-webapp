<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\SeoMeta;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
  public function run(): void
  {
    $pages = [
      [
        'title' => 'Home',
        'slug' => 'home',
        'content' => '<h2>Welcome to Maxian Corp</h2><p>We build digital experiences that help businesses scale with confidence.</p>',
        'order' => 1,
        'seo' => [
          'meta_title' => 'Home | Maxian Corp',
          'meta_description' => 'Corporate website and CMS for Maxian Corp.',
          'meta_keywords' => 'corporate, cms, homepage',
          'og_title' => 'Welcome to Maxian Corp',
          'og_description' => 'Modern corporate web platform powered by Laravel.',
        ],
      ],
      [
        'title' => 'About Us',
        'slug' => 'about',
        'content' => '<h2>Who We Are</h2><p>We are a forward-thinking company focused on high-quality digital products.</p>',
        'order' => 2,
        'seo' => [
          'meta_title' => 'About Us | Maxian Corp',
          'meta_description' => 'Learn about Maxian Corp and our mission.',
          'meta_keywords' => 'about, company, mission, team',
          'og_title' => 'About Maxian Corp',
          'og_description' => 'We build exceptional digital products.',
        ],
      ],
      [
        'title' => 'Services',
        'slug' => 'services',
        'content' => '<h2>Our Services</h2><p>From product strategy to engineering delivery, we support end-to-end transformation.</p>',
        'order' => 3,
        'seo' => [
          'meta_title' => 'Services | Maxian Corp',
          'meta_description' => 'Discover services offered by Maxian Corp.',
          'meta_keywords' => 'services, development, consulting',
          'og_title' => 'Services at Maxian Corp',
          'og_description' => 'Comprehensive digital services for modern businesses.',
        ],
      ],
      [
        'title' => 'Contact',
        'slug' => 'contact',
        'content' => '<h2>Get in Touch</h2><p>Contact our team for collaboration opportunities and project discussions.</p>',
        'order' => 4,
        'seo' => [
          'meta_title' => 'Contact | Maxian Corp',
          'meta_description' => 'Reach out to Maxian Corp.',
          'meta_keywords' => 'contact, consultation, inquiry',
          'og_title' => 'Contact Maxian Corp',
          'og_description' => 'Let us discuss your next digital initiative.',
        ],
      ],
    ];

    foreach ($pages as $item) {
      $page = Page::updateOrCreate([
        'slug' => $item['slug'],
      ], [
        'title' => $item['title'],
        'content' => $item['content'],
        'is_published' => true,
        'order' => $item['order'],
        'created_by' => 1,
      ]);

      SeoMeta::updateOrCreate([
        'seoable_type' => Page::class,
        'seoable_id' => $page->id,
      ], array_merge($item['seo'], [
        'canonical_url' => url('/' . $item['slug']),
      ]));
    }
  }
}
