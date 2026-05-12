<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        $loginField = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([
            $loginField => $credentials['login'],
            'password' => $credentials['password'],
            'is_active' => 1,
        ], $remember)) {
            $request->session()->regenerate();

            $user = $request->user()->load('roles');

            if ($user->must_change_password) {
                return redirect()->route('student.password.edit');
            }

            if ($user->roles->contains('name', 'student')) {
                return redirect()->intended(route('student.dashboard'));
            }

            return redirect()->route('admin.dashboard');

        }

        return back()
            ->withErrors([
                'login' => 'The provided credentials are incorrect or the account is inactive.',
            ])
            ->onlyInput('login');
    }

    protected function redirectByRole($user)
    {
        // Priority-based role routing
        $priority = [
            'super_admin' => 'admin.dashboard',
            'admin'       => 'admin.dashboard',
            'hr'          => 'admin.dashboard',
            'registrar'   => 'registrar.dashboard',
            'teacher'     => 'teacher.dashboard',
        ];

        foreach ($priority as $role => $route) {
            if ($user->hasRole($role)) {
                return route($route);
            }
        }

        abort(403, 'No dashboard assigned.');
    }
}