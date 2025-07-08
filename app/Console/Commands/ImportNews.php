<?php

namespace App\Console\Commands;

use App\Services\MultiSourceAggregator;
use Illuminate\Console\Command;

class ImportNews extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'import:news';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Command description';

  /**
   * Execute the console command.
   */
  public function handle(MultiSourceAggregator $feature)
  {
    $feature->syncAll();
  }
}
