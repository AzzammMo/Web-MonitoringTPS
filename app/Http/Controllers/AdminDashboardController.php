<?php

namespace App\Http\Controllers;

use App\Models\Tps;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        
        $tps = Tps::all(); // Ambil semua data TPS
        return view('dashboard.admin', compact('tps')); // Kirim data ke view
    }
}
