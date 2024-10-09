<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="custom-container">
                <div class="p-6 text-gray-900">
                    @auth
                        <div style="font-size: 1.2rem;">
                            {{ __('Selamat datang, ') }}
                            <span class="welcome-message font-bold uppercase">{{ Auth::user()->name }}</span>
                        </div>
                    @else
                        <div>{{ __("You're not logged in!") }}</div>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Map Container -->
    <div class="map-container mx-auto max-w-2xl">
        <div id="map" style="height: 400px; width: 100%;"></div>
    </div>

    <!-- Route Info Container -->
    <div id="route-info"
        style="height: 120px; width: 250px; background-color: white; border: 1px solid #ccc; padding: 10px; margin: 10px auto; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
        <h2
            style="font-size: 1.2rem; margin-bottom: 5px; text-align: center; font-weight: bold; text-transform: uppercase;">
            INFORMASI RUTE</h2>
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
        <h3 style="text-align: center; margin: 0;">Kirim Data TPS baru</h3>
        <input type="text" id="nama-tps" placeholder="Nama TPS" required />
        <input type="text" id="lat" placeholder="Latitude" required />
        <input type="text" id="lng" placeholder="Longitude" required />
        <input type="text" id="alamat" placeholder="Alamat" required />
        <button onclick="sendToWhatsApp()">Kirim ke WhatsApp</button>
    </div>

    <!-- Search TPS with Distance Filter -->
    <div class="overflow-x-auto mx-auto w-full max-w-2xl mt-4">
        <input type="text" id="search-tps" placeholder="Cari TPS..."
            class="border border-gray-300 rounded p-2 mb-4 w-full" />

        <label for="distance-filter" class="block mb-2">Pilih Jarak</label>
        <select id="distance-filter" class="border border-gray-300 rounded p-2 mb-4 w-full">
            <option value="0">Semua</option>
            <option value="200">200 m</option>
            <option value="500">500 m</option>
            <option value="1000">1 km</option>
            <option value="2000">2 km</option>
        </select>

        <div id="loading" class="text-center hidden">Sedang memuat...</div> <!-- Elemen loading -->
        <div id="tps-list">
            <table class="min-w-full bg-white border">
                <thead>
                    <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-4 text-left border-b">Nama TPS</th>
                        <th class="py-3 px-4 text-left border-b">Alamat</th>
                        <th class="py-3 px-4 text-left border-b">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light" id="tps-table-body">
                    @if ($tps->isEmpty())
                        <tr>
                            <td colspan="3" class="py-3 px-4 text-center">Tidak ada data TPS</td>
                        </tr>
                    @else
                        @foreach ($tps as $tpsItem)
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                <td class="py-3 px-4">{{ $tpsItem->namaTps }}</td>
                                <td class="py-3 px-4">{{ $tpsItem->alamat }}</td>
                                <td class="py-3 px-4">
                                    <button class="view-button" data-lat="{{ $tpsItem->lat }}"
                                        data-lng="{{ $tpsItem->lng }}">
                                        Lihat
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
            <div id="no-data" class="py-3 text-center border border-gray-200 rounded hidden">Data TPS tidak ditemukan
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

    <!-- Leaflet JS & SweetAlert -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>

    <script>
        // Initialize Leaflet map
        const map = L.map('map').setView([-7.2575, 112.7521], 13);

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // Define custom icon for TPS markers
        var customIcon = L.icon({
            iconUrl: '{{ asset('image/marker.png') }}',
            iconSize: [32, 32],
            iconAnchor: [16, 32],
            popupAnchor: [0, -32]
        });

        const tpsData = @json($tps);
        let routingControl;
        let userLatLng;

        // Function to show user's current location
        function showCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    userLatLng = [position.coords.latitude, position.coords.longitude];

                    L.marker(userLatLng, {
                        icon: L.icon({
                            iconUrl: '{{ asset('image/markertitik.png') }}',
                            iconSize: [32, 32],
                            iconAnchor: [16, 32],
                            popupAnchor: [0, -32]
                        })
                    }).addTo(map).bindPopup('Lokasi Anda');

                    map.setView(userLatLng, 15);
                    // Trigger TPS filtering after locating user
                    filterTPSByDistance();
                }, function() {
                    Swal.fire('Error', 'Tidak dapat mengakses lokasi Anda.', 'error');
                });
            } else {
                Swal.fire('Error', 'Browser ini tidak mendukung geolocation.', 'error');
            }
        }

        // Add markers for each TPS when the map is initialized
        tpsData.forEach(tpsItem => {
            const tpsLatLng = [tpsItem.lat, tpsItem.lng];

            L.marker(tpsLatLng, {
                icon: customIcon
            }).addTo(map).bindPopup(`<b>${tpsItem.namaTps}</b><br>${tpsItem.alamat}`);
        });

        // Function to calculate and display route
        function calculateRoute(destinationLat, destinationLng) {
            if (userLatLng) {
                // Clear existing routes on the map if needed
                if (routingControl) {
                    map.removeControl(routingControl);
                }

                // Show loading indication
                document.getElementById('loading').classList.remove('hidden');

                // Create a new route
                routingControl = L.Routing.control({
                    waypoints: [
                        L.latLng(userLatLng[0], userLatLng[1]),
                        L.latLng(destinationLat, destinationLng)
                    ],
                    routeWhileDragging: false, // Set to false for better performance
                    createMarker: function() {
                        return null;
                    },
                    lineOptions: {
                        styles: [{
                            color: 'blue',
                            weight: 4
                        }]
                    },
                }).addTo(map);

                routingControl.on('routesfound', function(e) {
                    const route = e.routes[0];
                    const distance = (route.summary.totalDistance / 1000).toFixed(2); // Convert to km
                    const duration = Math.round(route.summary.totalTime / 60); // Convert to minutes

                    document.getElementById('fastest-route').textContent = 'Rute Tercepat: ' + distance + ' km';
                    document.getElementById('route-duration').textContent = 'Durasi: ' + duration + ' menit';

                    // Hide loading indication
                    document.getElementById('loading').classList.add('hidden');
                });
            }
        }

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

        // Function to filter TPS by distance
        function filterTPSByDistance() {
            const distanceFilter = document.getElementById('distance-filter').value;
            const searchQuery = document.getElementById('search-tps').value.toLowerCase();
            const filteredTPS = tpsData.filter(tpsItem => {
                const tpsLatLng = [tpsItem.lat, tpsItem.lng];
                const distance = calculateDistance(userLatLng, tpsLatLng);
                const withinDistance = (distanceFilter == 0) || (distance <= distanceFilter);

                // Filter by name
                const nameMatch = tpsItem.namaTps.toLowerCase().includes(searchQuery);

                return withinDistance && nameMatch;
            });

            displayTPS(filteredTPS);
        }

        // Function to calculate distance between two latitude/longitude points
        function calculateDistance(coord1, coord2) {
            const R = 6371; // Radius of the Earth in km
            const dLat = (coord2[0] - coord1[0]) * Math.PI / 180;
            const dLon = (coord2[1] - coord1[1]) * Math.PI / 180;
            const a =
                Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(coord1[0] * Math.PI / 180) * Math.cos(coord2[0] * Math.PI / 180) *
                Math.sin(dLon / 2) * Math.sin(dLon / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            return R * c * 1000; // Distance in meters
        }

        // Function to display TPS in the table
        function displayTPS(tpsList) {
            const tpsTableBody = document.getElementById('tps-table-body');
            tpsTableBody.innerHTML = ''; // Clear existing rows
            const noDataMessage = document.getElementById('no-data');
            if (tpsList.length === 0) {
                noDataMessage.classList.remove('hidden');
            } else {
                noDataMessage.classList.add('hidden');
                tpsList.forEach(tpsItem => {
                    const row = document.createElement('tr');
                    row.classList.add('border-b', 'border-gray-200', 'hover:bg-gray-100');
                    row.innerHTML = `
                        <td class="py-3 px-4">${tpsItem.namaTps}</td>
                        <td class="py-3 px-4">${tpsItem.alamat}</td>
                        <td class="py-3 px-4">
                            <button class="view-button" data-lat="${tpsItem.lat}" data-lng="${tpsItem.lng}">Lihat</button>
                        </td>
                    `;
                    tpsTableBody.appendChild(row);
                });
            }
        }

        // Event listeners
        document.getElementById('distance-filter').addEventListener('change', filterTPSByDistance);
        document.getElementById('search-tps').addEventListener('input', filterTPSByDistance);

        // Show current location when the page loads
        window.onload = showCurrentLocation;

        // Event listener for the view button
        document.getElementById('tps-list').addEventListener('click', function(e) {
            if (e.target.classList.contains('view-button')) {
                const lat = e.target.dataset.lat;
                const lng = e.target.dataset.lng;
                calculateRoute(lat, lng);
            }
        });

        // Function to filter TPS by distance
        function filterTPSByDistance() {
            // Show loading indication
            document.getElementById('loading').classList.remove('hidden');

            const distanceFilter = document.getElementById('distance-filter').value;
            const searchQuery = document.getElementById('search-tps').value.toLowerCase();
            const filteredTPS = tpsData.filter(tpsItem => {
                const tpsLatLng = [tpsItem.lat, tpsItem.lng];
                const distance = calculateDistance(userLatLng, tpsLatLng);
                const withinDistance = (distanceFilter == 0) || (distance <= distanceFilter);

                // Filter by name
                const nameMatch = tpsItem.namaTps.toLowerCase().includes(searchQuery);

                return withinDistance && nameMatch
            });

            // Simulate a delay to show loading effect
            setTimeout(function() {
                displayTPS(filteredTPS);

                // Hide loading indication after filtering is done
                document.getElementById('loading').classList.add('hidden');
            }, 500); // 500ms delay to simulate loading
        }
    </script>
</x-app-layout>
