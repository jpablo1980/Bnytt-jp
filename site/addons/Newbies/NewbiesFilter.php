<?php

namespace Statamic\Addons\Newbies;

use Statamic\Extend\Filter;
use \Carbon\Carbon;

class NewbiesFilter extends Filter
{
    /**
     * Perform filtering on a collection
     *
     * @return \Illuminate\Support\Collection
     */
    public function filter()
    {
				// Only show news newer than x days.
				return $this->collection->filter(function ($entry) {
						$entry_date = $entry->date()->diffInDays();
						$days = (int) $this->get('days', '90');
						return $entry_date < $days;
        });
    }
}
