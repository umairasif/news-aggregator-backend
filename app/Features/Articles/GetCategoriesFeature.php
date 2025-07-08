<?php

namespace App\Features\Articles;

use App\Repositories\Interfaces\ArticleRepositoryInterface;
use Illuminate\Support\Collection;

class GetCategoriesFeature
{
    /**
     * @var ArticleRepositoryInterface
     */
    private ArticleRepositoryInterface $articleRepository;

    /**
     * GetCategoriesFeature constructor.
     *
     * @param ArticleRepositoryInterface $articleRepository
     */
    public function __construct(ArticleRepositoryInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * Get all available categories
     *
     * @return Collection
     */
    public function execute(): Collection
    {
        return $this->articleRepository->getCategories();
    }
}
