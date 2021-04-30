<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\PasswordValidationRules;
use App\Models\User;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class UserController extends Controller
{

    use PasswordValidationRules;

    protected $guard;

    public function __construct(StatefulGuard $guard)
    {
        $this->guard = $guard;
    }

    public function register(Request $request, CreatesNewUsers $creator)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules()
        ]);
        event(new Registered($user = $creator->create($request->all())));
        return ['status' => true, 'message' => 'user created successfully'];
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required'],
        ]);

        $user = User::whereEmail($request->email)->first();
        if (!$user)
            throw ValidationException::withMessages(['email' => 'user not register']);
        if (Hash::check($request->password, $user->password)) {
            $this->guard->login($user);
            return response(['status' => true, 'message' => 'login successfully']);
        }
        throw ValidationException::withMessages(['password' => 'password is incorrect']);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
