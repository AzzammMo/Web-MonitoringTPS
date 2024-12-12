<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring TPA</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            overflow-x: hidden;
        }
        .hero {
            text-align: center;
            padding: 40px 0;
            background-color: #327434; 
            color: white; 
            margin-bottom: 0; 
            border-bottom: 2px solid #327434;
        }

        .hero-image {
            width: 100%;
            max-width: 600px;
            height: auto;
            margin-bottom: 20px;
            border-radius: 10px;
        }

        .hero .title {
            font-size: 36px;
            margin-bottom: 10px;
            color: #fff;
        }

        .hero .subtitle {
            font-size: 24px;
            color: #24e76f; 
        }
        .background-wrapper {
            position: relative;
            width: 100%;
            min-height: 100vh; 
            background: url('{{ asset('image/sampah.jpg') }}') no-repeat center center;
            background-size: cover;
            z-index: 1;
            padding-top: 60px; 
            padding-bottom: 10px; 
        }
        .background-wrapper::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); 
            z-index: 1; 
        }
        .map-container {
            width: 80%;
            height: 450px;
            margin: 0 auto 20px auto; 
            border: 2px solid #327434;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 10;
        }

        #map {
            height: 100%;
        }
        .info {
            text-align: center;
            margin: 0; 
            padding: 20px;
            position: relative;
            z-index: 10;
            color: white; 
        }
        .info p {
            font-size: 1.1em;
            margin-bottom: 15px;
        }
        .link {
            color: #4CAF50;
            text-decoration: none;
        }
        .link:hover {
            text-decoration: underline;
        }
        .btn-container {
            margin-top: 20px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            background-color: #4CAF50;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .footer {
            background-color: #327434;
            padding: 10px;
            color: white;
            text-align: center;
            position: relative;
            z-index: 20;
            margin-top: 0;
        }
    </style>
</head>
<body>
    <!-- Hero Section (Title & Subtitle) -->
    <div class="hero">
        <img src="{{ asset('image/image.png') }}" class="hero-image" alt="TPS Image">
        <h1 class="title">MONITORING TEMPAT PEMBUANGAN SAMPAH </h1>
        <h2 class="subtitle">Wilayah Surabaya</h2>
    </div>

    <!-- Background Wrapper (background and content) -->
    <div class="background-wrapper">
        <!-- Map Section -->
        <div class="map-container">
            <div id="map"></div>
        </div>

        <!-- Info Section -->
        <div class="info">
            <p>Untuk melihat area TPS lebih lanjut, silahkan <a href="{{ route('login') }}" class="link">Login</a>
                terlebih dahulu.</p>
            <p>Jika Anda belum memiliki akun atau ingin mendaftarkan TPS baru, minta admin untuk mendaftarkan TPS baru.</p>
            <div class="btn-container">
                <a href="{{ route('login') }}" class="btn">Login</a>
                <a href="{{ route('register') }}" class="btn">Register</a>
            </div>
        </div>
    </div>

    <!-- Footer Section -->
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
            iconUrl: '{{ asset('image/marker.png') }}', // path to your custom icon
            iconSize: [32, 32], // size of the icon
            iconAnchor: [16, 32], // point of the icon which will correspond to marker's location
            popupAnchor: [0, -32] // point from which the popup should open relative to the iconAnchor
        });

        // Array of TPS data from the server (passed from controller to view)
        const tpsData = @json($tps);

        // Loop through each TPS and add marker to the map
        tpsData.forEach(tps => {
            L.marker([tps.lat, tps.lng], {
                    icon: customIcon
                })
                .addTo(map)
                .bindPopup(`<strong>${tps.namaTps}</strong><br>${tps.alamat}`);
        });
    </script>
</body>
</html>
