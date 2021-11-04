<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Rules\Password;
use Session;
use Hash;
use DB;
// use Log;

use App\Models\NewTenant;
use App\Models\Landlord\User;

class AuthController extends Controller
{
    public function index()
    {
        return view('landlord.auth.login');
    }

    public function signin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        
        if (Auth::guard('landlord')->attempt($credentials)) {
            $request->session()->regenerate();
            
            return redirect()->route('client.dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
            'password' => 'The provided credentials do not match our records.',
        ]);
    }

    public function register()
    {
        return view('landlord.auth.register');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function postRegistration(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:landlord.users'],
            'password' => ['required', 'string', new Password, 'confirmed'],
        ]);

        $client = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        if (Auth::guard('landlord')->attempt(['email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();
            
            return redirect()->route('client.dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
            'password' => 'The provided credentials do not match our records.',
        ]);
    }

    public function dashboard()
    {
        $tenantRecords = NewTenant::orderBy('id','desc')->paginate(10);

        return view('landlord.dashboard', compact('tenantRecords'));
    }

    public function signOut() {
        Session::flush();
        Auth::guard('landlord')->logout();
  
        return redirect()->route('user.login');
    }

}
