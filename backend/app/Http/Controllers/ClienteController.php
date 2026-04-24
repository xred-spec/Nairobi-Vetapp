<?php
// TODO
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\clienteUpdateRequest;
use App\Models\Cliente;
use App\Traits\ApiResponse;
use PhpParser\Node\Stmt\TryCatch;



class ClienteController extends Controller
{

    use ApiResponse;
    //editar direcion
    public function update(clienteUpdateRequest $request)
    {
        $cliente = Cliente::where('user_id', $request->user()->id)->first();

        if (!$cliente) {
            return response()->json(['message' => 'No encontrado'], 404);
        }

        // En lugar de all(), vamos a usar only() para asegurarnos de que solo entren strings
        $datos = $request->only(['municipio', 'colonia', 'codigo_postal', 'calle', 'numero_exterior']);

        // Quitamos los nulos o vacíos manualmente
        $datosLimpio = array_filter($datos, function ($valor) {
            return !is_null($valor) && $valor !== '';
        });

        $cliente->update($datosLimpio);

        return response()->json([
            'data' => $cliente,
            'message' => 'Dirección actualizada correctamente'
        ], 200);
    }
}
