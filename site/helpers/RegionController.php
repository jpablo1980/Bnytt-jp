<?php

namespace Statamic\SiteHelpers;

use Statamic\Extend\Controller as AbstractController;

class RegionController extends AbstractController
{
    /**
     * @return mixed
     */
    public function example()
    {
        //
    }

		public function region()
		{

			return 'mooo';
		}


		public function category()
		{
			// code...
			return $this->view('nyheter_region', [
            'title' => 'Karma'
        ]);
		}


		public function news()
		{
			// code...
		}
}
