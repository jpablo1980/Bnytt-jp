<?php

namespace Statamic\Addons\ArticleViews;

use Illuminate\Database\Eloquent\Model;

class EntryViews extends Model
{
    //
    protected $table = 'entry_views';

    protected $fillable = ['entry_id','views'];
}
