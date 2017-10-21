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
            'content' => 'required|string|min:6'
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
        $comment = Comment::where('blog_id',$request->input('blog_id', '默认值'))->get();
        return $comment;
    }

    //修改评论
    public function update(Request $request)
    {
        //表单验证
        $this->validate($request, [
            'title' => 'required|string|min:2|max:50',
            'content' => 'required|string|min:6'
        ]);

//        log::info('msg',[$request->input('content','默认值')]);
//        如果验证通过就向数据库表里写值
        $user = $request->user();
        try {
            $blog = Comment::update(['title' => $request->title, 'content' => $request->input('content', '默认值'), 'user_id' => $user->id]);
        } //如果失败获取数据库错误信息
        catch (\Exception $exception) {
            //          return response()->json(['code' => 100, 'msg' => '发布失败']);
            return $exception;
        }
        return response()->json(['code' => 0, 'msg' => '发布成功']);
    }

}



