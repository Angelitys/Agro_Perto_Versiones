<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register-new');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'type' => ['required', 'string', 'in:producer,consumer'],
            'phone_number' => ['nullable', 'string', 'max:20'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => $request->type,
            'phone_number' => $request->phone_number,
        ]);
        event(new Registered($user));

        Auth::login($user);

        // Redirecionar baseado no tipo de usuário
        if ($user->type === 'producer') {
            return redirect(route('dashboard', absolute: false))
                ->with('success', 'Bem-vindo ao AgroPerto! Sua conta de produtor foi criada com sucesso.');
        } else {
            return redirect(route('home', absolute: false))
                ->with('success', 'Bem-vindo ao AgroPerto! Sua conta foi criada com sucesso.');
        }
    }
}
