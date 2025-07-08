<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\ArticleFilterRequest;
use App\Http\Requests\ArticleSearchRequest;
use App\Http\Requests\ArticlePreferencesRequest;
use Illuminate\Database\Eloquent\Builder;

interface ArticleRepositoryInterface
{
  /**
   * Get articles query with filters
   *
   * @param ArticleFilterRequest|ArticleSearchRequest $request
   * @return Builder
   */
  public function getFilteredQuery(ArticleFilterRequest|ArticleSearchRequest $request): Builder;

  /**
   * Get articles query with search term
   *
   * @param ArticleSearchRequest $request
   * @param string $searchTerm
   * @return Builder
   */
  public function getSearchQuery(ArticleSearchRequest $request, string $searchTerm): Builder;

  /**
   * Get articles query based on user preferences
   *
   * @param ArticlePreferencesRequest $request
   * @return Builder
   */
  public function getPreferencesQuery(ArticlePreferencesRequest $request): Builder;

  /**
   * Get all available categories
   *
   * @return \Illuminate\Support\Collection
   */
  public function getCategories();

  /**
   * Get all available sources
   *
   * @return \Illuminate\Support\Collection
   */
  public function getSources();

  /**
   * Get all available authors
   *
   * @return \Illuminate\Support\Collection
   */
  public function getAuthors();
}