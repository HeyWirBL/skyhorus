<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class UsersController extends Controller
{
    public function index()
    {
        return Inertia::render('Users/Index', [
            'filters' => Request::all('search', 'role', 'trashed'),
            'users' => User::query()
                ->orderByName()
                ->filter(Request::only('search', 'role', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($user) => [
                    'id' => $user->id,
                    'nombre' => $user->nombre,
                    'apellidos' => $user->apellidos,
                    'usuario' => $user->usuario,
                    'email' => $user->email,
                    'rol' => $user->rol,
                    'deleted_at' => $user->deleted_at,
                ]),    
        ]); 
    }

    public function create()
    {
        return Inertia::render('Users/Create');
    }

    public function store()
    {
        Request::validate([
            'nombre' => 'required|string|max:50',
            'apellidos' => 'required|string|max:100',
            'usuario' => ['required', 'max:50', Rule::unique('users')],
            'email' => ['required', 'string', 'email', 'max:50', Rule::unique('users')],
            'password' => 'required',
            'rol' => 'required',
        ]);

        User::create([
            'nombre' => Request::get('nombre'),
            'apellidos' => Request::get('apellidos'),
            'usuario' => Request::get('usuario'),
            'email' => Request::get('email'),
            'password' => Request::get('password'),
            'rol' => Request::get('rol'),
        ]);

        return redirect(route('users.index'))->with('success', 'Usuario creado.');
    }

    public function edit(User $user)
    {
        return Inertia::render('Users/Edit', [
            'user' => [
                'id' => $user->id,
                'nombre' => $user->nombre,
                'apellidos' => $user->apellidos,
                'usuario' => $user->usuario,
                'email' => $user->email,
                'rol' => $user->rol,
                'deleted_at' => $user->deleted_at,
            ],    
        ]);
    }

    public function update(User $user)
    {
        Request::validate([
            'nombre' => 'required|string|max:50',
            'apellidos' => 'required|string|max:100',
            'usuario' => ['required', 'max:50', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:50', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable'],
            'rol' => 'required',
        ]);

        $user->update(Request::only('nombre', 'apellidos', 'usuario', 'email', 'rol'));

        if (Request::get('password')) {
            $user->update(['password' => Request::get('password')]);
        }

        return Redirect::back()->with('success', 'Usuario actualizado.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return Redirect::back()->with('success', 'Usuario eliminado.');
    }

    public function restore(User $user)
    {
        $user->restore();

        return Redirect::back()->with('success', 'Usuario restablecido.');
    }
}
