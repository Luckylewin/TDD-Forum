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
Route::delete('/threads/{channel}/{thread}', 'ThreadsController@destroy');

// 订阅帖子
Route::post('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionsController@store')->middleware('auth');
// 取消订阅
Route::delete('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionsController@destroy')->middleware('auth');

Route::get('/threads/{channel}/{thread}/{replies}', 'RepliesController@index');
Route::post('/threads/{channel}/{thread}/{replies}', 'RepliesController@store');

// 用户个人中心
Route::get('/profiles/{user}', 'ProfilesController@show')->name('profile');
Route::get('/profiles/{user}/notifications', 'UserNotificationsController@index');
Route::delete('/profiles/{user}/notifications/{notification}', 'UserNotificationsController@destroy');


// @某人补齐
Route::get('api/users','Api\UsersController@index');

// 发表回复
Route::post('/threads/{thread}/replies', 'RepliesController@store');
// 更新回复
Route::patch('/replies/{reply}', 'RepliesController@update');
// 删除回复
Route::delete('/replies/{reply}', 'RepliesController@destroy');
// 点赞
Route::post('/replies/{reply}/favorites', 'FavoritesController@store');
// 取消点赞
Route::delete('/replies/{reply}/favorites', 'FavoritesController@destroy');
