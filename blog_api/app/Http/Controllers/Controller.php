<?php
namespace App\Http\Controllers;

abstract class Controller
{
    public function responseFromAPI($status, $code, $data = null, $message = '', $errors = null)
    {
        return response()->json([
            'status'  => $status,
            "message" => $message ?? '',
            'data'    => $data,
            "errors"  => $errors,
        ], 200);
    }

    public function responseUnAuthorizedFromAPI()
    {
        return response()->json([
            'status'  => 'fail',
            "message" => 'UnAuthorized Action',
            'data'    => null,
            "errors"  => null,
        ], 200);
    }
}
