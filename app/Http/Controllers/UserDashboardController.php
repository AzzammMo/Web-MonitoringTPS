<?php

namespace App\Http\Controllers;

use App\Models\Tps;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    public function index()
    {
        // Ambil data TPS dari database
        $tps = Tps::all();
        $tps = \App\Models\Tps::paginate(10); 
        return view('dashboard.user', compact('tps'));
    }
}
