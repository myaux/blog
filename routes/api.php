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
Route::get('test', function () {
    return 'test闭包写法';
});
//数据库
//用户表相关操作
Route::group(['prefix' => 'user'],function (){
    Route::group(['prefix' => 'nativity'],function (){
        Route::post('select',function (){
            $table =DB::select('show tables');
            //DB是一个facade（门面），用于便捷的访问数据库操作对象-类似于指定入口
            //log 日志记录，使用相关函数可以查看框架操作日志
            //重点：select可以执行查询语句（也可以执行其他操作），返回结果对象，且以集合的方式返回
            Log::debug('tables',[$table]);
            return $table;
        });
        Route::post('insert',function (){
            //重点：insert可以执行插入语句，返回操作是否成功
            $created_at =date('Y-m-d H:i:s');
            $email = time().'@qq.com';
            $charu = DB::insert("insert into `users` (`name`,`email`,`password`,`created_at`)
                                           value ('zhai','$email','123456','$created_at')" );
            return $charu?'插入成功':'插入失败';
        });
        Route::post('update',function (){
            //重点：update返回操作受影响行数（被修改了数据的行数，一个数字）
            $xiugai1 = DB::update("update `users` set `password` = ?",['87654321']);
            $xiugai2 = DB::update("update `users` set `name` = :name",['name' => 'new-name']);
            return[
                'xiugai1'=>$xiugai1,
                'xiugai2'=>$xiugai2
            ];
        });
        Route::post('delete', function () {
            // delete 操作返回被删除的行数（数字）
            $xiugai = DB::delete("delete from `users` where name = :name", ['name' => 'new-name']);
            return $xiugai;
        });
//        Route::post('statement', function () {
//            // 用于执行没有返回值的语句
//            DB::statement("drop table `password_resets`");
//        });
//        // 事务操作
//        Route::post('auto-transaction', function () {
//            // 自动事务
//            // 当sql语句操作成功时，自动提交
//            // 失败时，自动回滚（无任何返回值）
//            DB::transaction(function () {
//                DB::update("update `users` set `name` = :name", ['name' => 'new-name']);
//                throw new Exception('手动抛出一个异常');
//                DB::update("update `users` set `name` = :name", ['name' => 'name']);
//            });
//        });
//        Route::post('transaction', function () {
//            // 手动操作事务
//            // 包含 beginTransaction, commit rollBack
//            //可自定义添加返回数据以达到检测事务流程运行状态的目的
//            DB::beginTransaction();
//            try {
//                DB::update("update `users` set `name` = :name", ['name' => 'new-name']);
////                throw new Exception('手动抛出一个异常');
//                DB::commit();
//                return '事务提交了，数据被提交到数据库中了';
//            } catch (Exception $exception) {
//                DB::rollBack();
//                return '事务回滚了，数据操作被取消';
//            }
//        });
        Route::post('bind', function () {
            // 参数绑定
            // 命名绑定
            $xiugai11 = DB::update("update `users` set `name` = :name, `password` = :password", ['name' => 'new-name', 'password' => '111111']);
            // 位置绑定
            $xiugai22 = DB::update("update `users` set `name` = ?, `password` = ?", ['old-name', '222222']);
            return [
                '1' => $xiugai11,
                '2' => $xiugai22
            ];
        });
    });
    Route::group(['prefix' =>'structure'],function (){
        Route::post('get',function (){
            //获取所有符合条件的值
            return DB::table('users')->get();
        });
        Route::post('first',function (){
            //获取符合条件的第一条数据，返回一个对象
            $aaa =DB::table('users')->first();
            return response()->json($aaa);
        });
        Route::post('value',function (){
            //只返回单个值
            return DB::table('users')->value('name');
        });
        Route::post('pluck',function (){
            //查询并返回符合条件的一列数据
            return DB::table('users')->pluck('email');
        });
        Route::post('polymeric',function (){
            //count (统计符合条件数据条数)
            //max(统计符合条件数据里最大值)
            //min
            //sum
            //  return DB::table('users')->count();
            //return DB::table('users')->max('id');
            //return DB::table('users')->min('id');
            return DB::table('users')->sum('id');
        });
        Route::post('select',function (){
            //查询并返回符合条件的一列数据
            return DB::table('users')->pluck('email');
        });
        Route::post('select', function () {
            // select
            // raw 使用原生表达式
            // select * from users
            // select name, email from users
            // 别名: name as real_name
            // select count(id) from users;
            // 通过 raw方法来设定原生sql
//            return DB::table('users')
//                ->select(['name as real_name', 'email'])
//                ->get();
            return DB::table('users')
                ->select(DB::raw('count(id) as count'))
                ->get();
        });
        Route::group(['prefix' => 'where'], function () {
            Route::post('where', function () {
                // where 的使用
                // where($column, $op, $value)
                // where($column, $value)
                // where([])
//                return DB::table('users')
//                    ->where('name', '=', 'zhan')
//                    ->get();
//                return DB::table('users')
//                    ->where('name', 'zhan')
//                    ->get();
                return DB::table('users')
                    ->where([['name', '=', 'zhai'], ['id', '=', '4']])
                    ->get();
            });
            Route::post('orWhere', function () {
                return DB::table('users')
                    ->where('name', 'zhan')
                    ->orWhere('id', 6)
                    ->get();
            });
            Route::post('whereBetween', function () {});
            Route::post('whereIn', function () {
                // whereIn
                // whereNotIn
            });
            Route::post('whereDate', function () {});
            Route::post('whereColumn', function () {});
        });
        Route::group(['prefix' => 'most'], function () {
            Route::post('orderBy', function () {});
            Route::post('inRandomOrder', function () {});
            Route::post('groupBy', function () {});
            Route::post('skip', function () {});
            Route::post('tack', function () {});
        });
        Route::post('insert', function () {
            // insert
            // insetGetId
        });
        Route::post('update', function () {
            // update
            // increment
            // decrement
        });
        Route::post('delete', function () {
            // delete
            // truncate 删除表所有数据
        });
        Route::post('paging', function () {
            // 分组的示例
            // skip tack
        });
    });
});



//博客表相关操作
Route::group(['prefix' => 'blog-model'], function () {
    // 模型插入数据操作
    Route::get('create', function (Request $request) {
        // 如何使用模型来插入一条数据
        // 使用create方法来插入数据，返回一个模型对象
        // 第一种创建
        $input = [
            'user_id' => 99,
            'title' => '博客的标题',
            'content' => '博客的内容',
            'ip' => $request->ip(),
        ];
//
//        $blog = \App\Blog::create($input);
//
//        return response()->json($blog);
        // 第二种插入
        $blog = new \App\Blog($input);
        $blog ->save();
    });
    // 根据主键查询
    Route::get('index/{id}', function ($id) {
        return response()->json(\App\Blog::find($id));
    });
    // 列表查询
    Route::get('show', function () {
        return response()->json(\App\Blog::get());
    });
    // 条件查询, 查询构造器和以前的DB对象一样用
    Route::get('whereShow/{user_id}', function ($user_id) {
        $list = \App\Blog::where('user_id', $user_id)->get();
        foreach ($list as &$item) {
            $item->area = 'hello word';
        }
        return response()->json($list);
    });
    // 修改数据
    Route::post('update/{id}', function (Request $request, $id) {
        if (! $request->has('title')) return response('title未设置', 400);
        $title = $request->input('title');
//        // 获取模型
//        $blog = \App\Blog::find($id);
//        // 修改模型值
//        $blog->title = $title;
//        // 保存结果
//        $blog->save();
//
//        return response()->json(\App\Blog::find($id));
        // 批量修改
        \App\Blog::where('id', 7)->update(['title' => '新的标题']);
    });
    // 删除操作（硬删除）
    Route::delete('delete/{id}', function ($id) {
        // 第一种删除方式
//        return \App\Blog::destroy($id);
        // 第二种删除方式
        $blog = \App\Blog::find($id);
        return response()->json($blog->delete());
    });
});

//评论表相关操作
Route::group(['prefix' => 'comment-model'], function () {
    // 模型插入数据操作
    Route::get('create', function (Request $request) {
        // 如何使用模型来插入一条数据
        // 使用create方法来插入数据，返回一个模型对象
        // 第一种创建
        $input = [
            'user_id' => 11,
            'blog_id' => '博客id',
            'content' => '评价内容',
        ];
//
//        $comment = \App\Comment::create($input);
//
//        return response()->json($comment);
        // 第二种插入
        $comment = new \App\Comment($input);
        $comment ->save();
    });
    // 根据主键查询
    Route::get('index/{id}', function ($id) {
        return response()->json(\App\Comment::find($id));
    });
    // 列表查询
    Route::get('show', function () {
        return response()->json(\App\Comment::get());
    });
    //实际列表查询应该是根据博客id来显示评论
    Route::get('whereList/{blog_id}', function ($blog_id) {
        $list = \App\Comment::where('blog_id', $blog_id)->get();
        return response()->json($list);
    });

    // 条件查询, 查询构造器和以前的DB对象一样用
    Route::get('whereShow/{user_id}', function ($user_id) {
        $list = \App\Comment::where('user_id', $user_id)->get();
        foreach ($list as &$item) {
            $item->area = 'hello word';
        }
        return response()->json($list);
    });
    // 修改数据
    Route::post('update/{id}', function (Request $request, $id) {
        if (! $request->has('content')) return response('content未设置', 400);
        $content = $request->input('content');
//        // 获取模型
//        $comment = \App\Comment::find($id);
//        // 修改模型值
//        $comment->content = $content;
//        // 保存结果
//        $comment->save();
//
//        return response()->json(\App\Comment::find($id));
        // 批量修改
        \App\Comment::where('id', 7)->update(['content' => '新的评论']);
    });
    // 删除操作（硬删除）
    Route::delete('delete/{id}', function ($id) {
        // 第一种删除方式
//        return \App\Blog::destroy($id);
        // 第二种删除方式
        $comment = \App\Comment::find($id);
        return response()->json($comment->delete());
    });
});