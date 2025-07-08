<?php

namespace App\Services\Sources;

use App\Models\Article;
use App\Services\ArticleSource;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Guardian Service
 *
 * Fetches articles from The Guardian API and stores them in the database.
 */
class Guardian extends ArticleSource
{
  /**
   * Default page size for API requests
   *
   * @var int
   */
  private int $pageSize = 20;

  /**
   * Fetch articles from The Guardian and store them in the database
   *
   * @return void
   */
  public function fetchArticles(): void
  {
    $articles = $this->fetchArticlesFromApi();
    $parsedArticles = $this->parseArticles($articles);
    $this->saveArticles($parsedArticles);
  }

  /**
   * Fetch articles from The Guardian API with pagination
   *
   * @return array
   */
  private function fetchArticlesFromApi(): array
  {
    $articles = [];
    $configs = config('services.guardian');
    $params = [
      'api-key' => $configs['key'],
      'page-size' => $this->pageSize,
      'from-date' => Carbon::now()->subDay()->format('Y-m-d'),
      'to-date' => Carbon::now()->format('Y-m-d'),
    ];

    try {
      $page = 1;
      do {
        $params['page'] = $page;
        $response = $this->http($configs['base_url'])->get('search', $params)->json();
        $data = $response['response'];
        $articles = array_merge($articles, $data['results']);
        $currentPage = $data['currentPage'] ?? $page;
        $totalPages = $data['pages'] ?? $currentPage;
        $page++;
      } while ($currentPage < $totalPages);
    } catch (\Exception $e) {
      Log::error("Failed to fetch articles from The Guardian: " . $e->getMessage());
      return [];
    }

    return $articles;
  }

  /**
   * Parse raw articles data into a standardized format
   *
   * @param array $articles
   * @return Collection
   */
  protected function parseArticles($articles): Collection
  {
    return collect($articles)->map(function ($article) {
      return [
        'title' => $article['webTitle'],
        'title_slug' => Str::slug($article['webTitle']),
        'description' => $article['description'] ?? null,
        'url' => $article['webUrl'],
        'image_url' => $article['urlToImage'] ?? null,
        'source' => Article::ARTICLE_SOURCE_GUARDIAN,
        'category' => $article['sectionName'] ?? null,
        'published_at' => Carbon::parse($article['webPublicationDate']),
        'author' => $article['author'] ?? null,
        'content' => $article['content'] ?? null,
      ];
    });
  }

  /**
   * Save articles to the database
   *
   * @param Collection $articles
   * @return void
   */
  private function saveArticles(Collection $articles): void
  {
    $this->storeBatchArticles($articles);
  }
}
