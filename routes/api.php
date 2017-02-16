<?php
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
$api = app ( 'Dingo\Api\Routing\Router' );

/**
 * WebAPI
 *
 * Version 1.0
 */
$api->version ( 'v1', [
		'namespace' => 'App\Api\V1\Controllers'
], function ($api) {
	$api->group ( ['middleware' => ['api'], 'prefix' => 'v1'], function ($api) {
		$api->get ( 'list', 'ListController@index' );
  } );
} );
