<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(){
        Auth::logout();
        // dd(Auth::check(),Auth::user());
        // Auth::login(Auth::user());
        // Auth::logout();
        // dump(session('token') , findEmployeeIdByToken(session('token')),Auth::user());
        // $id = findEmployeeIdByToken(session('token')) ;
        // $token = createDevice($id);
        // dump($token , findEmployeeIdByToken($token));
    }
}