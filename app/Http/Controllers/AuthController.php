<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showRegistrationForm()
   {
       return view('auth.register');
   }

   public function register(Request $request)
   {
       $this->validate($request, [
           'name' => 'required',
           'email' => 'required|email|unique:users',
           'password' => 'required|min:6|confirmed',
       ]);

       $user = User::create([
           'name' => $request->name,
           'email' => $request->email,
           'password' => bcrypt($request->password),
       ]);

       Auth::login($user);

       return redirect('/home');
   }

   public function showLoginForm()
   {
       return view('auth.login');
   }

   public function login(Request $request)
   {
       $this->validate($request, [
           'email' => 'required|email',
           'password' => 'required',
       ]);

       if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
           return redirect('/home');
       }

       return back()->withErrors([
           'email' => 'The provided credentials do not match our records.',
       ]);
   }

   public function logout()
   {
       Auth::logout();

       return redirect('/');
   }
}
