<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use Redis;

class ListController extends Controller
{
	/**
	 * Show the building list.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		Log::debug("ListController index start");
		$list = json_decode(Redis::get ( "jkk" ));
		Log::debug($list);
		$updated_at = Redis::get ( "updated_at" );
		Log::debug("ListController index end");
		return view ( 'list', [ 'list' => $list, 'updated_at' => $updated_at ] );
	}
}
