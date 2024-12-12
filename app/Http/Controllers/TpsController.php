<?php

namespace App\Http\Controllers;

use App\Models\Tps;
use Illuminate\Http\Request;

class TpsController extends Controller
{
    public function store(Request $request)
    {
        // dd($request->all()); 
        // Validasi input
        $request->validate([
            'namaTps' => 'required|string|max:255|unique:tps,namaTps',
            'alamat' => 'required|string|max:255',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'status' => 'nullable|in:tersedia,penuh,pemeliharaan', // Status boleh kosong, kalau kosong akan diatur ke 'tersedia'
        ]);
    
        // Buat TPS baru dan set status default ke 'tersedia' jika tidak ada status yang dipilih
        $tps = Tps::create([
            'namaTps' => $request->namaTps,
            'alamat' => $request->alamat,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'status' => $request->status ?? 'tersedia', // Jika status tidak ada, 'tersedia' jadi default
        ]);
    
        // Redirect ke halaman dashboard admin dengan pesan sukses
        return redirect()->route('dashboard.admin')->with('success', 'TPS berhasil ditambahkan.');
    }
    
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'namaTps' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'status' => 'required|string|in:tersedia,penuh,pemeliharaan',
        ]);

        $tps = Tps::findOrFail($id);
        $tps->update([
            'namaTps' => $request->namaTps,
            'alamat' => $request->alamat,
            'status' => $request->status,
        ]);

        // Redirect ke halaman dashboard dengan pesan sukses
        return redirect()->route('dashboard.admin')->with('success', 'TPS berhasil diperbarui.');
    }

    // Method untuk menghapus TPS
    public function destroy($id)
    {
        $tps = Tps::find($id);

        if (!$tps) {
            return redirect()->route('dashboard.admin')->with('error', 'TPS tidak ditemukan.');
        }

        $tps->delete();

        return redirect()->route('dashboard.admin')->with('success', 'TPS berhasil dihapus.');
    }

        public function edit($id)
    {
        $tps = Tps::findOrFail($id); // Menemukan satu TPS berdasarkan ID
        return view('edit-tps', compact('tps')); // Kirim data TPS tunggal ke view
    }

}
