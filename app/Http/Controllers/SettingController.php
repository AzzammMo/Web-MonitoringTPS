<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function showAdminPhoneForm()
    {
        $setting = Setting::first(); 
        
        return view('dashboard.admin', compact('setting')); 
    }

    // Method untuk memperbarui nomor WhatsApp
    public function updateAdminPhone(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:15', // Validasi input
        ]);

        $setting = Setting::first() ?? new Setting();
        $setting->whatsapp_number = $request->phone; // Update nomor WhatsApp
        $setting->save(); // Simpan pengaturan

        return response()->json([
            'success' => true,
            'message' => 'Nomor WhatsApp admin berhasil diperbarui!' // Tambahkan pesan
        ]);
    }

    public function getWhatsApp()
    {
        $setting = Setting::first(); // Mengambil pengaturan pertama
        return response()->json(['whatsapp_number' => $setting->whatsapp_number]);
    }
    
}
