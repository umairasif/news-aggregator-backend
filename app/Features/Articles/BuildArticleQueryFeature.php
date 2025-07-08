<?php

namespace App\Features\Articles;

use App\Http\Requests\ArticleSearchRequest;
use App\Http\Requests\ArticleFilterRequest;
use App\Repositories\Interfaces\ArticleRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class BuildArticleQueryFeature
{
  /**
   * @var ArticleRepositoryInterface
   */
  private ArticleRepositoryInterface $articleRepository;

  /**
   * BuildArticleQueryFeature constructor.
   *
   * @param ArticleRepositoryInterface $articleRepository
   */
  public function __construct(ArticleRepositoryInterface $articleRepository)
  {
    $this->articleRepository = $articleRepository;
  }

  /**
   * Build article query with common filters
   *
   * @param ArticleSearchRequest|ArticleFilterRequest $request
   * @return Builder
   */
  public function execute(ArticleSearchRequest|ArticleFilterRequest $request): Builder
  {
    return $this->articleRepository->getFilteredQuery($request);
  }
}
