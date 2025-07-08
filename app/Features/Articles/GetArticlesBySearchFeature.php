<?php

namespace App\Features\Articles;

use App\Http\Requests\ArticleSearchRequest;
use App\Repositories\Interfaces\ArticleRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class GetArticlesBySearchFeature
{
    /**
     * @var ArticleRepositoryInterface
     */
    private ArticleRepositoryInterface $articleRepository;

    /**
     * GetArticlesBySearchFeature constructor.
     *
     * @param ArticleRepositoryInterface $articleRepository
     */
    public function __construct(ArticleRepositoryInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * Get articles query with search term
     *
     * @param ArticleSearchRequest $request
     * @param string $searchTerm
     * @return Builder
     */
    public function execute(ArticleSearchRequest $request, string $searchTerm): Builder
    {
        return $this->articleRepository->getSearchQuery($request, $searchTerm);
    }
}