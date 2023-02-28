<?php

namespace App\Policies;

use App\Models\CromatografiaLiquida;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CromatografiaLiquidaPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CromatografiaLiquida  $cromatografiaLiquida
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, CromatografiaLiquida $cromatografiaLiquida)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return in_array($user->rol, ['Administrador', 'Colaborador', 'Editor']);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user)
    {
        return in_array($user->rol, ['Administrador', 'Editor']);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CromatografiaLiquida  $cromatografiaLiquida
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, CromatografiaLiquida $cromatografiaLiquida)
    {
        return $user->rol === 'Administrador';
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user)
    {
        return $user->rol === 'Administrador';
    }
}
