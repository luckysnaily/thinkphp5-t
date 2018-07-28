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

// return [
//     '__pattern__' => [
//         'name' => '\w+',
//     ],
//     '[hello]'     => [
//         ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
//         ':name' => ['index/hello', ['method' => 'post']],
//     ],

// ];


// think从thinkphp/library/目录下面找起
use think\Route;

//后台路由
Route::get('/','admin/index/index'); 	# 首页 
Route::get("left",'admin/index/left'); 
Route::get("top",'admin/index/top');
Route::get("main",'admin/index/main');

Route::post("login",'admin/public/login');
Route::get("login",'admin/public/login');
Route::get("logout",'admin/public/logout');

Route::get('test', 'admin/index/test');


Route::get('index', 'admin/test/index');
Route::get('model', 'admin/test/model');

Route::group('admin', function ()
{
	// 分类相关路由
	Route::get('category/add', 'admin/category/add');
	Route::post('category/add', 'admin/category/add');
	Route::get('category/index', 'admin/category/index');
	Route::get('category/upd', 'admin/category/upd');
	Route::post('category/upd', 'admin/category/upd');
	Route::get('category/ajaxDel', 'admin/category/ajaxDel');


	// 文章相关路由
	Route::get('article/index','admin/article/index');
	Route::get('article/add','admin/article/add');
	Route::post('article/add','admin/article/add');
	Route::get('article/upd','admin/article/upd');
	Route::post('article/upd','admin/article/upd');
	Route::get('article/del','admin/article/del');
	Route::get('article/getContent','admin/article/getContent');
});