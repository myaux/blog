<?php

namespace App\Http\Controllers;


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
}