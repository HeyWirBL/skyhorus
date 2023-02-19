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
        return Inertia::render('Users/Index', [
            'can' => [
                'createUser' => Auth::user()->can('create', User::class),
                'editUser' => Auth::user()->can('update', User::class),
            ],
            'filters' => $request->all('search', 'role', 'trashed'),
            'users' => $request->user()->query()
                ->orderByName()
                ->filter($request->only('search', 'role', 'trashed'))
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
        $validated = $request->validate([
            'nombre' => 'required|string|max:50',
            'apellidos' => 'required|string|max:100',
            'usuario' => 'required|string|max:50|unique:'.User::class,
            'email' => 'required|string|email|max:50|unique:'.User::class,
            'password' => ['required', Rules\Password::defaults()],
            'telefono' => 'nullable',
            'direccion' => 'nullable',
            'rol' => 'required|string',
        ]);

        $request->user()->create($validated);

        return redirect(route('users'))->with('success', 'Usuario creado.');
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
     * Restore the user's account.
     */
    public function restore(User $user): RedirectResponse
    {
        $user->restore();

        return Redirect::back()->with('success', 'Usuario restablecido.');
    }
}
