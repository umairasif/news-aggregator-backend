<?php

namespace App\Repositories;

use App\Http\Requests\ArticleFilterRequest;
use App\Http\Requests\ArticleSearchRequest;
use App\Http\Requests\ArticlePreferencesRequest;
use App\Models\Article;
use App\Repositories\Interfaces\ArticleRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class ArticleRepository implements ArticleRepositoryInterface
{
  /**
   * @var Article
   */
  protected Article $model;

  /**
   * ArticleRepository constructor.
   *
   * @param Article $article
   */
  public function __construct(Article $article)
  {
    $this->model = $article;
  }

  /**
   * Get articles query with filters
   *
   * @param ArticleFilterRequest|ArticleSearchRequest $request
   * @return Builder
   */
  public function getFilteredQuery(ArticleFilterRequest|ArticleSearchRequest $request): Builder
  {
    $query = $this->model->newQuery()->orderBy('published_at', 'desc');

    if ($request->has('from_date')) {
      $query->where('published_at', '>=', $request->input('from_date'));
    }

    if ($request->has('to_date')) {
      $query->where('published_at', '<=', $request->input('to_date'));
    }

    if ($request->has('category')) {
      $query->where('category', $request->input('category'));
    }

    if ($request->has('source')) {
      $query->where('source', $request->input('source'));
    }

    if ($request->has('author')) {
      $query->where('author', $request->input('author'));
    }

    return $query;
  }

  /**
   * Get articles query with search term
   *
   * @param ArticleSearchRequest $request
   * @param string $searchTerm
   * @return Builder
   */
  public function getSearchQuery(ArticleSearchRequest $request, string $searchTerm): Builder
  {
    $query = $this->getFilteredQuery($request);

    $query->where(function ($q) use ($searchTerm) {
      $q->where('title', 'like', "%{$searchTerm}%")
        ->orWhere('description', 'like', "%{$searchTerm}%")
        ->orWhere('content', 'like', "%{$searchTerm}%");
    });

    return $query;
  }

  /**
   * Get articles query based on user preferences
   *
   * @param ArticlePreferencesRequest $request
   * @return Builder
   */
  public function getPreferencesQuery(ArticlePreferencesRequest $request): Builder
  {
    $query = $this->model->newQuery()->orderBy('published_at', 'desc');

    if ($request->has('sources') && !empty($request->input('sources'))) {
      $query->whereIn('source', $request->input('sources'));
    }

    if ($request->has('categories') && !empty($request->input('categories'))) {
      $query->whereIn('category', $request->input('categories'));
    }

    if ($request->has('authors') && !empty($request->input('authors'))) {
      $query->whereIn('author', $request->input('authors'));
    }

    return $query;
  }

  /**
   * Get all available categories
   *
   * @return \Illuminate\Support\Collection
   */
  public function getCategories()
  {
    return $this->model->newQuery()
      ->select('category')
      ->whereNotNull('category')
      ->distinct()
      ->orderBy('category')
      ->pluck('category');
  }

  /**
   * Get all available sources
   *
   * @return \Illuminate\Support\Collection
   */
  public function getSources()
  {
    return $this->model->newQuery()
      ->select('source')
      ->distinct()
      ->orderBy('source')
      ->pluck('source');
  }

  /**
   * Get all available authors
   *
   * @return \Illuminate\Support\Collection
   */
  public function getAuthors()
  {
    return $this->model->newQuery()
      ->select('author')
      ->whereNotNull('author')
      ->distinct()
      ->orderBy('author')
      ->pluck('author');
  }
}