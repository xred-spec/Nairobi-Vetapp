<?php

namespace App\Exceptions;

use Exception;

class TokenException extends Exception
{
    public function render($request){
    return response()->json([
        'data'=>null,
        'message'=>'Error en la validacion del token',
        'error'=>$this->getMessage()
    ],403);
    }
}
