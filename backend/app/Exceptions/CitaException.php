<?php

namespace App\Exceptions;

use Exception;

class CitaException extends Exception
{

    public function render($request){
    return response()->json([
        'data'=>null,
        'message'=>'Error en la modificacion de la cita',
        'error'=>$this->getMessage()
    ],403);
    }
}
