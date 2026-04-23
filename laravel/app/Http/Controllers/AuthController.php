<?php

namespace App\Http\Controllers;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('pages.auth.login');
    }

    public function showRegister()
    {
        return view('pages.auth.register');
    }

    public function account()
    {
        return view('pages.auth.account');
    }
}
