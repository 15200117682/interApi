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
Route::get('/info', function () {
    phpinfo();
});


Route::post("user/login","User\\UserController@login")->middleware("brush");    //登陆
Route::post("user/reg","User\\UserController@reg");                             //注册
Route::post("user/index","User\\UserController@index");                         //首页
