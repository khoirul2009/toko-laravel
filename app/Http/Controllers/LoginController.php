<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function index()
    {
        return view('login.index', [
            'title' => 'Login'
        ]);
    }
    public function authenticate(LoginRequest $request)
    {
        if ($request->ajax()) {

            $credentials = $request->getCredentials();

            if (!Auth::validate($credentials)) {
                return response('Login failed please check your credentials', 401);
            }

            $user = Auth::getProvider()->retrieveByCredentials($credentials);

            Auth::login($user);

            return response('loggedIn', 200);
        }
        abort(404);
    }
    public function logout()
    {
        Session::flush();

        Auth::logout();

        return redirect('login');
    }
}
