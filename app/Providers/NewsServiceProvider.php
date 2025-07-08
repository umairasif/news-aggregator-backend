<?php

namespace App\Providers;

use App\Repositories\ArticleRepository;
use App\Repositories\Interfaces\ArticleRepositoryInterface;
use App\Services\MultiSourceAggregator;
use App\Services\Sources\Guardian;
use App\Services\Sources\NewsApi;
use App\Services\Sources\NewYorkTimes;
use Illuminate\Support\ServiceProvider;

class NewsServiceProvider extends ServiceProvider
{
  /**
   * Register services.
   */
  public function register(): void
  {
    // bind individual sources
    $this->app->singleton(NewsApi::class);
    $this->app->singleton(Guardian::class);
    $this->app->singleton(NewYorkTimes::class);

    // bind repository
    $this->app->bind(ArticleRepositoryInterface::class, ArticleRepository::class);

    // aggregator with all sources
    $this->app->singleton(MultiSourceAggregator::class, function ($app) {
      return new MultiSourceAggregator([
        $app->make(NewsApi::class),
        $app->make(Guardian::class),
        $app->make(NewYorkTimes::class),
      ]);
    });
  }

  /**
   * Bootstrap services.
   */
  public function boot(): void
  {

  }
}
