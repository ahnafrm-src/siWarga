<?php

namespace App\Http\Traits;

trait ApiResponse
{
    public function success($data, string $message){
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ]);
    }

    public function error(string $message){
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => null
        ]);
    }
}