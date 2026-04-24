<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Productos;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\AuthorizationException;
class ProductosPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
 
        if($user->hasRol('administrador') || $user->hasRol('trabajador')){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Productos $producto): bool
    {
        return $user->hasRol('administrador') || $user->hasRol('trabajador');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
       // throw new AuthorizationException('El usuario no tiene permisos para ejecutar esta accion',404);
        return $user->hasRol('administrador');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Productos $producto): bool
    {
        return $user->hasRol('administrador');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Productos $producto): bool
    {
        return $user->hasRol('administrador');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Productos $producto): bool
    {
        return $user->hasRol('administrador');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Productos $producto): bool
    {
        return false;
    }
}
