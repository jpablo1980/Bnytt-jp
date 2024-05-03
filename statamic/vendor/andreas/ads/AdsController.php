<?php

namespace Statamic\Addons\Ads;

use Statamic\Extend\Controller;
use Statamic\API\Entry;
use Statamic\API\Collection;
use Illuminate\Http\Request;

class AdsController extends Controller
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

    public function getAd($id)
    {


        if (Entry::exists($id)) // if entry exists count view.
        {
            $ad = Entry::find($id);

            // Get destination url
            $destination_url = $ad->get('destination_url');

            if ($destination_url) {

                // Get Clicks.
                $clicks = $ad->get('clicks');

                $clicks++;

                // Set clicks
                $ad->set('clicks', $clicks);

                $ad->save();

                return redirect($destination_url);
            } else {
                return abort(404);
            }
        }
    }

    public function getAds(Request $request)
    {
        $ad_array = [];

        if ($request->is('!/ads/ads/region/*')) {


            $region_slug = $request->segment(5,'stockholm');

            if (!$this->cache->exists('ad_region_'.$region_slug)){

                $region = Entry::whereSlug($region_slug, 'region');
                $region_id = $region->id();

                $ads = Entry::whereCollection('ads')->removeUnpublished();

                $ads = $ads->filter(function ($ad) use ($region_id) {
                    if(!$ad->get('regioner')){
                        return false;
                    }
    
                    return in_array($region_id, $ad->get('regioner'));
                });

                $this->cache->put('ad_region_'.$region_slug, $ads,60);

            } else {
                $ads = $this->cache->get('ad_region_'.$region_slug);
            }

        } else {

            $cache ='ad_';

            if (!$this->cache->exists($cache)){
                $ads = Entry::whereCollection('ads')->removeUnpublished();
                $ads = $ads->filter(function ($ad) {
                    return $ad->get('global') == 1;
                });
                $this->cache->put($cache, $ads,60);
            } else {
                $ads = $this->cache->get($cache);
            }
            
        }


        if (!$ads) {
            return abort(404);
        }

        $top_ad = $this->adsRandom('top', $ads, 1);
        $middle_add = $this->adsRandom('middle', $ads, 1);
        $small_middle_ad = $this->adsRandom('small_middle', $ads, 4);
        $news_feed_ad = $this->adsRandom('news_feed', $ads, 1);
        $side_ad = $this->adsRandom('side', $ads, 2);

        $ad_array = [
            'top' => $top_ad,
            'middle' => $middle_add,
            'small_middle' => $small_middle_ad,
            'news_feed' => $news_feed_ad,
            'side' => $side_ad
        ];

        return $ad_array;
    }

    private function adsRandom($typ, $ads, $qty = 1)
    {

        $data = [];
        $new_ads = $ads->filter(function ($ad) use ($typ) {

            $type = $ad->get('typ');
            if ($type == $typ) {
                return true;
            } else {
                return false;
            }
        });

        if($new_ads->count() < 1){
            return [];
        } 
        $ads = $new_ads->random(min($new_ads->count(), $qty));


        if ($qty == 1) {
            $ads = [$ads];
        }
        foreach ($ads as $ad) {
            $ad_data = [
                'id' => $ad->id(),
                'bild' => $ad->get('bild')
            ];
            $data[] = $ad_data;
        }

        return $data;
    }
}
