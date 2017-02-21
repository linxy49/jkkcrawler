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
		$list = json_decode(Redis::get ( "jkk" ));
		$updated_at = Redis::get ( "updated_at" );
		return view ( 'list', [ 'list' => array_reverse($list), 'updated_at' => $updated_at ] );
	}
}
