<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="container" style="background-color: #e1e1e1;">
            <div class="p-6">
                <div class="flex justify-between items-center">
                    <div class="flex-1 text-left">
                        @auth
                            <div style="font-size: 1.6rem; font-weight: bold;">
                                {{ __('Selamat datang , ') }}
                                <span class="welcome-message text-uppercase">
                                    {{ Auth::user()->name }}
                                </span>
                            </div>
                        @else
                            <div>{{ __("You're not logged in!") }}</div>
                        @endauth
                    </div>
                    <div class="weather-location-container">
                        {{-- <!-- Jam Realtime -->
                        <div id="current-time" class="info-item">
                            <i class="fa fa-clock"></i>
                            <span id="time"></span>
                        </div> --}}
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

        <div class="info-icon">
            <i class="fas fa-info-circle" onclick="toggleInfoPopup()"></i>
        </div>

        <!-- Popup Poster -->
        <div id="info-popup" class="info-popup hidden" style="z-index: 9999;">
            <img src="{{ asset('image/poster.png') }}" alt="Informasi Poster" class="poster-image">
            <i onclick="toggleInfoPopup()" class="close-popup fa fa-times"></i> <!-- Font Awesome Close Icon -->
        </div>

        <!-- Map Container -->
        <div class="map-container mx-auto max-w-2xl" style="margin-top: 30px;">
            <div id="map" style="height: 400px; width: 100%;"></div>
        </div>

        <!-- Route Info Container -->
        <div id="route-info"
            style="height: 120px; width: 250px; background-color: white; border: 1px solid #ccc; padding: 10px; margin: 10px auto; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
            <h2
                style="font-size: 1.2rem; margin-bottom: 5px; text-align: center; font-weight: bold; text-transform: uppercase;">
                INFORMASI RUTE
            </h2>
            <div id="route-details" style="text-align: center;">
                <div id="fastest-route">Rute Tercepat: -</div>
                <div id="route-duration">Durasi: -</div>
            </div>
        </div>

        <button class="whatsapp-button" onclick="toggleWhatsAppForm()">
            <i class="fas fa-comments" style="font-size: 30px;"></i>
            <span>Chat Admin</span>
        </button>

        <!-- Form Isian untuk WhatsApp -->
        <div id="whatsapp-form" style="display: none;">
            <h3 style="text-align: center; margin: 0;">Informasi TPS</h3>
            <input type="text" id="nama-tps" placeholder="Nama TPS" required />
            <input type="text" id="lat" placeholder="Latitude" required />
            <input type="text" id="lng" placeholder="Longitude" required />
            <input type="text" id="alamat" placeholder="Alamat" required />
            <input type="textarea" id="pesan" placeholder="Masukkan pesan" required />
            <button onclick="sendToWhatsApp()">Kirim ke WhatsApp</button>
        </div>

        <div class="overflow-x-auto mx-auto w-full max-w-2xl mt-4">
            <input type="text" id="search-tps" placeholder="Cari TPS..."
                class="border border-gray-300 rounded p-2 mb-4 w-full" />

            {{-- <label for="distance-filter" class="block mb-2">Pilih Jarak</label>
            <select id="distance-filter" class="border border-gray-300 rounded p-2 mb-4 w-full">
                <option value="0">Semua</option>
                <option value="200">200 m</option>
                <option value="500">500 m</option>
                <option value="1000">1 km</option>
                <option value="2000">2 km</option>
            </select> --}}

            <div id="loading" class="text-center hidden">Sedang memuat...</div> <!-- Elemen loading -->
            <div id="tps-list">
                <table class="min-w-full bg-white border">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-4 px-6 text-left border-b">Nama TPS</th>
                            <th class="py-4 px-6 text-left border-b">Alamat</th>
                            <th class="py-4 px-6 text-left border-b">Status</th> <!-- Kolom Status -->
                            <th class="py-4 px-6 text-left border-b">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm font-light" id="tps-table-body">
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
                                            <button
                                                class="lihat-btn bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md"
                                                data-lat="{{ $tpsItem->lat }}" data-lng="{{ $tpsItem->lng }}"
                                                data-nama="{{ $tpsItem->namaTps }}"
                                                data-alamat="{{ $tpsItem->alamat }}"
                                                data-status="{{ $tpsItem->status }}">
                                                Lihat
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                <div id="no-data" class="py-3 text-center border border-gray-200 rounded hidden">Data TPS tidak
                    ditemukan</div>
            </div>
            <!-- Pagination -->
            <div class="mt-4">
                {{ $tps->links() }}
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

        <!-- Leaflet JS & SweetAlert -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>

        <script>
            // Deklarasi variabel global
            var map;
            var routingControl = null;
            var userMarker = null;
            var tpsMarker = null; // Menyimpan marker TPS untuk menghindari tumpang tindih

            document.addEventListener('DOMContentLoaded', function() {
                // Inisialisasi peta
                map = L.map('map').setView([-7.250445, 112.768845], 10); // Koordinat default Surabaya
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                // Menambahkan ikon untuk TPS
                var tpsIcon = L.icon({
                    iconUrl: '/image/marker.png', // Ganti dengan path gambar ikon TPS
                    iconSize: [32, 32], // Ukuran ikon
                    iconAnchor: [16, 32], // Titik anchor pada ikon
                    popupAnchor: [0, -32] // Posisi popup relatif terhadap ikon
                });

                // Menambahkan marker TPS
                @foreach ($tps as $tpsItem)
                    var marker = L.marker([{{ $tpsItem->lat }}, {{ $tpsItem->lng }}], {
                            icon: tpsIcon
                        })
                        .addTo(map)
                        .bindPopup(
                            '<strong>{{ $tpsItem->namaTps }}</strong><br>{{ $tpsItem->alamat }}<br>' +
                            '<span style="color: {{ $tpsItem->status == 'tersedia' ? 'green' : ($tpsItem->status == 'penuh' ? 'red' : 'orange') }}; ' +
                            'border: 1px solid {{ $tpsItem->status == 'tersedia' ? 'green' : ($tpsItem->status == 'penuh' ? 'red' : 'orange') }}; ' +
                            'padding: 4px 8px; ' +
                            'border-radius: 8px; ' +
                            'background-color: {{ $tpsItem->status == 'tersedia' ? '#d4edda' : ($tpsItem->status == 'penuh' ? '#f8d7da' : '#fff3cd') }};' +
                            'display: block; margin-bottom: 8px; max-width: 150px; word-wrap: break-word; text-align: left;">' +
                            '{{ ucfirst($tpsItem->status) }}</span>'
                        );
                @endforeach

                // Menambahkan lokasi pengguna
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        var userLat = position.coords.latitude;
                        var userLng = position.coords.longitude;

                        // Menghapus marker lokasi pengguna jika ada sebelumnya
                        if (userMarker) {
                            map.removeLayer(userMarker);
                        }

                        // Menambahkan marker untuk lokasi pengguna
                        userMarker = L.marker([userLat, userLng], {
                                icon: L.icon({
                                    iconUrl: '/image/markertitik.png', // Ganti dengan path gambar ikon lokasi pengguna
                                    iconSize: [32, 32], // Ukuran ikon
                                    iconAnchor: [16, 32], // Titik anchor pada ikon
                                    popupAnchor: [0, -32] // Posisi popup relatif terhadap ikon
                                })
                            }).addTo(map)
                            .bindPopup("Lokasi Anda")
                            .openPopup();

                        // Mengatur tampilan peta ke lokasi pengguna
                        map.setView([userLat, userLng], 13); // Menyesuaikan zoom level
                    }, function() {
                        alert("Gagal mendapatkan lokasi Anda. Pastikan GPS aktif.");
                    });
                } else {
                    alert("Geolocation tidak didukung oleh browser Anda.");
                }
            });

            // Delegasi event untuk tombol "Lihat"
            document.addEventListener('click', function(event) {
                var button = event.target.closest('.lihat-btn');
                if (button) {
                    var lat = parseFloat(button.getAttribute('data-lat'));
                    var lng = parseFloat(button.getAttribute('data-lng'));
                    var nama = button.getAttribute('data-nama');
                    var alamat = button.getAttribute('data-alamat');
                    var status = button.getAttribute('data-status');
                    viewCoordinates(lat, lng, nama, alamat, status);
                }
            });

            // Fungsi untuk menampilkan rute dan informasi TPS
            function viewCoordinates(lat, lng, namaTps, alamat, status) {
                // Hapus rute dan marker TPS lama jika ada
                if (routingControl) {
                    map.removeControl(routingControl);
                    routingControl = null;
                }
                if (tpsMarker) {
                    map.removeLayer(tpsMarker);
                }

                // Menambahkan marker TPS baru
                tpsMarker = L.marker([lat, lng], {
                        icon: L.icon({
                            iconUrl: '/image/marker.png', // Ganti dengan path gambar ikon TPS
                            iconSize: [32, 32], // Ukuran ikon
                            iconAnchor: [16, 32], // Titik anchor pada ikon
                            popupAnchor: [0, -32] // Posisi popup relatif terhadap ikon
                        })
                    }).addTo(map)
                    .bindPopup(
                        '<strong>' + namaTps + '</strong><br>' +
                        alamat + '<br>' +
                        '<span style="color: ' + (status == 'tersedia' ? 'green' : (status == 'penuh' ? 'red' : 'orange')) +
                        '; ' +
                        'border: 1px solid ' + (status == 'tersedia' ? 'green' : (status == 'penuh' ? 'red' : 'orange')) +
                        '; ' +
                        'padding: 4px 8px; ' +
                        'border-radius: 8px; ' +
                        'background-color: ' + (status == 'tersedia' ? '#d4edda' : (status == 'penuh' ? '#f8d7da' :
                            '#fff3cd')) + '; ' +
                        'display: block; margin-bottom: 8px; max-width: 150px; word-wrap: break-word; text-align: left;">' +
                        status.charAt(0).toUpperCase() + status.slice(1) + '</span>'
                    ).openPopup();

                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        var userLat = position.coords.latitude;
                        var userLng = position.coords.longitude;

                        // Membuat rute dari lokasi pengguna ke TPS
                        routingControl = L.Routing.control({
                            waypoints: [L.latLng(userLat, userLng), L.latLng(lat, lng)],
                            routeWhileDragging: true,
                            createMarker: function() {
                                return null;
                            }, // Menghindari penambahan marker default pada rute
                            lineOptions: {
                                styles: [{
                                    color: 'blue',
                                    weight: 5
                                }]
                            }
                        }).addTo(map);

                        // Menampilkan informasi rute di pop-up
                        routingControl.on('routesfound', function(e) {
                            var routes = e.routes[0];
                            var distance = (routes.summary.totalDistance / 1000).toFixed(2); // Dalam kilometer
                            var duration = (routes.summary.totalTime / 60).toFixed(1); // Dalam menit

                            // Mengirimkan informasi jarak dan durasi ke elemen
                            document.getElementById('fastest-route').textContent =
                                `Rute Tercepat: ${distance} km`;
                            document.getElementById('route-duration').textContent = `Durasi: ${duration} menit`;

                            // Menampilkan jarak dan durasi dalam popup TPS
                            tpsMarker.bindPopup(
                                '<strong>' + namaTps + '</strong><br>' +
                                alamat + '<br>' +
                                '<span style="color: ' + (status == 'tersedia' ? 'green' : (status ==
                                    'penuh' ? 'red' : 'orange')) + '; ' +
                                'border: 1px solid ' + (status == 'tersedia' ? 'green' : (status ==
                                    'penuh' ? 'red' : 'orange')) + '; ' +
                                'padding: 4px 8px; ' +
                                'border-radius: 8px; ' +
                                'background-color: ' + (status == 'tersedia' ? '#d4edda' : (status ==
                                    'penuh' ? '#f8d7da' : '#fff3cd')) + '; ' +
                                'display: block; margin-bottom: 8px; max-width: 150px; word-wrap: break-word; text-align: left;">' +
                                status.charAt(0).toUpperCase() + status.slice(1) + '</span>' + '<br>' +
                                `Jarak: ${distance} km <br>Durasi: ${duration} menit`
                            ).openPopup();
                        });

                        // Zoom peta untuk mencakup rute
                        var bounds = L.latLngBounds([userLat, userLng], [lat, lng]);
                        map.fitBounds(bounds, {
                            padding: [50, 50]
                        });
                    });
                }
            }
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('search-tps');
                const loadingElement = document.getElementById('loading'); // Ambil elemen loading
                const noDataElement = document.getElementById('no-data');

                searchInput.addEventListener('input', function() {
                    // Tampilkan efek loading saat mengetik
                    loadingElement.style.display = 'block';

                    // Ambil nilai pencarian
                    const searchValue = searchInput.value.toLowerCase();

                    // Ambil semua baris tabel TPS
                    const rows = document.querySelectorAll('#tps-table-body tr');

                    let found = false;

                    // Loop untuk memeriksa setiap baris
                    rows.forEach(function(row) {
                        const tpsName = row.querySelector('td').textContent
                            .toLowerCase(); // Ambil nama TPS

                        // Periksa apakah nama TPS mengandung teks pencarian
                        if (tpsName.includes(searchValue)) {
                            row.style.display = ''; // Tampilkan baris jika cocok
                            found = true;
                        } else {
                            row.style.display = 'none'; // Sembunyikan baris jika tidak cocok
                        }
                    });

                    // Tampilkan atau sembunyikan pesan "Data TPS tidak ditemukan"
                    if (found) {
                        noDataElement.style.display = 'none';
                    } else {
                        noDataElement.style.display = 'block';
                    }

                    // Sembunyikan efek loading setelah pencarian selesai
                    setTimeout(function() {
                        loadingElement.style.display =
                            'none'; // Sembunyikan loading setelah pencarian selesai
                    }, 500); // Delay singkat untuk efek loading
                });
            });
            // Function to toggle WhatsApp form visibility
            function toggleWhatsAppForm() {
                const form = document.getElementById('whatsapp-form');
                form.style.display = form.style.display === 'none' ? 'block' : 'none';
            }

            // Function to send message to WhatsApp
            function sendToWhatsApp() {
                const namaTps = document.getElementById('nama-tps').value;
                const lat = document.getElementById('lat').value;
                const lng = document.getElementById('lng').value;
                const alamat = document.getElementById('alamat').value;

                if (namaTps && lat && lng && alamat) {
                    fetch('/settings/whatsapp')
                        .then(response => response.json())
                        .then(data => {
                            const whatsappNumber = data.whatsapp_number;
                            const message = `TPS: ${namaTps}, Latitude: ${lat}, Longitude: ${lng}, Alamat: ${alamat}`;
                            const whatsappLink = `https://wa.me/${whatsappNumber}?text=${encodeURIComponent(message)}`;
                            window.open(whatsappLink, '_blank');
                        })
                        .catch(error => {
                            console.error('Error fetching WhatsApp number:', error);
                        });
                } else {
                    Swal.fire('Error', 'Semua field harus diisi!', 'error');
                }
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
