<?php

namespace App\Policies;

use App\Models\Servicios;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ServiciosPolicy
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
    public function view(User $user, Servicios $servicios): bool
    {
        return $user->hasRol('administrador') || $user->hasRol('trabajador');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user -> hasRol('administrador');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Servicios $servicios): bool
    {
       return $user -> hasRol('administrador');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Servicios $servicios): bool
    {
        return $user -> hasRol('administrador');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Servicios $servicios): bool
    {
       return $user -> hasRol('administrador');
    }

     public function CambiarEstado(User $user, Servicios $servicios): bool
    {
       return $user -> hasRol('administrador');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Servicios $servicios): bool
    {
        return false;
    }
}
