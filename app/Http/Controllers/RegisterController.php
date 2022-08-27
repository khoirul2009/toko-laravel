<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function index()
    {
        return view('register.index', [
            'title' => 'Register'
        ]);
    }
    public function register(Request $request)
    {
        if ($request->ajax()) {
            if (!$request->terms) {
                return response('Please agree privacy policy and terms', 406);
            }
            $validatedData = $request->validate([
                'username'  => 'required|min:6|max:255|unique:users,username',
                'email'     => 'required|max:255|unique:users,email',
                'password'  => 'required|min:6'
            ]);

            $validatedData['password'] = Hash::make($validatedData['password']);
            User::create($validatedData);

            $request->session()->flash('success', 'User Success Created, You Can Login Now !');

            return [
                'success'   => true,
                'message'   => 'Account has been created'
            ];
        }
        abort(404);
    }
}
