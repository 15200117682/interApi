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

Route::post("goods/goodslist","Goods\\GoodsController@goodslist");      //商品的查询
Route::post("goods/goodsdetails","Goods\\GoodsController@goodsdetails");//单个商品的详情查询
Route::post("cart/cartadd","Cart\\CartController@cartadd");//购物车添加
Route::post("cart/cartlist","Cart\\CartController@cartlist");//购物车展示


Route::group(['middleware'=>['brush']],function(){
    Route::post("user/login","User\\UserController@login");    //用户登陆
    Route::post("user/reg","User\\UserController@reg");        //用户注册
    Route::post("user/index","User\\UserController@index");    //项目首页
});         //防刷路由中间件组


Route::get("exem/exemlist","Exem\\ExemController@exemlist");//考试
Route::get("exem/exemadd","Exem\\ExemController@exemadd");//考试

Route::get("ceshi/login","Exem\\ExemController@login");       //签名测试

Route::post("ceshi/goodsadd","OneWeek\\OneWeekController@insert");       //商品添加测试
Route::post("ceshi/goodslist","OneWeek\\OneWeekController@select");       //商品展示测试
Route::post("ceshi/goodsfind","OneWeek\\OneWeekController@goodsfind");       //商品展示测试
Route::post("ceshi/delete","OneWeek\\OneWeekController@delete");       //商品删除测试
Route::post("ceshi/update","OneWeek\\OneWeekController@update");       //商品修改测试


Route::get("ceshi/uploadadd","OneWeek\\OneWeekController@uploadadd");       //商品修改测试
Route::get("ceshi/encrypt","OneWeek\\OneWeekController@encrypt");       //对称加密测试
Route::get("ceshi/Noencrypt","OneWeek\\OneWeekController@Noencrypt");       //非对称加密测试
Route::get("ceshi/shubao","OneWeek\\OneWeekController@shubao");       //数组加密测试
Route::get("ceshi/foreignUrl","OneWeek\\OneWeekController@foreignUrl");       //对外的url加密
Route::get("ceshi/foreignDoUrl","OneWeek\\OneWeekController@foreignDoUrl");       //对称加密对外的url加密

Route::resource('ceshi/posts', 'OneWeek\\RestfulController');       //restful风格单一资源测试

Route::post('ceshi/login', 'OneWeek\\OneWeekController@login');       //测试登陆
Route::post('ceshi/goodshot', 'OneWeek\\OneWeekController@goodshot');       //测试最新商品
Route::post('ceshi/detail/', 'OneWeek\\OneWeekController@detail');       //测试商品详情
Route::post('ceshi/cartcory', 'OneWeek\\OneWeekController@cartcory');       //测试分类
Route::post('ceshi/corygoods', 'OneWeek\\OneWeekController@corygoods');       //测试根据分类查商品

Route::post("weather","FourWeek\\FourWeekController@weather");       //调用天气接口
Route::post("weatherlist","FourWeek\\FourWeekController@weatherlist");       //调用自己的天气接口
Route::get("weatheradd","FourWeek\\FourWeekController@weatheradd");       //调用天气接口


