<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Usuario;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('Usuarios/Index', [
            'filters' => Request::all('search', 'role', 'trashed'),
            'usuarios' => Usuario::query()
                ->with('user')
                ->filter(Request::only('search', 'role', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($usuario) => [
                    'idUsuario' => $usuario->idUsuario,
                    'nombre' => $usuario->nombre,
                    'apellidos' => $usuario->apellidos,
                    'rol' => $usuario->rol,
                    'deleted_at' => $usuario->deleted_at,
                    'user' => $usuario->user ? $usuario->user->only('username', 'email') : null,
                ]),    
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Usuarios/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {

        $validated1 = Request::validate([
            'username' => ['required', 'max:50', Rule::unique('users')],
            'email' => ['required', 'max:50', 'email', Rule::unique('users')],
            'password' => 'required',
        ]);

        $user = User::create($validated1);

        if ($user) {
            $validated2 = Request::validate([
                'nombre' => 'required|string|max:50',
                'apellidos' => 'required|string|max:100',
                'telefono' => 'max:50',
                'direccion' => 'max:150',
                'rol' => 'required',
                'user_id' => $user->id,
            ]);

            $usuario = Usuario::create($validated2);

            return $usuario;
        } else {
            return $user;
        }
    
        return Redirect::route('usuarios.index')->with('success', 'Usuario creado.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Chirp  $chirp
     * @return \Illuminate\Http\Response
     */
    public function edit(Chirp $chirp)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Chirp  $chirp
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Chirp $chirp)
    {
        $this->authorize('update', $chirp);

        $validated = $request->validate([
            'message' => 'required|string|max:255',
        ]);

        $chirp->update($validated);

        return Redirect(route('chirps.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Chirp  $chirp
     * @return \Illuminate\Http\Response
     */
    public function destroy(Chirp $chirp)
    {
        $this->authorize('delete', $chirp);

        $chirp->delete();

        return redirect(route('chirps.index'));
    }
}
