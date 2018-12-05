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

Auth::routes();

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/', 'ThreadsController@index');

//帖子
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/threads', 'ThreadsController@index');
Route::get('/threads/create', 'ThreadsController@create');
Route::get('/threads/{channel}', 'ThreadsController@index');
Route::get('/threads/{channel}/{thread}', 'ThreadsController@show');
Route::post('/threads', 'ThreadsController@store');
Route::post('/threads/{channel}/{thread}/{replies}', 'RepliesController@store');

// 用户个人中心
Route::get('/profiles/{user}', 'ProfilesController@show')->name('profile');

//Route::resource('threads', 'ThreadsController');

Route::post('/threads/{thread}/replies', 'RepliesController@store');

Route::post('/replies/{reply}/favorites', 'FavoritesController@store');

