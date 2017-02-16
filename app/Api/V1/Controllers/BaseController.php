<?php

namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;
use Dingo\Api\Routing\Helpers;

class BaseController extends Controller {

	use Helpers;
	/**
	 * building list.
	 *
	 * @param Request $request
	 */
	public function info() {
		echo "version : 1.0";
	}

}
