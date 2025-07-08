<?php

namespace App\Services;

use Illuminate\Support\Collection;

class MultiSourceAggregator
{
  public function __construct(private array $sources)
  {
  }

  /**
   * Synchronizes all sources by fetching articles from each source.
   */
  public function syncAll()
  {
    return collect($this->sources)->flatMap(function (ArticleSource $s) {
      $s->fetchArticles();
    });
  }
}