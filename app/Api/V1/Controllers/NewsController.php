<?php

namespace App\Api\V1\Controllers;

use Carbon\Carbon;
use Log;
use Redis;
use Request;

/*
 * User
 */
class NewsController extends BaseController {

	/**
	 * recent building list.
	 *
	 * @param Request $request
	 */
	public function index() {
		$recent = json_decode(Redis::get ( "recent" ));
		$data['recent'] = $recent;
		return response ()->json ( [
				'data' => $data
		] );
	}
}
