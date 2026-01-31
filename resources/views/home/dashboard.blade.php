@extends('home.v_layouts')

@section('title', 'Home User')

@section('content')
<h2 class="section-title">🗺️ Tracking</h2>

<div class="card mb-3">
    <div class="card-header">
        <h4>Rute Terpendek</h4>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <label>Titik Awal</label>
                <select id="startPoint" class="form-control">
                    <option value="rumah">🏠 Rumah</option>
                    <option value="kampus_ummi">🎓 Kampus UMMI</option>
                    <option value="secapa_polri">🪖 Secapa Polri</option>
                    <option value="cibadak">📍 Cibadak</option>
                </select>
            </div>
            <div class="col-md-4 align-self-end">
                <button onclick="calculateRoute()" class="btn btn-primary btn-block">
                    🚗 Cari Rute
                </button>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div id="map" style="height:500px;border-radius:10px;"></div>
    </div>
</div>
@endsection

@push('styles')
<link
  rel="stylesheet"
  href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
/>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
let map, gpsMarker, startMarker, routeLine;

// node statis
const nodes = {
    rumah: [-6.9083592252156905, 106.89633620913852],
    kampus_ummi: [-6.9185548295720265, 106.93409279379514],
    secapa_polri: [-6.911272035193913, 106.92435462263086],
    cibadak: [-6.890065918562608, 106.78160176597994]
};

// init map
map = L.map('map').setView(nodes.rumah, 12);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(map);

// ambil GPS realtime
function fetchLocation() {
    fetch("{{ route('getLocation') }}")
        .then(res => res.json())
        .then(data => {
            if (data.status !== 'success') return;

            const pos = [parseFloat(data.lat), parseFloat(data.lng)];

            if (!gpsMarker) {
                gpsMarker = L.marker(pos).addTo(map).bindPopup("📡 Posisi GPS");
            } else {
                gpsMarker.setLatLng(pos);
            }
        });
}

setInterval(fetchLocation, 5000);

// tombol cari rute
function calculateRoute() {
    const startKey = document.getElementById('startPoint').value;
    const start = nodes[startKey];

    fetch("{{ route('getLocation') }}")
        .then(res => res.json())
        .then(data => {
            const end = [parseFloat(data.lat), parseFloat(data.lng)];
            drawRoute(start, end);
        });
}

// routing OSRM (jalan asli)
function drawRoute(start, end) {

    // hapus rute lama
    if (routeLine) map.removeLayer(routeLine);

    // hapus marker awal lama
    if (startMarker) map.removeLayer(startMarker);

    // marker lokasi awal
    startMarker = L.marker(start, {
        icon: L.icon({
            iconUrl: 'https://maps.google.com/mapfiles/ms/icons/green-dot.png',
            iconSize: [32, 32],
            iconAnchor: [16, 32]
        })
    }).addTo(map).bindPopup("🚀 Titik Awal");

    // routing OSRM
    const url = `https://router.project-osrm.org/route/v1/driving/`
        + `${start[1]},${start[0]};${end[1]},${end[0]}`
        + `?overview=full&geometries=geojson`;

    fetch(url)
        .then(res => res.json())
        .then(data => {

            const coords = data.routes[0].geometry.coordinates
                .map(c => [c[1], c[0]]);

            routeLine = L.polyline(coords, {
                color: 'red',
                weight: 5
            }).addTo(map);

            map.fitBounds(routeLine.getBounds());
        });
}

</script>
@endpush
