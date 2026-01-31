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
            <h4>Real Time Tracking & Shortest Path (Dijkstra)</h4>
        </div>

        <div class="card-body">
            <div id="map-info-nama" class="mb-2 text-muted">
                📡 Memuat device yang terhubung...
            </div>

            <div id="map-info-lokasi" class="mb-2 text-muted">
                📍 Memuat koordinat dari alat...
            </div>

            <div id="map" style="width: 100%; height: 500px; border-radius: 10px;"></div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- GOOGLE MAP --}}
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC-B4NynLW9quhSmUzruZANgkRNskU47nQ"
        async defer>
    </script>

    <script>
        let map = null;
        let marker = null;
        let dijkstraLine = null;

        function initMap() {
            fetchLocation();
            fetchDijkstraRoute();

            setInterval(fetchLocation, 5000);      // GPS realtime
            setInterval(fetchDijkstraRoute, 15000); // Refresh rute
        }

        // ==========================
        // GPS REALTIME
        // ==========================
        function fetchLocation() {
            fetch('{{ route('getLocation') }}')
                .then(res => res.json())
                .then(data => {
                    if (data.status === "success" && data.lat && data.lng) {

                        const pos = {
                            lat: parseFloat(data.lat),
                            lng: parseFloat(data.lng)
                        };

                        if (!map) {
                            map = new google.maps.Map(document.getElementById("map"), {
                                zoom: 15,
                                center: pos
                            });

                            marker = new google.maps.Marker({
                                position: pos,
                                map: map,
                                title: data.device
                            });
                        } else {
                            marker.setPosition(pos);
                            map.setCenter(pos);
                        }

                        document.getElementById("map-info-nama").innerText =
                            `📡 Nama Device: ${data.device}`;

                        document.getElementById("map-info-lokasi").innerText =
                            `📍 Lat: ${pos.lat}, Lng: ${pos.lng}`;
                    }
                })
                .catch(err => console.error("GPS error:", err));
        }

        // ==========================
        // DIJKSTRA ROUTE
        // ==========================
        function fetchDijkstraRoute() {
            fetch('/tracking/dijkstra')
                .then(res => res.json())
                .then(data => {

                    if (!data.route || data.route.length === 0) return;

                    const routePath = data.route.map(p => ({
                        lat: parseFloat(p.lat),
                        lng: parseFloat(p.lng)
                    }));

                    if (dijkstraLine) {
                        dijkstraLine.setMap(null);
                    }

                    dijkstraLine = new google.maps.Polyline({
                        path: routePath,
                        geodesic: true,
                        strokeColor: "#FF0000",
                        strokeOpacity: 1,
                        strokeWeight: 4
                    });

                    dijkstraLine.setMap(map);

                    // Marker START
                    new google.maps.Marker({
                        position: routePath[0],
                        map: map,
                        label: "A"
                    });

                    // Marker END
                    new google.maps.Marker({
                        position: routePath[routePath.length - 1],
                        map: map,
                        label: "B"
                    });
                })
                .catch(err => console.error("Dijkstra error:", err));
        }

        window.onload = initMap;
    </script>
@endpush
