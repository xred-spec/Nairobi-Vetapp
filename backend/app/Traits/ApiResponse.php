<?php

namespace App\Traits;

trait ApiResponse
{
    public function Respuesta($data = null, $message = null, $code = 200, $error = null){
        return response()->json([
            'data' => $data,
            'message' => $message,
            'error' => $error
        ], $code);
    }
}
