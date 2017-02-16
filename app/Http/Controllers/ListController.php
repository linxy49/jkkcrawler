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

		return view ( 'list', [ 'list' => $list ] );
	}
}
