<?php

namespace App\Http\Controllers;


use App\Blog;
use Illuminate\Http\Request;
class BlogController extends Controller
{
    public function info()
    {
        return [
            'route'=>\Route::current(),
            'name'=>\Route::currentRouteName(),
            'action'=>\Route::currentRouteAction()
        ];
    }

    public function path(\Request $request)
    {
        return $request ->path();
    }

    public function url(\Request $request)
    {
        return $request ->url();
    }

    public function fullurl(\Request $request)
    {
        return $request ->fullurl();
    }

    public function method(\Request $request)
    {
        return $request ->method();
    }
    public function isMethod(\Request $request)
    {
        return $request ->isMethod('');
    }

    public function all(\Request $request)
    {
        return $request ->all();
    }

    public function input(\Request $request)
    {
        return $request ->input();
    }

    public function only(\Request $request)
    {
        return $request ->only('');
    }

    public function except(\Request $request)
    {
        return $request ->except('');
    }

    public function has(\Request $request)
    {
        return $request ->has('');
    }



    public function create(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string|min:2|max:50',
            'content' => 'required|string|min:6'
        ]);
        try {
            $user = Blog::create([
                'title' => $request->title,
                'content' => $request-> content,
            ]);
        } catch (\Exception $exception) {
            return response()->json(['code' => 100, 'msg' => '发布失败']);
        }
        return response()->json(['code' => 0, 'msg' => '发布成功']);
    }
}
