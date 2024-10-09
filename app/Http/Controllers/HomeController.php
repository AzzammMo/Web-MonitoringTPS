<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tps;

class HomeController extends Controller
{
    public function index()
    {
        $tps = Tps::all();
        
        return view('home', compact('tps'));

    }
}
