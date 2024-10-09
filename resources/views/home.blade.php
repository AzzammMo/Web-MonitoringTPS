<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring TPS</title>
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
</head>

<body>

    <!-- Hero Section (Title & Subtitle) -->
    <div class="hero">
        <img src="{{ asset('image/image.png') }}" class="hero-image" alt="TPS Image">
        <h1 class="title">MONITORING TEMPAT PEMBUANGAN SAMPAH</h1>
        <h2 class="subtitle">Wilayah Surabaya</h2>
    </div>

    <!-- Map Container -->
    <div class="map-container">
        <div id="map" style="height: 450px;"></div>
    </div>

    <!-- Info Section -->
    <div class="info">
        <p>Untuk melihat area TPS lebih lanjut, silahkan <a href="{{ route('login') }}" class="link">Login</a> terlebih dahulu.</p>
        <p>Jika Anda belum memiliki akun atau ingin mendaftarkan TPS baru, minta admin untuk mendaftarkan TPS baru.</p>

        <!-- Button container -->
        <div class="btn-container">
            <a href="{{ route('login') }}" class="btn">Login</a>
            <a href="{{ route('register') }}" class="btn">Register</a>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; 2024 Monitoring TPS Kota Surabaya.</p>
    </footer>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        // Initialize Leaflet map
        const map = L.map('map').setView([-7.2575, 112.7521], 11);

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // Define custom icon for TPS markers
        var customIcon = L.icon({
            iconUrl: '{{ asset("image/marker.png") }}', // path to your custom icon
            iconSize: [32, 32], // size of the icon
            iconAnchor: [16, 32], // point of the icon which will correspond to marker's location
            popupAnchor: [0, -32] // point from which the popup should open relative to the iconAnchor
        });

        // Array of TPS data from the server (passed from controller to view)
        const tpsData = @json($tps);

        // Loop through each TPS and add marker to the map
        tpsData.forEach(tps => {
            L.marker([tps.lat, tps.lng], {icon: customIcon})
                .addTo(map)
                .bindPopup(`<strong>${tps.namaTps}</strong><br>${tps.alamat}`);
        });
    </script>
</body>

</html>
