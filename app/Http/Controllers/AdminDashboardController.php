<?php

namespace App\Http\Controllers;

use App\Models\Tps;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        
        $tps = Tps::all(); 
        $tps = \App\Models\Tps::paginate(10); // Menampilkan 10 TPS per halaman
        return view('dashboard.admin', compact('tps')); // Kirim data ke view
    }

    
}
