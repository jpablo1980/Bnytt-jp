<?php

namespace Statamic\Addons\ArticleViews\Commands;

use Statamic\Extend\Command;
use Statamic\Addons\ArticleViews\EntryViews;
use Statamic\API\Entry;

class SyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'articleviews:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $this->info('Hej!');

        // Get all the views.
        // Sync the views to the collection.

        $views = EntryViews::all();

        foreach($views as $view)
        {

            // If entry exists then sync.
            if(Entry::exists($view->entry_id)) {

                    $entry = Entry::find($view->entry_id);

                    // If views have changed then set and save it.
                    if($entry->get('views') != $view->views)
                    {
                        $entry->set('views', $view->views);
                        $entry->save();

                        $this->info('Entry:' . $entry->get('title'));
                        $this->info('Views:' . $entry->get('views'));
                    }
            }

        }

        
    }
}
