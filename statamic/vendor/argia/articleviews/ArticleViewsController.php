<?php

namespace Statamic\Addons\ArticleViews;

use Statamic\Extend\Controller;
use Statamic\API\Entry;

class ArticleViewsController extends Controller
{
    /**
     * Maps to your route definition in routes.yaml
     *
     * @return mixed
     */
    public function index()
    {
        dd('test');
        return $this->view('index');
    }

    public function getAddView($id)
    {
        if (Entry::exists($id)) // if entry exists count view.
        {
            $entry_views = EntryViews::firstOrNew(
                ['entry_id' => $id],
                ['views' => 0]
            );

            $entry_views->views++;

            $entry_views->save();

            $data = [
                'entry' => $entry_views
            ];

            return response()->json($data);
        }


        return '';

        // !/ArticleViews/AddView/
    }

    public function getCountViews($id)
    {
        if (Entry::exists($id)) // if entry exists count view.
        {
            $entry_views = EntryViews::firstOrNew(
                ['entry_id' => $id],
                ['views' => 0]
            );

            $data = [
                'entry' => $entry_views,
                'test' => 5
            ];

            return response()->json($data);
        }


        return '';

        // !/ArticleViews/CountViews/
    }

    public function getUrl($id)
    {

        if (Entry::exists($id)) // if entry exists count view.
        {
            $article = Entry::find($id);

            // Get destination url
            $destination_url = $article->get('destination_url');

            // Get Clicks.
            $clicks = $article->get('clicks');

            $clicks++;

            // Set clicks
            $article->set('clicks', $clicks);

            $article->save();

            if ($destination_url) {
                return redirect($destination_url);
            } else {
                return 'no url';
            }
        }
    }
}
