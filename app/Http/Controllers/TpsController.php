<?php

namespace App\Http\Controllers;

use App\Models\Tps; // Pastikan model Tps sudah diimport
use Illuminate\Http\Request;

class TpsController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'namaTps' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $tps = new Tps();
        $tps->namaTps = $request->namaTps;
        $tps->alamat = $request->alamat;
        $tps->lat = $request->lat;
        $tps->lng = $request->lng;
        $tps->save();

        return response()->json(['success' => true, 'data' => $tps]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'namaTps' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $tps = Tps::findOrFail($id);
        $tps->namaTps = $request->namaTps;
        $tps->alamat = $request->alamat;
        $tps->lat = $request->lat;
        $tps->lng = $request->lng;
        $tps->save();

        return response()->json(['success' => true, 'data' => $tps]);
    }

    public function destroy($id)
    {
        $tps = Tps::findOrFail($id);
        $tps->delete();

        return response()->json(['success' => true]);
    }
}
