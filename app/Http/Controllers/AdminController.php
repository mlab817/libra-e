<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;


class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }


    public function index()
    {
        return view('admin.adminDashboard');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login_form');
    }
}
