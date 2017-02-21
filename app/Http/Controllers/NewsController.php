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
		Log::debug("NewsController start.");
		$recent = json_decode(Redis::get ( "recent" ));
		Log::debug($recent);
		Log::debug("NewsController end.");
		return view ( 'news', [ 'recent' => [] ] );
	}
}
