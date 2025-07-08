<?php

namespace App\Services\Sources;

use App\Models\Article;
use App\Repositories\ArticleRepository;
use App\Services\ArticleSource;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * NewsApi Service
 *
 * Fetches articles from the NewsApi.org service and stores them in the database.
 */
class NewsApi extends ArticleSource
{
  /**
   * List of categories to fetch articles for
   *
   * @var array<string>
   */
  private array $categories = [
    'entertainment',
    'business',
    'general',
    'health',
    'science',
    'sports',
    'technology'
  ];


  /**
   * Fetch articles from NewsApi and store them in the database
   *
   * @return void
   */
  public function fetchArticles(): void
  {
    $articles = $this->fetchArticlesByCategory();
    $parsedArticles = $this->parseArticles($articles);
    $this->saveArticles($parsedArticles);
  }

  /**
   * Fetch articles from NewsApi by category
   *
   * @return array<string, array>
   */
  private function fetchArticlesByCategory(): array
  {
    $articles = [];
    $configs = config('services.newsapi');
    $params = [
      'apiKey' => $configs['key'],
    ];
    foreach ($this->categories as $category) {
      try {
        $categoryParams = $params;
        $categoryParams['category'] = $category;
        $response = $this->http($configs['base_url'])->get('top-headlines', $categoryParams)->json();
        $articles[$category] = $response['articles'] ?? [];
      } catch (\Exception $e) {
        Log::error("Failed to fetch articles for category {$category}: " . $e->getMessage());
        $articles[$category] = [];
      }
    }

    return $articles;
  }

  /**
   * Parse raw articles data into a standardized format
   *
   * @param array<string, array> $articleCategories
   * @return Collection
   */
  protected function parseArticles($articleCategories): Collection
  {
    $result = collect();

    foreach ($articleCategories as $category => $articles) {
      if (empty($articles)) {
        continue;
      }

      $parsedArticles = collect($articles)->map(function ($article) use ($category) {
        return [
          'title' => $article['title'],
          'title_slug' => Str::slug($article['title']),
          'description' => $article['description'] ?? null,
          'url' => $article['url'],
          'image_url' => $article['urlToImage'] ?? null,
          'source' => ArticleRepository::ARTICLE_SOURCE_NEWS_API,
          'category' => $category,
          'published_at' => Carbon::parse($article['publishedAt']),
          'author' => $article['author'] ?? null,
          'content' => $article['content'] ?? null,
        ];
      });

      $result = $result->merge($parsedArticles);
    }

    return $result;
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
