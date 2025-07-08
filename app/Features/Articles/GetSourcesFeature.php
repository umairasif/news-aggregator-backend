<?php

namespace App\Features\Articles;

use App\Repositories\Interfaces\ArticleRepositoryInterface;
use Illuminate\Support\Collection;

class GetSourcesFeature
{
    /**
     * @var ArticleRepositoryInterface
     */
    private ArticleRepositoryInterface $articleRepository;

    /**
     * GetSourcesFeature constructor.
     *
     * @param ArticleRepositoryInterface $articleRepository
     */
    public function __construct(ArticleRepositoryInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * Get all available sources
     *
     * @return Collection
     */
    public function execute(): Collection
    {
        return $this->articleRepository->getSources();
    }
}
