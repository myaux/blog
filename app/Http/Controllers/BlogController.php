<?php

namespace App\Http\Controllers;

use App\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BlogController extends Controller
{
    //发布博客
    public function create(Request $request)
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
            $blog = Blog::create(['title' => $request->title, 'content' => $request->input('content', '默认值'), 'user_id' => $user->id]);
        } //如果失败获取数据库错误信息
        catch (\Exception $exception) {
            //          return response()->json(['code' => 100, 'msg' => '发布失败']);
            return $this->error_response('发布失败');
        }
        return $this->array_response('发布成功');
    }

    //获取博客详细信息
    public function show(Request $request)
    {
        $blog = Blog::where('id', $request->id)->first();
        return $blog;
    }

    //获取博客列表
    public function index(Request $request)
    {
        $blog = Blog::get();
        return $blog;
    }

    //  todo 修改博客
    public function update(Request $request)
    {
        // todo 1 : id值没有经过表单处理
        //表单验证
        $this->validate($request, [
            'title' => 'required_without_all:content|string|min:2|max:50',
            'content' => 'required_without_all:title|string|min:6'
        ]);

        log::info('all',[$request->all()]);
//        如果验证通过就向数据库表里写值


        //  取出$request中的blog_id
        $id = $request->input('id');
        log::info('id',[$request->input('id')]);
        //对user模型中的属性进行更新（等同于数据库更新）
//        $blog = $request->blog();
        $blog = Blog::find($id);
        // todo 2 : 这里不调试一下blog模型取到了没有吗
//        $blog =$request->blog;
        //对$request中通过验证的数据进行遍历更新
        try {
            // todo 3 : 这里request使用foreach获取到的是所有的传入值吗，那传入的id怎么办？
            foreach ($request->only(['title', 'content']) as $key => $value) {
                $blog->$key = $value;
            }
            //保存修改到数据库中
            $blog->save();

        } //如果失败获取数据库错误信息
        catch (\Exception $exception) {
            log::info('msg',[$exception]);
            return $this->error_response('修改失败', 100, [$exception->getMessage(), $exception->getPrevious(), $exception->getTraceAsString()]);
        }
        return $this->array_response('发布成功');
    }

}



