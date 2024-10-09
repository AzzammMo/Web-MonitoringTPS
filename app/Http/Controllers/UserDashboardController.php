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

        // Kembalikan view dengan data TPS
        return view('dashboard.user', compact('tps'));
    }
}
