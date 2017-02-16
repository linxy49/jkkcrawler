<?php

namespace App\Api\V1\Controllers;

use Carbon\Carbon;
use Log;
use Redis;
use Request;

/*
 * User
 */
class ListController extends BaseController {

	/**
	 * building list.
	 *
	 * @param Request $request
	 */
	public function index() {
		$list = json_decode(Redis::get ( "jkk" ));
		$updated_at = Redis::get ( "updated_at" );
		$data['list'] = $list;
		$data['updated_at'] = $updated_at;
		return response ()->json ( [
				'data' => $data
		] );
	}
}
