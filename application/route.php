<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;

Route::get('banner/:id','api/v1.Banner/getBanner');
Route::get('theme/:id','api/v1.Theme/getThemeDetail',[],['id'=>'\d+']);
Route::get('theme','api/v1.Theme/getThemeList');
Route::get('product/:id','api/v1.Product/getProductDetail',[],['id'=>'\d+']);
Route::get('product/category/:id','api/v1.Product/getProductInCategory');
Route::get('product','api/v1.Product/getRecent');
Route::get('category','api/v1.Category/getCategory');
Route::post('token/user','api/v1.Token/getToken');
Route::post('token/verify','api/v1.Token/verifyToken');
Route::post('address','api/v1.Address/createOrUpdate');
Route::get('address/user','api/v1.Address/getAddress');
Route::post('order/:id','api/v1.Order/getOrderDetail',[],['id'=>'\d+']);
Route::post('order/place','api/v1.Order/placeOrder');
Route::post('pay','api/v1.Pay/preOrder');
Route::post('pay/notify','api/v1.Pay/receiveNotify');