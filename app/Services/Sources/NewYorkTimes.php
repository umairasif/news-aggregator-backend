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
 * New York Times Service
 *
 * Fetches articles from The New York Times API and stores them in the database.
 */
class NewYorkTimes extends ArticleSource
{
  /**
   * Default page size for API requests
   *
   * @var int
   */
  private int $pageSize = 10;

  /**
   * Sleep time between API requests in microseconds
   *
   * @var int
   */
  private int $requestDelay = 10000000; // 10 seconds

  /**
   * Fetch articles from The New York Times and store them in the database
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
   * Parse raw articles data into a standardized format
   *
   * @param array $articles
   * @return Collection
   */
  protected function parseArticles($articles): Collection
  {
    return collect($articles)->map(function ($article) {
      return [
        'title' => $article['headline']['main'],
        'title_slug' => Str::slug($article['headline']['main']),
        'description' => $article['snippet'] ?? null,
        'url' => $article['web_url'],
        'image_url' => $article['urlToImage'] ?? null,
        'source' => ArticleRepository::ARTICLE_SOURCE_NYT,
        'category' => $article['section_name'] ?? null,
        'published_at' => Carbon::parse($article['pub_date']),
        'author' => $article['author'] ?? null,
        'content' => $article['content'] ?? null,
      ];
    });
  }

  /**
   * Fetch articles from The New York Times API with pagination
   *
   * @return array
   */
  private function fetchArticlesFromApi(): array
  {
    $articles = [];
    $configs = config('services.nyt');
    $params = [
      'api-key' => $configs['key'],
      'begin_date' => Carbon::now()->subDay()->format('Ymd'),
      'end_date' => Carbon::now()->format('Ymd'),
    ];

    try {
      $page = 0;
      do {
        $params['page'] = $page;
        $response = $this->http($configs['base_url'])->get('articlesearch.json', $params)->json();
        $data = $response['response'];
        $metaData = $data['metadata'];

        if ($data['docs']) {
          $articles = array_merge($articles, $data['docs']);
        }

        $currentPage = $page;
        $totalPages = $metaData['hits'] / $this->pageSize ?? $currentPage;
        $totalPages = ceil($totalPages);
        $page++;

        // Add delay to avoid rate limiting
        usleep($this->requestDelay);
      } while ($currentPage < $totalPages);
    } catch (\Exception $e) {
      Log::error("Failed to fetch articles from The New York Times: " . $e->getMessage());
      return [];
    }

    return $articles;
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
