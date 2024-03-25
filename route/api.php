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
use think\facade\Route;


Route::post('/v1/posts', 'v1.post/posts')->allowCrossDomain();
Route::get('/v1/posts', 'v1.post/posts')->allowCrossDomain();
Route::get('v1/attr/lists', 'v1.post/posts')->allowCrossDomain();
Route::get('v1/misc/getMediumOptions', 'v1.misc/getMediumOptions')->allowCrossDomain();
Route::get('v1/misc/getCategoryOptions', 'v1.misc/getCategoryOptions')->allowCrossDomain();
Route::get('v1/misc/getAttrOptions', 'v1.misc/getAttrOptions')->allowCrossDomain();
Route::post('v1/post/like', 'v1.post/like')->allowCrossDomain();
Route::post('v1/post/comment', 'v1.post/comment')->allowCrossDomain();

Route::post('/v2/posts', 'v2.post/posts')->allowCrossDomain();
Route::get('/v2/posts', 'v2.post/posts')->allowCrossDomain();
