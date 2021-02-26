<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Device;

class HomeController extends Controller
{
    public function index(){
        // Auth::logout();
        return view('home.index');
    }
}
