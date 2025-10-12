@extends('home.v_layouts')

@section('title', 'Home User')

@section('breadcrumb')
    @php
        $items = [
            ['name' => 'Dashboard', 'url' => route('indexAdmin')],
            ['name' => 'Home', 'url' => ''],
        ];
    @endphp
    @include('components.breadcrumb', ['items' => $items])
@endsection

@section('content')
    <h2 class="section-title">@yield('title')</h2>
    <div class="card">  
        <div class="card-header">
            <h4>Real Time Tracking Position</h4>
        </div>
        <div class="card-body">
            <div id="map-info-nama" class="mb-2 text-muted">📡 Memuat device yang terhubung...</div>
            <div id="map-info-lokasi" class="mb-2 text-muted">📍 Memuat koordinat dari alat...</div>
            <div id="map" style="width: 100%; height: 500px; border-radius: 10px;"></div>
        </div>
    </div>
@endsection

@push('scripts')
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC-B4NynLW9quhSmUzruZANgkRNskU47nQ&callback=initMap"
        async defer>
    </script>

    <script>
        let map = null;
        let marker = null;

        function initMap() {
            fetchLocation(); // panggil map pertama kali
            setInterval(fetchLocation, 5000); // refresh map otomatis
        }

        function fetchLocation() {
            console.log("⏳ Memuat lokasi dari alat...");

            fetch('{{ route('getLocation') }}')
                .then(response => response.json())
                .then(data => {
                    console.log("Data dari alat:", data);

                    const mapContainer = document.getElementById("map");

                    if (data.status === "success" && data.device && data.lat && data.lng) {
                        const lat = parseFloat(data.lat);
                        const lng = parseFloat(data.lng);
                        const device_name = data.device;
                        const pos = { lat, lng };

                        // Jika map belum dibuat
                        if (!map) {
                            mapContainer.innerHTML = ""; // hapus alert jika ada
                            map = new google.maps.Map(mapContainer, {
                                zoom: 15,
                                center: pos
                            });

                            marker = new google.maps.Marker({
                                position: pos,
                                map: map,
                                title: device_name
                            });
                        } else {
                            marker.setPosition(pos);
                            marker.setTitle(device_name);
                            map.setCenter(pos);
                        }

                        document.getElementById("map-info-nama").innerText = `📡 Nama Device: ${device_name}`;
                        document.getElementById("map-info-lokasi").innerText = `📍 Lat: ${lat}, Lng: ${lng}`;
                    } else {
                        showError("❌ Data koordinat tidak tersedia.");
                    }
                })
                .catch(err => {
                    console.error("❌ Gagal mengambil data:", err);
                    showError("❌ Gagal mengambil data dari server.");
                });
        }

        function showError(message) {
            const mapContainer = document.getElementById("map");

            mapContainer.innerHTML = `
                <div class="alert alert-warning text-center">
                    ${message}
                </div>
            `;
        }
    </script>
@endpush
