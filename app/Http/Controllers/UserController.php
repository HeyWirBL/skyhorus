<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request): Response
    {
        $filters = $request->only('search', 'role', 'trashed');
        $query = $request->user()->query()->orderByName()->filter($filters);

        $users = $query->paginate(10)->withQueryString()->through(function ($user) {
            return [
                'id' => $user->id,
                'nombre' => $user->nombre,
                'apellidos' => $user->apellidos,
                'usuario' => $user->usuario,
                'email' => $user->email,
                'rol' => $user->rol,
                'deleted_at' => $user->deleted_at,
            ];
        });

        return Inertia::render('Users/Index', [
            'can' => [
                'createUser' => $request->user()->can('create', User::class),
                'editUser' => $request->user()->can('update', User::class),
            ],
            'filters' => $filters,
            'users' => $users,
        ]); 
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): Response
    {
        return Inertia::render('Users/Create');
    }

    /**
     * Store a newly created user in database.
     * 
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([            
            'nombre' => ['required', 'string', 'max:50'],
            'apellidos' => ['required', 'string', 'max:100'],            
            'usuario' => ['required', 'string', 'max:50', 'unique:' . User::class],
            'email' => ['required', 'string', 'email', 'max:50', 'unique:' . User::class],
            'password' => ['required', Rules\Password::defaults()],
            'telefono' => ['nullable', 'string'],
            'direccion' => ['nullable', 'string'],
            'rol' => ['required', 'string'],
        ]);

        $user = User::create($validatedData);

        return redirect()->route('users')->with('success', 'Usuario creado.');
    }

    /**
     * Display the information for specific user.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing an specific user.
     */
    public function edit(User $user): Response
    {
        return Inertia::render('Users/Edit', [
            'user' => [
                'id' => $user->id,
                'nombre' => $user->nombre,
                'apellidos' => $user->apellidos,
                'usuario' => $user->usuario,
                'email' => $user->email,
                'rol' => $user->rol,
                'telefono' => $user->telefono,
                'direccion' => $user->direccion,
                'deleted_at' => $user->deleted_at,
            ],    
        ]);
    }

    /**
     * Update the user's information.
     * 
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'nombre' => ['required', 'string', 'max:50'],
            'apellidos' => ['required', 'string', 'max:100'],
            'usuario' => ['string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'email' => ['email', 'max:50', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', Rules\Password::defaults()],
            'telefono' => ['nullable', 'max:50'],
            'direccion' => ['nullable'],
            'rol' => ['required', 'string'],
        ]);

        $user->update($request->only(
            'nombre', 'apellidos', 'usuario', 'email', 'telefono', 'direccion', 'rol'
        ));

        if ($request->get('password')) {
            $user->update(['password' => $request->get('password')]);
        }

        return Redirect::back()->with('success', 'Usuario actualizado.');
    }

    /**
     * Delete temporary an specific user.
     */
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();
        return Redirect::back()->with('success', 'Usuario eliminado.');
    }

    /**
     * Delete multiple users.
     */
    public function destroyAll(Request $request, User $user): RedirectResponse
    {
        $ids = explode(',', $request->query('ids', ''));
        $user->whereIn('id', $ids)->delete();
        return Redirect::back()->with('success', 'Usuarios eliminados.');
    }

    /**
     * Restore the user's account.
     */
    public function restore(User $user): RedirectResponse
    {
        $user->restore();
        return Redirect::back()->with('success', 'Usuario restablecido.');
    }

    /**
     * Restore mutliple users.
     */
    public function restoreAll(Request $request, User $user): RedirectResponse
    {        
        $ids = explode(',', $request->query('ids', ''));
        $user->whereIn('id', $ids)->restore();       
        return Redirect::back()->with('success', 'Usuarios restablecidos.');
    }
}
