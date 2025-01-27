<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function responseFromAPI($status, $code,  $data = null, $message = '', $errors = null)
    {
        return response()->json([
            'status' => $status,
            "message" => $message ?? '',
            'data' => $data,
            "errors" =>  $errors
        ], 200);
    }
}
