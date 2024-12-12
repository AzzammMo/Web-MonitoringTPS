<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="container bg-gray-200 p-6">
            <div class="flex justify-between items-center">
                <div class="flex-1 text-left">
                    @auth
                        <div class="text-xl font-semibold">
                            {{ __('Selamat datang Admin, ') }}
                            <span class="welcome-message text-uppercase">
                                {{ Auth::user()->name }}
                            </span>
                        </div>
                    @else
                        <div>{{ __("You're not logged in!") }}</div>
                    @endauth
                </div>
                <div class="weather-location-container">
                    <!-- Cuaca Terkini -->
                    <div id="weather-info" class="info-item">
                        <span id="weather-icon"></span>
                        <span id="weather"></span>
                    </div>
                    <!--Lokasi -->
                    <div id="location" class="info-item">
                        <i class="fa fa-map-marker-alt"></i>
                        <span id="location-name"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Map Container -->
    <div class="map-container mx-auto max-w-2xl" style="margin-top: 30px;">
        <div id="map" style="height: 400px; width: 100%; z-index: 0;"></div>
        <!-- Ensured z-index is below modal -->
    </div>

    <div class="flex justify-center my-4">
        <button id="addTpsButton" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
            Tambah TPS
        </button>
    </div>

    <!-- Icon untuk mengatur nomor WhatsApp -->
    <div class="admin-settings mx-auto max-w-2xl mt-4 z-index: 1;">
        <button class="whatsapp-button" onclick="toggleWhatsAppForm()">
            <i class="fas fa-comment-dots" style="font-size: 20px; color: white;"></i>
            <span style="color: white;">Atur Nomor WhatsApp</span>
        </button>

        <!-- Form untuk mengatur nomor WhatsApp -->
        <div id="whatsapp-form"
            style="display: none; background-color: #25D366; padding: 10px; border-radius: 5px; margin-top: 10px; z-index: 1;">
            <input type="text" id="admin-phone" placeholder="Nomor WhatsApp Admin" required
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50">
            <button onclick="updateAdminPhone()" class="bg-green-500 text-white px-4 py-2 rounded">Simpan</button>
        </div>
    </div>

    <!-- Search TPS with Distance Filter -->
    <div id="search-tps" class="overflow-x-auto mx-auto  max-w-2xl mt-4">
        <input type="text" placeholder="Cari TPS..." class="overflow-x-auto mx-auto w-full max-w-2xl mt-4">

        <div id="loading" class="text-center hidden">Sedang memuat...</div> <!-- Elemen loading -->
    </div>

    <div id="tps-table" class="overflow-x-auto mx-auto w-full max-w-3xl mt-3">
        <table class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-4 px-6 text-left border-b border-gray-300 w-1/4">Nama TPS</th>
                    <th class="py-4 px-6 text-left border-b border-gray-300 w-1/4">Alamat</th>
                    <th class="py-4 px-6 text-left border-b border-gray-300 w-1/4">Status</th>
                    <th class="py-4 px-6 text-left border-b border-gray-300 w-1/4">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light" id="tps-list">
                @if ($tps->isEmpty())
                    <tr>
                        <td colspan="4" class="py-4 px-6 text-center">Tidak ada data TPS</td>
                    </tr>
                @else
                    @foreach ($tps as $tpsItem)
                        <tr class="border-b border-gray-300 hover:bg-gray-100 cursor-pointer"
                            data-id="{{ $tpsItem->id }}">
                            <td class="py-4 px-6 border-b border-gray-300">{{ $tpsItem->namaTps }}</td>
                            <td class="py-4 px-6 border-b border-gray-300">{{ $tpsItem->alamat }}</td>
                            <td class="py-4 px-6 border-b border-gray-300">
                                <span
                                    class="status-badge inline-block py-1 px-3 text-xs font-semibold rounded-full
                                    @if ($tpsItem->status == 'tersedia') bg-green-100 text-green-600 border border-green-400
                                    @elseif ($tpsItem->status == 'penuh') 
                                        bg-red-100 text-red-600 border border-red-400
                                    @elseif ($tpsItem->status == 'pemeliharaan') 
                                        bg-yellow-100 text-yellow-600 border border-yellow-400 
                                    @else 
                                        bg-gray-100 text-gray-600 border border-gray-400 @endif">
                                    {{ ucfirst($tpsItem->status) ?? 'Tersedia' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 border-b border-gray-300">
                                <div class="flex items-center gap-2">
                                    <button href="javascript:void(0);"
                                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-md"
                                        onclick="openEditModal({{ $tpsItem->id }}, '{{ $tpsItem->namaTps }}', '{{ $tpsItem->alamat }}', '{{ $tpsItem->latitude }}', '{{ $tpsItem->longitude }}', '{{ $tpsItem->status }}')">
                                        Edit
                                    </button>
                                    <button href="javascript:void(0);"
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md"
                                        onclick="viewCoordinates({{ $tpsItem->lat }}, {{ $tpsItem->lng }}, '{{ $tpsItem->namaTps }}', '{{ $tpsItem->alamat }}', '{{ $tpsItem->status }}')">
                                        Lihat
                                    </button>
                                    <form id="delete-form-{{ $tpsItem->id }}"
                                        action="{{ route('dashboard.delete', $tpsItem->id) }}" method="POST"
                                        class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDelete({{ $tpsItem->id }})"
                                            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        <!-- Pagination -->
        <div class="mt-4">
            {{ $tps->links() }}
        </div>
    </div>
    </div>

    <!-- Modal for Adding TPS -->
    <div id="addTpsModal"
        class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center hidden z-index: 9999"
        style="z-index: 9999;">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
            <h3 class="text-xl font-semibold mb-4">Tambah TPS Baru</h3>
            <form action="{{ route('dashboard.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="namaTps" class="block text-sm font-semibold text-gray-700">Nama TPS</label>
                    <input type="text" name="namaTps" id="namaTps"
                        class="w-full p-2 border border-gray-300 rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="alamat" class="block text-sm font-semibold text-gray-700">Alamat</label>
                    <input type="text" name="alamat" id="alamat"
                        class="w-full p-2 border border-gray-300 rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="lat" class="block text-sm font-semibold text-gray-700">Latitude</label>
                    <input type="numeric" name="lat" id="lat"
                        class="w-full p-2 border border-gray-300 rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="lng" class="block text-sm font-semibold text-gray-700">Longitude</label>
                    <input type="numeric" name="lng" id="lng"
                        class="w-full p-2 border border-gray-300 rounded-md" required>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" id="closeModalButton"
                        class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                        Batal
                    </button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                        Tambah TPS
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal for Editing TPS -->
    <div id="editTpsModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center hidden "
        style="z-index: 9999;">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
            <h3 class="text-xl font-semibold mb-4">Edit TPS</h3>
            <form action="{{ route('dashboard.update', 'id') }}" method="POST" id="editTpsForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="editTpsId">
                <div class="mb-4">
                    <label for="namaTps" class="block text-sm font-semibold text-gray-700">Nama TPS</label>
                    <input type="text" name="namaTps" id="editNamaTps"
                        class="w-full p-2 border border-gray-300 rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="alamat" class="block text-sm font-semibold text-gray-700">Alamat</label>
                    <input type="text" name="alamat" id="editAlamat"
                        class="w-full p-2 border border-gray-300 rounded-md" required>
                </div>

                <div class="mb-4">
                    <label for="status" class="block text-sm font-semibold text-gray-700">Status</label>
                    <select name="status" id="editStatus" class="w-full p-2 border border-gray-300 rounded-md"
                        required>
                        <option value="tersedia">Tersedia</option>
                        <option value="penuh">Penuh</option>
                        <option value="pemeliharaan">Pemeliharaan</option>
                    </select>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" id="closeEditModalButton"
                        class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                        Batal
                    </button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if (session('loginstatus'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Success!',
                    text: '{{ session('loginstatus') }}',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            });
        </script>
    @endif

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Periksa apakah ada session message untuk sukses atau error
            const successMessage = "{{ session('success') }}";
            const errorMessage = "{{ session('error') }}";

            // Menampilkan SweetAlert jika ada pesan sukses
            if (successMessage) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: successMessage,
                    showConfirmButton: true,
                });
            }

            // Menampilkan SweetAlert jika ada pesan error
            if (errorMessage) {
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan!',
                    text: errorMessage,
                    showConfirmButton: true,
                });
            }

            // Add TPS Button click event to show modal
            const addTpsButton = document.getElementById('addTpsButton');
            const addTpsModal = document.getElementById('addTpsModal');
            const closeModalButton = document.getElementById('closeModalButton');

            addTpsButton.addEventListener('click', function() {
                addTpsModal.classList.remove('hidden');
            });

            closeModalButton.addEventListener('click', function() {
                addTpsModal.classList.add('hidden');
            });
        });

        // JavaScript function to open the Edit modal and populate data
        function openEditModal(id, namaTps, alamat, lat, lng, status) {
            // Set form action URL with the correct ID for the update route
            document.getElementById('editTpsForm').action = '/dashboard/update/' + id;

            // Fill in the form fields with the existing data
            document.getElementById('editTpsId').value = id;
            document.getElementById('editNamaTps').value = namaTps;
            document.getElementById('editAlamat').value = alamat;

            document.getElementById('editStatus').value = status;

            // Show the modal
            document.getElementById('editTpsModal').classList.remove('hidden');
        }

        // Close the Edit modal
        document.getElementById('closeEditModalButton').addEventListener('click', function() {
            document.getElementById('editTpsModal').classList.add('hidden');
        });

        // Confirm delete function
        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data TPS ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${id}`).submit();
                }
            });
        }

        // Inisialisasi peta
        const map = L.map('map').setView([-7.2575, 112.7521], 13);

        // Tambahkan tile peta OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // Define custom icon for TPS markers
        const customIcon = L.icon({
            iconUrl: '{{ asset('image/marker.png') }}',
            iconSize: [32, 32],
            iconAnchor: [16, 32],
            popupAnchor: [0, -32]
        });

        // Inisialisasi data TPS dari server
        const tpsData = @json($tps);

        // Menyimpan marker TPS agar bisa dihapus saat filter
        let markers = [];

        // Fungsi untuk menampilkan semua TPS di peta (persebaran)
        function showAllTPS() {
            // Clear previous markers
            markers.forEach(marker => marker.remove());
            markers = [];

            // Menampilkan marker untuk semua TPS
            tpsData.forEach(tpsItem => {
                const tpsLatLng = [tpsItem.lat, tpsItem.lng];
                const statusClass = getStatusClass(tpsItem.status);

                const marker = L.marker(tpsLatLng, {
                        icon: customIcon
                    })
                    .addTo(map)
                    .bindPopup(`
            <b>${tpsItem.namaTps}</b><br>
            ${tpsItem.alamat}<br>
            <span class="inline-block py-1 px-3 text-xs font-semibold rounded-full ${statusClass}">
                ${capitalizeFirstLetter(tpsItem.status)}
            </span>
        `);

                // Simpan marker agar bisa dihapus jika diperlukan
                markers.push({
                    marker,
                    tpsItem
                });
            });
        }

        // Fungsi untuk melihat koordinat TPS di peta
        function viewCoordinates(lat, lng, namaTps, alamat, status) {
            // Arahkan peta ke koordinat yang diberikan
            map.setView([lat, lng], 15); // Zoom level 15 (cukup dekat untuk melihat detail lokasi)

            // Menampilkan marker dengan status yang sesuai
            const statusClass = getStatusClass(status);

            // Hapus marker sebelumnya jika ada
            map.eachLayer(function(layer) {
                if (layer instanceof L.Marker) {
                    map.removeLayer(layer);
                }
            });

            // Tambahkan marker untuk menunjukkan lokasi TPS
            L.marker([lat, lng], {
                    icon: customIcon // Gunakan ikon kustom yang telah didefinisikan
                })
                .addTo(map)
                .bindPopup(`
        <b>${namaTps}</b><br>
        ${alamat}<br>
        <span class="inline-block py-1 px-3 text-xs font-semibold rounded-full ${statusClass}">
            ${capitalizeFirstLetter(status)}
        </span>
    `)
                .openPopup();
        }

        // Fungsi untuk mendapatkan status class
        function getStatusClass(status) {
            switch (status) {
                case 'tersedia':
                    return 'bg-green-100 text-green-600 border border-green-400';
                case 'penuh':
                    return 'bg-red-100 text-red-600 border border-red-400';
                case 'pemeliharaan':
                    return 'bg-yellow-100 text-yellow-600 border border-yellow-400';
                default:
                    return 'bg-gray-100 text-gray-600 border border-gray-400';
            }
        }
        // Capitalize first letter for status
        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('#search-tps input[type="text"]');
            const tpsList = document.querySelector('#tps-list');
            const loadingIndicator = document.getElementById('loading');

            // Tambahkan elemen untuk menampilkan pesan jika data tidak ditemukan
            let noDataMessage = document.createElement('tr');
            noDataMessage.id = 'no-data-message';
            noDataMessage.innerHTML =
                `<td colspan="4" class="py-4 px-6 text-center">Tidak ada data TPS yang sesuai</td>`;
            noDataMessage.style.display = 'none'; // Disembunyikan awalnya
            tpsList.appendChild(noDataMessage);

            // Fungsi untuk memfilter TPS berdasarkan nama
            searchInput.addEventListener('input', function() {
                const searchValue = searchInput.value.toLowerCase().trim();

                // Tampilkan loading indicator
                loadingIndicator.classList.remove('hidden');

                // Simulasi waktu untuk proses filter (opsional)
                setTimeout(() => {
                    const rows = tpsList.querySelectorAll('tr');
                    let hasVisibleRows =
                        false; // Variabel untuk memeriksa apakah ada data yang sesuai

                    // Loop melalui setiap baris TPS
                    rows.forEach(row => {
                        // Abaikan pesan "Tidak ada data TPS"
                        if (row.id === 'no-data-message') return;

                        const tpsNameCell = row.querySelector(
                            'td:first-child'); // Kolom pertama (Nama TPS)
                        if (tpsNameCell) {
                            const tpsName = tpsNameCell.textContent.toLowerCase();
                            // Periksa apakah nama TPS sesuai dengan pencarian
                            if (tpsName.includes(searchValue)) {
                                row.style.display = ''; // Tampilkan baris
                                hasVisibleRows = true;
                            } else {
                                row.style.display = 'none'; // Sembunyikan baris
                            }
                        }
                    });

                    // Tampilkan pesan jika tidak ada data yang sesuai
                    if (!hasVisibleRows) {
                        noDataMessage.style.display = ''; // Tampilkan pesan
                    } else {
                        noDataMessage.style.display = 'none'; // Sembunyikan pesan
                    }

                    // Sembunyikan loading indicator
                    loadingIndicator.classList.add('hidden');
                }, 200); // Tambahkan sedikit delay untuk simulasi
            });
        });

        function toggleWhatsAppForm() {
            const form = document.getElementById('whatsapp-form');
            // Toggle antara menampilkan dan menyembunyikan form
            if (form.style.display === 'none' || form.style.display === '') {
                form.style.display = 'block'; // Menampilkan form
            } else {
                form.style.display = 'none'; // Menyembunyikan form
            }
        }

        // Fungsi untuk memperbarui nomor WhatsApp Admin
        function updateAdminPhone() {
            const adminPhone = document.getElementById('admin-phone').value;

            // Validasi input
            if (!adminPhone) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Nomor telepon tidak boleh kosong!'
                });
                return;
            }

            // Cek apakah input hanya mengandung angka
            if (!/^\d+$/.test(adminPhone)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Nomor telepon hanya boleh mengandung angka!'
                });
                return;
            }

            // Cek panjang input
            if (adminPhone.length > 13) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Nomor telepon tidak boleh lebih dari 13 angka!'
                });
                return;
            }

            // Jika validasi berhasil, lanjutkan dengan panggilan API
            fetch('/api/update-admin-phone', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Jika menggunakan Laravel
                    },
                    body: JSON.stringify({
                        phone: adminPhone
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Nomor WhatsApp Admin berhasil diperbarui!'
                        });
                        document.getElementById('whatsapp-form').reset(); // Reset form setelah menyimpan
                        toggleWhatsAppForm(); // Tutup form
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Gagal memperbarui nomor WhatsApp Admin.'
                        });
                    }
                });
        }

        function getWeather() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        document.getElementById('weather').textContent = 'Memuat Cuaca...';
                        document.getElementById('location-name').textContent = 'Memuat Lokasi...';

                        setTimeout(() => {
                            const lat = position.coords.latitude;
                            const lon = position.coords.longitude;
                            const apiKey = '344abe5a4f56636f35a541a3b0a74554';
                            const weatherUrl =
                                `https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&appid=${apiKey}&units=metric&lang=id`;

                            fetch(weatherUrl)
                                .then(response => response.json())
                                .then(data => {
                                    const weatherElement = document.getElementById('weather');
                                    const weatherIconElement = document.getElementById('weather-icon');
                                    const locationElement = document.getElementById('location-name');
                                    const temperature = data.main.temp;
                                    const weatherDescription = capitalizeFirstLetter(data.weather[0]
                                        .description);
                                    const weatherIcon = data.weather[0].icon;
                                    const cityName = data.name;

                                    locationElement.textContent = cityName;
                                    weatherElement.textContent = `${temperature}°C, ${weatherDescription}`;
                                    weatherIconElement.innerHTML =
                                        `<img src="https://openweathermap.org/img/wn/${weatherIcon}.png" alt="${weatherDescription}" />`;
                                })
                                .catch(error => {
                                    console.error('Error fetching weather data:', error);
                                    document.getElementById('weather').textContent =
                                        'Tidak dapat memuat cuaca';
                                });
                        }, 5000);
                    },
                    function(error) {
                        console.error("Error getting location:", error);
                        document.getElementById('weather').textContent = 'Lokasi tidak ditemukan';
                    }, {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    }
                );
            } else {
                console.error("Geolocation is not supported by this browser.");
                document.getElementById('weather').textContent = 'Lokasi tidak ditemukan';
            }
        }
        getWeather();

        // Fungsi untuk kapitalisasi kata pertama
        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }

        function toggleInfoPopup() {
            const popup = document.getElementById('info-popup');
            popup.classList.toggle('hidden');
        }
    </script>
</x-app-layout>
