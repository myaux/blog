<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|max:20',
            'name' => 'required|string|min:2|max:20'
        ]);
        try {
            $user = User::create([
                'email' => $request->email,
                'password' => password_hash($request->password, PASSWORD_DEFAULT),
                'name' => $request->name,
            ]);
        } catch (\Exception $exception) {
            return response()->json(['code' => 100, 'msg' => '注册失败']);
        }
        return response()->json(['code' => 0, 'msg' => '注册成功']);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:6|max:20',
        ]);
        $user = User::where('email', $request->email)->first();
        if (!password_verify($request->password, $user->password))
            return response()->json(['code' => 100, 'msg' => '密码错误']);
        else {
            $token = \JWTAuth::fromUser($user);
            return response()->json(['code' => 0, 'msg' => '登陆成功'])->withHeaders(['authorization' => 'Bearer ' . $token]);
        }
    }

    public function show(Request $request)
    {
        $user = $request->user();
        return $user;
    }

    public function see(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer|exists:users',
        ]);
           $user = User::where('id', $request->id)->first();
            return $user;
            //todo 没有值的时候，返回“无此用户”
    }
    public function update(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|max:20',
            'name' => 'required|string|min:2|max:20'
        ]);
        try {
            $user = User::update([
                'email' => $request->email,
                'password' => password_hash($request->password, PASSWORD_DEFAULT),
                'name' => $request->name,
            ]);
        } catch (\Exception $exception) {
            return response()->json(['code' => 100, 'msg' => '修改失败']);
        }
        return response()->json(['code' => 0, 'msg' => '修改成功'.$user]);
    }

}



