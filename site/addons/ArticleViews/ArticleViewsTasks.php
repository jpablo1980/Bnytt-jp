<?php

namespace Statamic\Addons\ArticleViews;

use Statamic\Extend\Tasks;
use Illuminate\Console\Scheduling\Schedule;

class ArticleViewsTasks extends Tasks
{
    /**
     * Define the task schedule
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     */
    public function schedule(Schedule $schedule)
    {
	// TODO - Change this later to every hour to reduce load.
	$schedule->command('articleviews:sync')->dailyAt('23:00')->withoutOverlapping();
    }
}
