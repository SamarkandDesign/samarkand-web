<?php

namespace App\Presenters;

use Illuminate\Support\HtmlString;

class EventPresenter extends ModelPresenter
{
  public function duration()
  {
    $days = $this->model->start_date->diffInDays($this->model->end_date) + 1;

    return sprintf('%s %s', $days, str_plural('day', $days));
  }

  /**
   * Get the event status.
   *
   * @return string
   */
  public function status()
  {
    if ($this->model->isUnderway()) {
      return 'Underway';
    }

    if ($this->model->hasEnded()) {
      return 'Ended ' . $this->model->end_date->diffForHumans();
    }

    if ($this->model->isUpcoming()) {
      return 'Starts ' . $this->model->start_date->diffForHumans();
    }

    return 'Unknown';
  }

  /**
   * Preset the event's start and end date.
   *
   * @return HtmlString
   */
  public function dates()
  {
    return new HtmlString($this->startDate() . $this->endDate());
  }

  public function startDate()
  {
    return new HtmlString(
      sprintf(
        '<time itemprop="startDate" content="%s">%s</time>',
        $this->model->start_date->toIso8601String(),
        $this->model->start_date->format($this->model->all_day ? 'j M Y' : 'j M Y, H:i')
      )
    );
  }

  public function endDate()
  {
    return new HtmlString(
      sprintf(
        '<time itemprop="endDate" content="%s">%s</time>',
        $this->model->end_date->toIso8601String(),
        $this->model->end_date->format($this->model->all_day ? 'j M Y' : 'j M Y, H:i')
      )
    );
  }

  public function googleCalendarLink()
  {
    $dateFormat = $this->model->all_day ? 'Ymd' : 'Ymd\TH:i:';

    $query = [
      'action' => 'TEMPLATE',
      'text' => str_replace('.', '', $this->model->title),
      'dates' => sprintf(
        '%s/%s',
        $this->model->start_date->format($dateFormat),
        $this->model->end_date->format($dateFormat)
      ),
      'location' => $this->model->venue->toOneLineString(),
    ];

    return sprintf('http://www.google.com/calendar/event?%s', http_build_query($query));
  }
}
