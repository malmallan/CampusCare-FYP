<?php



namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    
    public function showLogin()
    {
        return view('auth.login');
    }

   
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        
        if (Auth::attempt($credentials, $request->boolean('remember'))) {

            
            $request->session()->regenerate();

            
            return redirect()->intended(route('dashboard'));
        }

        
        return back()
            ->withErrors(['email' => 'These credentials do not match our records.'])
            ->onlyInput('email');
    }

   

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            // unique:users -> emel belum wujud dalam jadual users
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            // confirmed -> mesti sama dengan field password_confirmation
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => $validated['password'],
            
            'role'     => 'student',
        ]);

       

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    // ---------- LOG KELUAR ----------

    public function logout(Request $request)
    {
        Auth::logout();

        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'You have been signed out.');
    }
}
