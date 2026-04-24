<?php

namespace App\Policies;

use App\Models\Animales;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AnimalesPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRol('administrador') || $user->hasRol('trabajador');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Animales $animales): bool
    {
        return $user->hasRol('administrador') || $user->hasRol('trabajador');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRol('administrador');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Animales $animales): bool
    {
        return $user->hasRol('administrador');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Animales $animales): bool
    {
        return $user->hasRol('administrador');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Animales $animales): bool
    {
        return $user->hasRol('administrador');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Animales $animales): bool
    {
        return $user->hasRol('administrador');
    }
}