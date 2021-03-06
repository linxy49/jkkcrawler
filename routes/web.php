<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/all', function () {
    return view('all');
});

Route::get( '/news', 'NewsController@index');

Route::get( '/list', 'ListController@index');

Route::get('/docs', function () {
    return view('docs');
});

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::resource('/user', 'UserController');

Route::resource('/item', 'ItemController');

Route::resource('/area', 'AreaController');
