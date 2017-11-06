<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    //发布评论
    public function create(Request $request)
    {
        //表单验证
        $this->validate($request, [
            'content' => 'required|string|min:6',
            'blog_id' =>'required|int'
        ]);

        log::info('msg',[$request->input('content','默认值')]);
//        如果验证通过就向数据库表里写值
        $user = $request->user();
        try {
            $comment = Comment::create(['content' => $request->input('content', '默认值'), 'user_id' => $user->id,'blog_id'=>$request->input('blog_id', '默认值')]);
        } //如果失败获取数据库错误信息
        catch (\Exception $exception) {
            //          return response()->json(['code' => 100, 'msg' => '发布失败']);
            return $exception;
        }
        return response()->json(['code' => 0, 'msg' => '发布成功']);
    }


    //获取评论列表
    public function index(Request $request)
    {
        //表单验证id
        $this->validate($request, [
            'blog_id' => 'required|int'
        ]);
        $comment = Comment::where('blog_id',$request->input('blog_id', '默认值'))->get();
        return response()->json([
            'data' => $comment,
            'msg' => 'succsess',
            'code' => 0,
        ]);
    }

    //修改评论
    public function update(Request $request)
    {
        //表单验证
        $this->validate($request, [
            'content' => 'required|string|min:2|max:50',
            'blog_id' => 'required|int',
            'id' =>'required|int'
        ]);

        log::info('all',[$request->all()]);
//        如果验证通过就向数据库表里写值

        //  取出$request中的blog_id
        $id = $request->input('id');
        log::info('id',[$id]);
        //对comment模型中的属性进行更新（等同于数据库更新）
        $comment = Comment::find($id);
        // 调试一下comment模型是否取到
        log::info('blog',[$comment]);
        //对$request中通过验证的数据进行遍历更新
        try {
            //  这里request使用foreach获取到的是所有的传入值，通过only限制需要取到的值
            foreach ($request->only(['content']) as $key => $value) {
                $comment->$key = $value;
            }
            //保存修改到数据库中
            $comment->save();
        } //如果失败获取数据库错误信息
        catch (\Exception $exception) {
            //          return response()->json(['code' => 100, 'msg' => '发布失败']);
            return $exception;
        }
        return response()->json(['code' => 0, 'msg' => '发布成功']);
    }

}



