<?php

namespace App\Jobs;

use App\Page;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateChildPagePaths extends Job implements ShouldQueue
{
  use InteractsWithQueue, SerializesModels;

  private $page;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct(Page $page)
  {
    $this->page = $page;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    $this->page->getDescendants()->map(function ($page) {
      $page->path = $page->getPath();
      $page->save();
    });
  }
}
