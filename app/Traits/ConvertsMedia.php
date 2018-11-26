<?php

namespace App\Traits;

trait ConvertsMedia
{
  /**
   * Set the image sizes for attachments.
   *
   * @return void
   */
  public function registerMediaConversions()
  {
    $this->addMediaConversion('thumb')
      ->setManipulations(['w' => 500, 'h' => 500, 'fit' => 'crop'])
      ->performOnCollections('images');

    $this->addMediaConversion('wide')
      ->setManipulations(['w' => 1300, 'h' => 866, 'fit' => 'crop'])
      ->performOnCollections('images');
  }
}
