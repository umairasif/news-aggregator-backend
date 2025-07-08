<?php

namespace App\Services;

use App\Models\Article;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

abstract class ArticleSource
{
  abstract public function fetchArticles(): void;

  abstract protected function parseArticles($articleCategories);

  /**
   * Store multiple articles at once using batch upsert
   *
   * @param Collection $items
   * @return void
   */
  protected function storeBatchArticles(Collection $items): void
  {
    if ($items->isEmpty()) {
      return;
    }

    $articles = $items->map(function ($item) {
      return [
        'title_slug' => $item['title_slug'],
        'title' => $item['title'],
        'description' => $item['description'] ?? null,
        'image_url' => $item['image_url'] ?? null,
        'url' => $item['url'],
        'source' => $item['source'],
        'category' => $item['category'] ?? null,
        'published_at' => $item['published_at'],
        'author' => $item['author'] ?? null,
        'content' => $item['content'] ?? null,
        'updated_at' => now(),
        'created_at' => now(),
      ];
    })->toArray();

    Article::upsert(
      $articles,
      ['title_slug'],
      [
        'title', 'description', 'image_url', 'url', 'source',
        'category', 'published_at', 'author', 'content', 'updated_at'
      ]
    );
  }

  protected function http(string $base): \Illuminate\Http\Client\PendingRequest
  {
    return Http::baseUrl($base)->throw();
  }


}
