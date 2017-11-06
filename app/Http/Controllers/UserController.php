<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function register(Request $request)
    {
        log::info('请求1',[$_POST, $request->all()]);
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
//            return response()->json(['code' => 100, 'msg' => '注册失败']);
            return $this->error_response('注册失败');
        }
//        return response()->json(['code' => 0, 'msg' => '注册成功']);
        return $this->array_response('注册成功');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:6|max:20',
        ]);
        $user = User::where('email', $request->email)->first();
        if (!password_verify($request->password, $user->password))
            return $this->error_response('密码错误');
        else {
            $token = \JWTAuth::fromUser($user);
            return $this->array_response('登陆成功')->withHeaders(['authorization' => 'Bearer ' . $token]);
        }
    }

    public function show(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'data' => $user,
            'msg' => 'succsess',
            'code' => 0,
        ]);
    }

    public function see(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer|exists:users',
        ]);
        $user = User::where('id', $request->id)->first();
        return response()->json([
            'data' => $user,
            'msg' => 'succsess',
            'code' => 0,
        ]);
    }

    // todo 修改用户信息


    public function update(Request $request)
    {
        $this->validate($request, [
            'email' => 'required_without_all:password,name|email|unique:users',
            'password' => 'required_without_all:email,name|string|min:6|max:20',
            'name' => 'required_without_all:email,password|string|min:2|max:20'
        ]);
        log::info('all',[$request->all()]);

        $input = $request->only('email','password','name');
        if ($request->has('password')) {
            $input['password'] = password_hash($input['password'], PASSWORD_DEFAULT);
        }

        $user = $request->user();
        try {
//            $user = User::update(['email' => $request->email,'password' => password_hash($request->password, PASSWORD_DEFAULT),'name' => $request->name,]);
            foreach ($input as $key => $value) {
                $user->$key = $value;
            }
            $user->save();
        } catch (\Exception $exception) {
            log::info('msg',[$exception]);
            return $this->error_response('修改失败');
        }
        return $this->array_response(['user' => $user]);
    }

}



