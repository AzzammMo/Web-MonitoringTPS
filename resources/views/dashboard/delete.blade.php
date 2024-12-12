@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="container bg-white p-6">
            <h1 class="text-2xl font-semibold mb-4">Hapus TPS</h1>

            <p>Apakah Anda yakin ingin menghapus TPS <strong>{{ $tps->namaTps }}</strong>?</p>
            <div class="mt-4 flex gap-2">
                <form id="delete-form" action="{{ route('dashboard.tps.destroy', $tps->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" id="delete-button" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md">
                        Ya, Hapus
                    </button>
                </form>
                <a href="{{ route('dashboard.admin') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                    Batal
                </a>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.getElementById('delete-button').addEventListener('click', function (e) {
            Swal.fire({
                title: 'Konfirmasi Penghapusan',
                text: "Apakah Anda yakin ingin menghapus TPS ini? Data yang sudah dihapus tidak dapat dikembalikan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form').submit(); // Kirim form jika konfirmasi
                }
            });
        });
    </script>
@endsection
