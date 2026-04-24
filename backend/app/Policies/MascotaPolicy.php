<?php

namespace App\Policies;

use App\Models\Cita;
use App\Models\Mascota;
use App\Models\User;
use App\Models\Cliente;
use App\Models\TrabajadorHorario;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class MascotaPolicy
{


    public function viewAny(User $user): bool
    {
        return $user->hasRol('cliente')
            || $user->hasRol('trabajador')
            || $user->hasRol('administrador');
    }

    public function viewVisibles(User $user): bool
    {
        if ($user->hasRol('trabajador') || $user->hasRol('administrador')) {
            return true;
        }
        if ($user->hasRol('cliente')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Mascota $mascota): bool
    {
        if ($user->hasRol('cliente') && $mascota->cliente_id === $user->cliente->id) {
            return true;
        }
        if ($user->hasRol('trabajador') || $user->hasRol('administrador')) {
            return true;
        }
        return false;
    }


    public function createCita(User $user, Mascota $mascota, Request $request): bool
    {

        if (($user->hasRol('cliente'))) {
            return true;
        }
        if ($user->hasRol('trabajador') || $user->hasRol('administrador')) {
            return true;
        }
        return false;
    }


    public function update(User $user, Mascota $mascota): bool
    {
        if ($user->hasRol('cliente') && $mascota->cliente_id === $user->cliente->id) {
            return true;
        }
        if ($user->hasRol('trabajador') || $user->hasRol('administrador')) {
            return true;
        }
        return false;
    }


    public function delete(User $user, Mascota $mascota): bool
    {
        if ($user->hasRol('trabajador') || $user->hasRol('administrador')) {
            return true;
        }
        if ($user->hasRol('cliente') && $mascota->cliente_id === $user->cliente->id) {
            return true;
        }
        return false;
    }

    public function create(User $user): bool
    {
        if ($user->hasRol('trabajador') || $user->hasRol('administrador')) {
            return true;
        }

        if ($user->hasRol('cliente') && isset($user->cliente)) {
            return true;
        }

        return false;
    }

    public function switchState(User $user, Mascota $mascota): bool
    {
        if ($user->hasRol('trabajador') || $user->hasRol('administrador')) {
            return true;
        }
        if ($user->hasRol('cliente')) {
            return false;
        }
        return false;
    }
}
