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

Route::post("goods/goodslist","Goods\\GoodsController@goodslist");

Route::group(['middleware'=>['brush']],function(){
    Route::post("user/login","User\\UserController@login");    //用户登陆
    Route::post("user/reg","User\\UserController@reg");        //用户注册
    Route::post("user/index","User\\UserController@index");    //项目首页
});         //防刷路由中间件组



