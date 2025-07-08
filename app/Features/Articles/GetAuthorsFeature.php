<?php

namespace App\Features\Articles;

use App\Repositories\Interfaces\ArticleRepositoryInterface;
use Illuminate\Support\Collection;

class GetAuthorsFeature
{
    /**
     * @var ArticleRepositoryInterface
     */
    private ArticleRepositoryInterface $articleRepository;

    /**
     * GetAuthorsFeature constructor.
     *
     * @param ArticleRepositoryInterface $articleRepository
     */
    public function __construct(ArticleRepositoryInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * Get all available authors
     *
     * @return Collection
     */
    public function execute(): Collection
    {
        return $this->articleRepository->getAuthors();
    }
}
