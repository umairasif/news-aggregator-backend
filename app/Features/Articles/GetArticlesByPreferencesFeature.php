<?php

namespace App\Features\Articles;

use App\Http\Requests\ArticlePreferencesRequest;
use App\Repositories\Interfaces\ArticleRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class GetArticlesByPreferencesFeature
{
    /**
     * @var ArticleRepositoryInterface
     */
    private ArticleRepositoryInterface $articleRepository;

    /**
     * GetArticlesByPreferencesFeature constructor.
     *
     * @param ArticleRepositoryInterface $articleRepository
     */
    public function __construct(ArticleRepositoryInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * Get articles query based on user preferences
     *
     * @param ArticlePreferencesRequest $request
     * @return Builder
     */
    public function execute(ArticlePreferencesRequest $request): Builder
    {
        return $this->articleRepository->getPreferencesQuery($request);
    }
}