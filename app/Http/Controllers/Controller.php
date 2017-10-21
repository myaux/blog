<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function array_response($data = [], $msg = 'success', $code = 0, $error_data = [])
    {
        return response()->json([
            'data' => $data,
            'msg' => $msg,
            'code' => $code,
            'error_date' => $error_data
        ]);
    }

    public function error_response($msg = '', $code = 100, $error_data = [])
    {
        return $this->array_response([], $msg, $code, $error_data);
    }

    public function no_content($msg = '', $code = 0)
    {
        return $this->array_response([], $msg, $code);
    }
}
