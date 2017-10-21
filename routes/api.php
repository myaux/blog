<?php

use Illuminate\Http\Request;

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
Route::middleware('jwt.auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['prefix' => 'user', 'middleware' => 'api'], function () {
    //注册
    Route::post('register', 'UserController@register');
    //登陆
    Route::post('login', 'UserController@login');
    //获取自己的信息
    Route::group(['middleware' => 'jwt.auth:jwt.refresh'], function () {
        Route::get('show', 'UserController@show');
        // todo 修改用户信息
        Route::post('update', 'UserController@update');
    });
    //获取用户的信息
    Route::post('see', 'UserController@see');
});
Route::group(['prefix' => 'blog', 'middleware' => 'api'], function () {

    //发布前需验证用户是否登陆，发布时写入user_id
    //修改时需验证用户是否登陆，设置具体可修改信息
    //获取时根据博客id显示博客详细信息
    //获取列表，显示所有博客标题，发布时间，设置好每次获取的条数处理分页
    Route::group(['middleware' => 'jwt.auth'], function () {
        Route::post('create', 'BlogController@create');
        // todo 修改博客
        Route::post('update', 'BlogController@update');
    });
    Route::post('show', 'BlogController@show');
    Route::post('index', 'BlogController@index');

});
Route::group(['prefix' => 'comment', 'middleware' => 'api'], function () {
    //发布评论前需验证用户是否登陆，发布时写入user_id，以及blog_id
    //修改时需验证用户是否登陆，设置具体可修改信息
    //获取评论列表，根据blog_id来获取所有评论

    Route::group(['middleware' => 'jwt.auth'], function () {
        Route::post('create', 'CommentController@create');
        // todo 修改评论
        Route::post('update', 'CommentController@update');
    });
    Route::get('index', 'CommentController@index');

});