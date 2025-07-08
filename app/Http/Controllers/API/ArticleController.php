<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Features\Articles\BuildArticleQueryFeature;
use App\Features\Articles\GetArticlesBySearchFeature;
use App\Features\Articles\GetArticlesByPreferencesFeature;
use App\Features\Articles\GetCategoriesFeature;
use App\Features\Articles\GetSourcesFeature;
use App\Features\Articles\GetAuthorsFeature;
use App\Http\Requests\ArticleFilterRequest;
use App\Http\Requests\ArticleSearchRequest;
use App\Http\Requests\ArticlePreferencesRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ArticleController extends Controller
{
  /**
   * @var BuildArticleQueryFeature
   */
  private BuildArticleQueryFeature $buildArticleQueryFeature;

  /**
   * @var GetArticlesBySearchFeature
   */
  private GetArticlesBySearchFeature $getArticlesBySearchFeature;

  /**
   * @var GetArticlesByPreferencesFeature
   */
  private GetArticlesByPreferencesFeature $getArticlesByPreferencesFeature;

  /**
   * @var GetCategoriesFeature
   */
  private GetCategoriesFeature $getCategoriesFeature;

  /**
   * @var GetSourcesFeature
   */
  private GetSourcesFeature $getSourcesFeature;

  /**
   * @var GetAuthorsFeature
   */
  private GetAuthorsFeature $getAuthorsFeature;

  /**
   * Constructor
   *
   * @param BuildArticleQueryFeature $buildArticleQueryFeature
   * @param GetArticlesBySearchFeature $getArticlesBySearchFeature
   * @param GetArticlesByPreferencesFeature $getArticlesByPreferencesFeature
   * @param GetCategoriesFeature $getCategoriesFeature
   * @param GetSourcesFeature $getSourcesFeature
   * @param GetAuthorsFeature $getAuthorsFeature
   */
  public function __construct(
    BuildArticleQueryFeature $buildArticleQueryFeature,
    GetArticlesBySearchFeature $getArticlesBySearchFeature,
    GetArticlesByPreferencesFeature $getArticlesByPreferencesFeature,
    GetCategoriesFeature $getCategoriesFeature,
    GetSourcesFeature $getSourcesFeature,
    GetAuthorsFeature $getAuthorsFeature
  )
  {
    $this->buildArticleQueryFeature = $buildArticleQueryFeature;
    $this->getArticlesBySearchFeature = $getArticlesBySearchFeature;
    $this->getArticlesByPreferencesFeature = $getArticlesByPreferencesFeature;
    $this->getCategoriesFeature = $getCategoriesFeature;
    $this->getSourcesFeature = $getSourcesFeature;
    $this->getAuthorsFeature = $getAuthorsFeature;
  }

  /**
   * Get articles with optional filtering
   *
   * @param ArticleFilterRequest $request
   * @return JsonResponse
   */
  public function index(ArticleFilterRequest $request): JsonResponse
  {
    $query = $this->buildArticleQueryFeature->execute($request);

    $perPage = $request->input('per_page', 15);
    $articles = $query->paginate($perPage);

    return response()->json($articles);
  }

  /**
   * Search articles by keyword
   *
   * @param ArticleSearchRequest $request
   * @return JsonResponse
   */
  public function search(ArticleSearchRequest $request): JsonResponse
  {
    $searchTerm = $request->input('q');
    $query = $this->getArticlesBySearchFeature->execute($request, $searchTerm);

    $perPage = $request->input('per_page', 15);
    $articles = $query->paginate($perPage);

    return response()->json($articles);
  }

  /**
   * Get articles based on user preferences
   *
   * @param ArticlePreferencesRequest $request
   * @return JsonResponse
   */
  public function getByPreferences(ArticlePreferencesRequest $request): JsonResponse
  {
    $query = $this->getArticlesByPreferencesFeature->execute($request);

    $perPage = $request->input('per_page', 15);
    $articles = $query->paginate($perPage);

    return response()->json($articles);
  }

  /**
   * Get all available categories
   *
   * @return JsonResponse
   */
  public function getCategories(): JsonResponse
  {
    $categories = $this->getCategoriesFeature->execute();

    return response()->json($categories);
  }

  /**
   * Get all available sources
   *
   * @return JsonResponse
   */
  public function getSources(): JsonResponse
  {
    $sources = $this->getSourcesFeature->execute();

    return response()->json($sources);
  }

  /**
   * Get all available authors
   *
   * @return JsonResponse
   */
  public function getAuthors(): JsonResponse
  {
    $authors = $this->getAuthorsFeature->execute();

    return response()->json($authors);
  }
}
