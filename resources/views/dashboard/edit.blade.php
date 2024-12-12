<!-- resources/views/dashboard/edit.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="container bg-white p-6">
            <h1 class="text-2xl font-semibold mb-4">Edit TPS</h1>

            <form action="{{ route('dashboard.tps.update', $tps->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="namaTps" class="block text-sm font-medium text-gray-700">Nama TPS</label>
                    <input type="text" id="namaTps" name="namaTps" value="{{ $tps->namaTps }}" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
                </div>

                <div class="mb-4">
                    <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                    <input type="text" id="alamat" name="alamat" value="{{ $tps->alamat }}" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
                </div>

                <div class="mb-4">
                    <label for="lat" class="block text-sm font-medium text-gray-700">Latitude</label>
                    <input type="number" id="lat" name="lat" value="{{ $tps->lat }}" required step="any" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
                </div>

                <div class="mb-4">
                    <label for="lng" class="block text-sm font-medium text-gray-700">Longitude</label>
                    <input type="number" id="lng" name="lng" value="{{ $tps->lng }}" required step="any" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
                        Update TPS
                    </button>
                    <a href="{{ route('dashboard.tps.index') }}" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
