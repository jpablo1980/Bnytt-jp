<?php

namespace Statamic\Addons\ArticleViews;

use Statamic\Extend\Tags;
use Statamic\API\Content;
use Statamic\API\Entry;

class ArticleViewsTags extends Tags
{
    /**
     * The {{ article_views }} tag
     *
     * @return string|array
     */
    public function index()
    {
        //
    }

    /**
     * The {{ article_views:count_view }} tag
     *
     * @return string|array
     */
    public function addView()
    {
        $id = $this->getParam('id');

        if (Entry::exists($id)) // if entry exists count view.
        {
            // $entry_views = EntryViews::firstOrNew(
            //     ['entry_id' => $id],
            //     ['views' => 0]
            // );

            // $entry_views->views++;

            // $entry_views->save();
            // TODO - Return Script here.
            $script = <<<EOD
        <script type="application/javascript">
            // Pageload
            function addArticleView() {
                var base_url = '/!/ArticleViews/AddView/';
                var xhr = new XMLHttpRequest();
                xhr.open('GET', base_url + "$id", true);
                xhr.send();
            }
            // Would sometimes be ran twice, added run guard to fix it
            if (typeof articleViewAlreadyAdded != 'undefined' && articleViewAlreadyAdded)
                addArticleView();
            articleViewAlreadyAdded = true;
        </script>
EOD;
            return $script;
        }

        return '';
    }


    /**
     * The {{ article_views:update_counts }} tag
     *
     * @return string
     */
    public function updateCounts()
    {
        return <<<EOD
<script type="application/javascript">
    // Find all article-view tags and fetch new values
    var base_url = '/!/ArticleViews/CountViews/';
    // For each tag
    var tags = document.getElementsByClassName('article-views-tag');
    for (var i = 0; i < tags.length; i++) {
        (function() {
            var tag = tags[i];
            if (tag.dataset.updated) return;
            tag.dataset.updated = true;
            var id = tag.dataset.articleId;
            var xhr = new XMLHttpRequest();
            xhr.open('GET', base_url + id, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState !== 4) return;
                if (xhr.status !== 200) return;
                // Set tag contents to view count
                tag.innerHTML = JSON.parse(xhr.responseText).entry.views;
            };
            xhr.send();
        })();
    }
</script>
EOD;
    }

    /**
     * The {{ article_views:count }} tag
     *
     * @return string|array
     */
    public function count()
    {
        // Get id for entry
        $id = $this->getParam('id');


        if (Entry::exists($id)) // if entry exists return views.
        {
            $entry_views = EntryViews::where('entry_id', $id)->first();

            if ($entry_views) {
                return $entry_views->views;
            } else {
                return 0;
            }
            // Get views by entry id.
            // return views.
        }
    }

    /**
     * The {{ article_views:tag }} tag
     *
     * @return string
     */
    public function tag()
    {
        $views = $this->count();

        // Store id in tag for async views count fetch
        $id = $this->getParam('id');

        return "<span class='article-views-tag' data-article-id='$id'>$views</span>";
    }

    /**
     * The {{ article_views:destination }} tag
     *
     * @return string|array
     */
    public function destination()
    {
        $id = $this->context['id'];

        $article = Entry::find($id);

        return '/!/ArticleViews/url/' . $id;
    }
}
