<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="container">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center">
                    <div class="flex-1 text-left">
                        @auth
                            <div style="font-size: 1.6rem; font-weight: bold;"> 
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
                        <!-- Jam Realtime -->
                        <div id="current-time" class="info-item">
                            <i class="fa fa-clock"></i>
                            <span id="time"></span>
                        </div>
                        <!-- Cuaca Terkini -->
                        <div id="weather-info" class="info-item">
                            <span id="weather-icon"></span>
                            <span id="weather"></span>
                        </div>
                        <!-- Nama Lokasi -->
                        <div id="location" class="info-item">
                            <i class="fa fa-map-marker-alt"></i>
                            <span id="location-name"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Icon untuk mengatur nomor WhatsApp -->
    <div class="admin-settings mx-auto max-w-2xl mt-4">
        <button class="whatsapp-button" onclick="toggleWhatsAppForm()">
            <i class="fas fa-comment-dots" style="font-size: 30px; color: white;"></i>
            <span style="color: white;">Atur Nomor WhatsApp</span>
        </button>

        <!-- Form untuk mengatur nomor WhatsApp -->
        <div id="whatsapp-form"
            style="display: none; background-color: #25D366; padding: 10px; border-radius: 5px; margin-top: 10px;">
            <input type="text" id="admin-phone" placeholder="Nomor WhatsApp Admin" required
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50">
            <button onclick="updateAdminPhone()" class="bg-green-500 text-white px-4 py-2 rounded">Simpan</button>
        </div>
    </div>

    <!-- Map Container -->
    <div class="map-container mx-auto max-w-2xl">
        <div id="map" style="height: 400px; width: 100%;"></div>
    </div>

    <!-- Button to Open Add TPS Form -->
    <div class="button-container mx-auto max-w-2xl mt-4">
        <button id="open-add-tps" class="bg-green-500 text-white px-4 py-2 rounded">
            <i class="fas fa-plus"></i> Tambah TPS
        </button>
    </div>

    <!-- Form for Adding/Updating TPS -->
    <div id="add-tps-form-container" class="mx-auto max-w-2xl mt-4 hidden">
        <form id="add-tps-form">
            <div class="mb-4">
                <label for="namaTps" class="block text-sm font-medium text-gray-700">Nama TPS</label>
                <input type="text" id="namaTps" required
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50">
            </div>
            <div class="mb-4">
                <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                <input type="text" id="alamat" required
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50">
            </div>
            <div class="mb-4">
                <label for="lat" class="block text-sm font-medium text-gray-700">Latitude</label>
                <input type="number" id="lat" required step="any"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50">
            </div>
            <div class="mb-4">
                <label for="lng" class="block text-sm font-medium text-gray-700">Longitude</label>
                <input type="number" id="lng" required step="any"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50">
            </div>
            <div class="button-group">
                <button type="submit" class="btn-submit bg-green-500 text-white px-4 py-2 rounded">Tambah TPS</button>
                <button type="button" id="close-add-tps"
                    class="btn-cancel bg-red-500 text-white px-4 py-2 rounded">Batal</button>
            </div>
        </form>
    </div>

    <!-- Search TPS -->
    <div class="overflow-x-auto mx-auto w-full max-w-2xl mt-4">
        <input type="text" id="search-tps" placeholder="Cari TPS..."
            class="border border-gray-300 rounded p-2 mb-4 w-full" oninput="searchTPS()" />
        <div class="overflow-x-auto mx-auto w-full max-w-2xl mt-4">
            <label for="distance-filter" class="block mb-2">Pilih Jarak</label>
            <select id="distance-filter" class="border border-gray-300 rounded p-2 mb-4 w-full">
                <option value="0">Semua</option>
                <option value="200">200 m</option>
                <option value="500">500 m</option>
                <option value="1000">1 km</option>
                <option value="2000">2 km</option>
            </select>
            <div id="loading" class="text-center hidden">Sedang memuat...</div>
            <table class="min-w-full bg-white border">
                <thead>
                    <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-4 text-left border-b">Nama TPS</th>
                        <th class="py-3 px-4 text-left border-b">Alamat</th>
                        <th class="py-3 px-4 text-left border-b">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light" id="tps-list">
                    @if ($tps->isEmpty())
                        <tr>
                            <td colspan="3" class="py-3 px-4 text-center">Tidak ada data TPS</td>
                        </tr>
                    @else
                        @foreach ($tps as $tpsItem)
                            <tr class="border-b border-gray-200 hover:bg-gray-100" data-id="{{ $tpsItem->id }}">
                                <td class="py-3 px-4">{{ $tpsItem->namaTps }}</td>
                                <td class="py-3 px-4">{{ $tpsItem->alamat }}</td>
                                <td class="py-3 px-4">
                                    <div class="table-action-buttons flex items-center gap-2">
                                        <button class="view-button" data-lat="{{ $tpsItem->lat }}"
                                            data-lng="{{ $tpsItem->lng }}">
                                            Lihat
                                        </button>
                                        <button class="edit-button" data-id="{{ $tpsItem->id }}"
                                            data-nama="{{ $tpsItem->namaTps }}" data-alamat="{{ $tpsItem->alamat }}"
                                            data-lat="{{ $tpsItem->lat }}" data-lng="{{ $tpsItem->lng }}">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <button class="delete-button" data-id="{{ $tpsItem->id }}">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
            <div id="no-data" class="py-3 text-center border border-gray-200 rounded hidden">Data TPS tidak
                ditemukan
            </div>
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

    <!-- Leaflet JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Initialize Leaflet map
        const map = L.map('map').setView([-7.2575, 112.7521], 13);

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // Array of TPS data from the server (passed from controller to view)
        let tpsData = @json($tps);
        let editingId = null; // Variable to track which TPS is being edited

        const markerIcon = L.icon({
            iconUrl: '{{ asset('image/marker.png') }}', // Gambar marker
            iconSize: [32, 32],
            iconAnchor: [16, 32],
            popupAnchor: [0, -32]
        });

        // Function to update markers on the map
        function updateMarkers() {
            // Clear existing markers
            map.eachLayer(function(layer) {
                if (layer instanceof L.Marker) {
                    map.removeLayer(layer);
                }
            });

            // Loop through each TPS and add marker to the map
            tpsData.forEach(tps => {
                L.marker([tps.lat, tps.lng], {
                        icon: markerIcon
                    })
                    .addTo(map)
                    .bindPopup(`<strong>${tps.namaTps}</strong><br>${tps.alamat}`);
            });
        }

        // Function to render TPS list
        function renderTPSList() {
            const tpsList = document.getElementById('tps-list');
            tpsList.innerHTML = '';
            tpsData.forEach(tps => {
                tpsList.innerHTML += `
                    <tr class="border-b border-gray-200 hover:bg-gray-100" data-id="${tps.id}">
                        <td class="py-3 px-4">${tps.namaTps}</td>
                        <td class="py-3 px-4">${tps.alamat}</td>
                        <td class="py-3 px-4">
                            <div class="table-action-buttons flex items-center gap-2">
                                <button class="view-button" data-lat="${tps.lat}" data-lng="${tps.lng}" onclick="viewTPS(${tps.lat}, ${tps.lng})">Lihat</button>
                                <button class="edit-button" data-id="${tps.id}" data-nama="${tps.namaTps}" data-alamat="${tps.alamat}" data-lat="${tps.lat}" data-lng="${tps.lng}" onclick="openEditForm(${tps.id})">Edit</button>
                                <button class="delete-button" data-id="${tps.id}" onclick="deleteTPS(${tps.id})">Hapus</button>
                            </div>
                        </td>
                    </tr>
                `;
            });
        }

        // Function to view TPS on the map
        function viewTPS(lat, lng) {
            map.setView([lat, lng], 16); // Zoom in on the selected TPS
        }

        // Function to toggle WhatsApp form visibility
        function toggleWhatsAppForm() {
            const form = document.getElementById('whatsapp-form');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }

        let originalTpsData = [...tpsData]; // Simpan data TPS asli untuk pencarian
        const searchInput = document.getElementById('search-tps');
        const tpsList = document.getElementById('tps-list');
        const loadingElement = document.getElementById('loading');
        const noDataElement = document.getElementById('no-data');

        // Fungsi untuk menampilkan TPS awal saat halaman dimuat
        function renderTPSList(data = tpsData) {
            tpsList.innerHTML = '';
            if (data.length === 0) {
                noDataElement.classList.remove('hidden');
            } else {
                noDataElement.classList.add('hidden');
                data.forEach(tps => {
                    tpsList.innerHTML += `
                    <tr class="border-b border-gray-200 hover:bg-gray-100" data-id="${tps.id}">
                        <td class="py-3 px-4">${tps.namaTps}</td>
                        <td class="py-3 px-4">${tps.alamat}</td>
                        <td class="py-3 px-4">
                            <div class="table-action-buttons flex items-center gap-2">
                                <button class="view-button" data-lat="${tps.lat}" data-lng="${tps.lng}" onclick="viewTPS(${tps.lat}, ${tps.lng})">Lihat</button>
                                <button class="edit-button" data-id="${tps.id}" data-nama="${tps.namaTps}" data-alamat="${tps.alamat}" data-lat="${tps.lat}" data-lng="${tps.lng}" onclick="openEditForm(${tps.id})">Edit</button>
                                <button class="delete-button" data-id="${tps.id}" onclick="deleteTPS(${tps.id})">Hapus</button>
                            </div>
                        </td>
                    </tr>
                `;
                });
            }
        }

        // Fungsi untuk melakukan pencarian TPS
        function searchTPS() {
            const searchTerm = searchInput.value.trim().toLowerCase();

            // Tampilkan elemen loading saat pencarian dimulai
            loadingElement.classList.remove('hidden');

            setTimeout(() => {
                // Filter data TPS berdasarkan pencarian
                const filteredTps = originalTpsData.filter(tps =>
                    tps.namaTps.toLowerCase().includes(searchTerm) ||
                    tps.alamat.toLowerCase().includes(searchTerm)
                );

                // Sembunyikan elemen loading setelah pencarian selesai
                loadingElement.classList.add('hidden');

                // Render data TPS yang sesuai dengan pencarian
                renderTPSList(filteredTps);
            }, 500); // Simulasi delay pencarian
        }

        function updateAdminPhone() {
            const adminPhone = document.getElementById('admin-phone').value;

            // Validation
            if (!adminPhone) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Nomor telepon tidak boleh kosong!'
                });
                return;
            }

            // Check if the input contains only digits
            if (!/^\d+$/.test(adminPhone)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Nomor telepon hanya boleh mengandung angka!'
                });
                return;
            }

            // Check the length of the input
            if (adminPhone.length > 13) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Nomor telepon tidak boleh lebih dari 13 angka!'
                });
                return;
            }

            // If validation passes, proceed with the API call
            fetch('/api/update-admin-phone', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
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
                })

        }
        // Function to open the edit form with pre-filled values
        function openEditForm(id) {
            const tps = tpsData.find(t => t.id === id);
            if (tps) {
                editingId = id; // Set the ID for editing
                document.getElementById('namaTps').value = tps.namaTps;
                document.getElementById('alamat').value = tps.alamat;
                document.getElementById('lat').value = tps.lat;
                document.getElementById('lng').value = tps.lng;
                document.getElementById('add-tps-form-container').classList.remove('hidden');
                document.querySelector('.btn-submit').textContent = 'Update TPS';
            }
        }

        // Function to delete TPS
        function deleteTPS(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Anda tidak akan dapat mengembalikan ini!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // AJAX call to delete the TPS
                    fetch(`/tps/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Simulate deleting from the data array
                                tpsData = tpsData.filter(t => t.id !== id);
                                renderTPSList(); // Refresh the TPS list
                                updateMarkers(); // Refresh the markers on the map
                                Swal.fire('Dihapus!', 'TPS telah dihapus.', 'success');
                            } else {
                                Swal.fire('Error', data.message, 'error');
                            }
                        })
                        .catch(error => {
                            Swal.fire('Error', 'Terjadi kesalahan saat menghapus TPS. Silakan coba lagi.',
                                'error');
                        });
                }
            });
        }

        // Event listeners
        document.getElementById('open-add-tps').addEventListener('click', () => {
            editingId = null; // Reset editing ID
            document.getElementById('add-tps-form-container').classList.remove('hidden');
            document.querySelector('.btn-submit').textContent = 'Tambah TPS';
            document.getElementById('add-tps-form').reset(); // Reset form
        });

        document.getElementById('close-add-tps').addEventListener('click', () => {
            document.getElementById('add-tps-form-container').classList.add('hidden');
        });

        // Form submission handler
        document.getElementById('add-tps-form').addEventListener('submit', (e) => {
            e.preventDefault();

            // Ambil data dari form
            const namaTps = document.getElementById('namaTps').value;
            const alamat = document.getElementById('alamat').value;
            const lat = parseFloat(document.getElementById('lat').value);
            const lng = parseFloat(document.getElementById('lng').value);

            // Tentukan URL dan metode yang sesuai (POST untuk tambah, PUT untuk edit)
            const url = editingId ? `/tps/${editingId}` : '/tps';
            const method = editingId ? 'PUT' : 'POST';

            // Persiapkan data yang akan dikirim
            const requestData = {
                namaTps,
                alamat,
                lat,
                lng
            };

            // Kirim data ke server
            fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(requestData)
                })
                .then(response => {
                    // Pastikan respons adalah JSON
                    return response.json().then(data => ({
                        status: response.status,
                        body: data
                    }));
                })
                .then(({
                    status,
                    body
                }) => {
                    if (status >= 200 && status < 300 && body.success) {
                        // Jika sukses
                        if (editingId) {
                            // Perbarui data lokal TPS jika sedang dalam mode edit
                            const index = tpsData.findIndex(t => t.id === editingId);
                            if (index !== -1) {
                                tpsData[index] = {
                                    id: editingId,
                                    namaTps,
                                    alamat,
                                    lat,
                                    lng
                                };
                            }
                            Swal.fire('Berhasil!', 'TPS telah diperbarui.', 'success');
                        } else {
                            // Tambah TPS baru ke dalam data lokal
                            tpsData.push(body.data); // Menggunakan data baru dari server
                            Swal.fire('Berhasil!', 'TPS telah ditambahkan.', 'success');
                        }

                        // Render ulang tabel dan marker peta
                        renderTPSList(); // Menampilkan daftar TPS
                        updateMarkers(); // Memperbarui marker di peta

                        // Sembunyikan form dan reset input
                        document.getElementById('add-tps-form-container').classList.add('hidden');
                        document.getElementById('add-tps-form').reset();
                    } else {
                        // Jika ada kesalahan dari server
                        Swal.fire('Error', body.message ||
                            'Terjadi kesalahan saat menambahkan atau memperbarui TPS.', 'error');
                    }
                });
        });
        renderTPSList();
        updateMarkers();
        // Filter TPS berdasarkan jarak
        function filterTPSByDistance() {
            const selectedDistance = parseInt(document.getElementById('distance-filter')
                .value); // Mendapatkan jarak yang dipilih
            const userLocation = map.getCenter(); // Mengambil posisi pusat peta (bisa disesuaikan dengan lokasi pengguna)

            // Menampilkan indikator loading (misalnya spinner)
            document.getElementById('loading').style.display = 'block';

            // Menghitung TPS yang sesuai dengan jarak yang dipilih
            setTimeout(() => {
                const filteredTps = originalTpsData.filter(tps => {
                    const tpsLatLng = L.latLng(tps.lat, tps.lng); // Koordinat TPS
                    const distance = userLocation.distanceTo(tpsLatLng); // Menghitung jarak

                    // Jika jarak yang dipilih 0, tampilkan semua TPS. Jika tidak, tampilkan TPS yang dalam jangkauan jarak
                    return selectedDistance === 0 || distance <= selectedDistance;
                });

                // Menampilkan TPS yang sudah difilter
                renderTPSList(filteredTps);

                // Menyembunyikan indikator loading setelah data ditampilkan
                document.getElementById('loading').style.display = 'none';
            }, 500);
        }

        // Menambahkan event listener untuk perubahan filter jarak
        document.getElementById('distance-filter').addEventListener('change', filterTPSByDistance);
        // Fungsi untuk update waktu real-time
        function updateTime() {
            const timeElement = document.getElementById('time');
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            timeElement.textContent = `${hours}:${minutes}:${seconds}`;
        }

        // Fungsi untuk mendapatkan cuaca berdasarkan lokasi pengguna
        function getWeather() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;
                    const apiKey = '344abe5a4f56636f35a541a3b0a74554';
                    const weatherUrl =
                        `https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&appid=${apiKey}&units=metric`;

                    fetch(weatherUrl)
                        .then(response => response.json())
                        .then(data => {
                            const weatherElement = document.getElementById('weather');
                            const weatherIconElement = document.getElementById('weather-icon');
                            const locationElement = document.getElementById(
                                'location-name');
                            const temperature = data.main.temp;
                            const weatherDescription = data.weather[0].description;
                            const weatherIcon = data.weather[0].icon;
                            const locationName = data.name;

                            // Menampilkan nama lokasi (kota)
                            locationElement.textContent = `${locationName}`;

                            // Menampilkan cuaca dan ikon
                            weatherElement.textContent = `${temperature}°C, ${weatherDescription}`;
                            weatherIconElement.innerHTML =
                                `<img src="https://openweathermap.org/img/wn/${weatherIcon}.png" alt="${weatherDescription}" />`; // Menampilkan ikon cuaca
                        })
                        .catch(error => {
                            console.error('Error fetching weather data:', error);
                            document.getElementById('weather').textContent = 'Tidak dapat memuat cuaca';
                        });
                }, function(error) {
                    console.error("Geolocation error:", error);
                    document.getElementById('weather').textContent = 'Lokasi tidak ditemukan';
                }, {
                    enableHighAccuracy: true,
                    timeout: 5000,
                    maximumAge: 0
                });
            } else {
                console.error("Geolocation is not supported by this browser.");
                document.getElementById('weather').textContent = 'Lokasi tidak ditemukan';
            }
        }
        setInterval(updateTime, 1000);
        updateTime();
        getWeather();
    </script>
</x-app-layout>
