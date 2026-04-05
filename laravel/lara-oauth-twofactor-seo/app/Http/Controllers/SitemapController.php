<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
  /**
   * Return cached XML sitemap.
   */
  public function __invoke(): Response
  {
    $xml = Cache::remember('sitemap.xml', now()->addDay(), function (): string {
      $posts = BlogPost::query()
        ->published()
        ->latest('published_at')
        ->get(['slug', 'updated_at']);

      return response()
        ->view('sitemap.xml', ['posts' => $posts])
        ->getContent();
    });

    return response($xml, 200, ['Content-Type' => 'application/xml']);
  }
}
