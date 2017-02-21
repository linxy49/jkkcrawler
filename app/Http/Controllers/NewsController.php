<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use Redis;

class NewsController extends Controller
{
	/**
	 * Show the recent building list.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$recent = json_decode(Redis::get ( "recent" ));
		return view ( 'news', [ 'recent' => [] ] );
	}
}
