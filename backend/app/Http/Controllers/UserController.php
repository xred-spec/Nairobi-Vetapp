<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Cliente;
use App\Http\Requests\UserUpdateRequest;
use App\Traits\ApiResponse;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use ApiResponse;
    //editar persona
    public function update(UserUpdateRequest $request)
    {
        $user = User::where('deleted_at', '=', null)->find($request->user()->id);
        if (!$user) {
            return $this->Respuesta(null, 'User no encontrado', 404, null);
        }
        $datosAActualizar = array_filter($request->validated());
        $user->update($datosAActualizar);
        return $this->Respuesta($user, 'User actualizado correctamente', 200, null);
    }
    //soft delete
    public function destroy($id)
    {
        $user = User::where('deleted_at', '=', null)->find($id);
        if (!$user) {
            return $this->Respuesta(null, 'User no encontrado', 404, null);
        }
        $user->delete();
        return $this->Respuesta($user, 'User eliminado correctamente', 200, null);
    }

    public function indexClientes(Request $request)
    {
        try {
            $clientes = Cliente::with('user')
                ->whereHas('user', function ($q) {
                    $q->where('rol_id', 1); 
                })
                ->filter($request->all()) 
                ->paginate(15);

            return $this->Respuesta(
                [
                    'data' => $clientes->items(),
                    'paginacion' => [
                        'current_page' => $clientes->currentPage(),
                        'last_page' => $clientes->lastPage(),
                        'per_page' => $clientes->perPage(),
                        'total' => $clientes->total(),
                    ],
                ],
                'Clientes obtenidos correctamente',
                200
            );
        } catch (Exception $e) {
            return $this->Respuesta(null, 'Error: ' . $e->getMessage(), 500);
        }
    }

    public function fullClientes()
    {
        try {
            $clientes = Cliente::whereHas('user', function ($query) {
                $query->where('estado', 'registrado');
            })->with('user')->get();

            return $this->Respuesta($clientes, 'Clientes registrados obtenidos correctamente', 200, null);
        } catch (Exception $e) {
            return $this->Respuesta(null, 'Error al obtener clientes: ' . $e->getMessage(), 500, null);
        }
    }

    public function showProfile(Request $request)
    {
        try {
            $user = $request->user()->load(['rol', 'cliente']);
            return $this->Respuesta(['data' => $user], 'Perfil obtenido correctamente', 200, null);
        } catch (Exception $e) {
            return $this->Respuesta(null, 'Error al obtener el perfil: ' . $e->getMessage(), 500, null);
        }
    }

    public function updatePassword(Request $request)
    {
        try {
            $request->validate([
                'password_actual' => 'required',
                'password' => 'required|min:8',
            ]);

            $user = $request->user();

            if (!Hash::check($request->password_actual, $user->password)) {
                return $this->Respuesta(null, 'La contraseña actual es incorrecta', 400, null);
            }

            $userToUpdate = clone User::find($user->id);
            $userToUpdate->password = Hash::make($request->password);
            $userToUpdate->save();

            return $this->Respuesta(null, 'Contraseña actualizada con éxito', 200, null);
        } catch (Exception $e) {
            return $this->Respuesta(null, 'Error al actualizar contraseña: ' . $e->getMessage(), 500, null);
        }
    }
}
