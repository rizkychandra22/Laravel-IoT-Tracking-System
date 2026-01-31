<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Tracking;
use Carbon\Carbon;
use App\Services\GraphService;
use App\Services\DijkstraService;


class TrackingController extends Controller
{
    public function index() // untuk menampilkan halaman dahsboard dengan map
    {
        return view('home.dashboard');
    }

    public function lastTracking()  // untuk menampilkan halaman riwayat tracking
    {
        $trackings = Tracking::latest('waktu')->get();
        return view('home.riwayat', compact('trackings'));
    }

    public function storeLocation(Request $request)
    {
        $lat = $request->query('lat');
        $lng = $request->query('lng');
        $device = $request->query('device_name');

        if ($lat && $lng && $device) {

            Cache::put('latest_lat', $lat, now()->addMinutes(10));
            Cache::put('latest_lng', $lng, now()->addMinutes(10));
            Cache::put('latest_device', $device, now()->addMinutes(10));

            $this->AutoSaveDatabase($device, $lat, $lng);

            return response()->json([
                'status' => 'success',
                'device' => $device,
                'lat' => $lat,
                'lng' => $lng,
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Parameter GPS tidak lengkap'
        ], 400);
    }


    private function AutoSaveDatabase($deviceName, $lat, $lng)  // untuk save ke DB setiap 1 jam
    {
        $latest = Tracking::where('device_name', $deviceName)
                    ->latest('waktu')
                    ->first();

        if (!$latest || $latest->waktu < now()->subHours(1)) { // simpan tiap 1 jam
            Tracking::create([
                'device_name' => $deviceName,
                'lat' => $lat,
                'lng' => $lng,
                'waktu' => Carbon::now(),
            ]);
        }
    }

    public function getLocation()   // untuk menampilkan data terakhir dari alat tracking
    {
        $lat = Cache::get('latest_lat');
        $lng = Cache::get('latest_lng');
        $device = Cache::get('latest_device');

        if ($device && $lat && $lng) {
            return response()->json([
                'device' => $device,
                'lat' => $lat,
                'lng' => $lng,
                'status' => 'success',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Belum ada data koordinat.',
        ], 404);
    }

    public function ajaxTrackingJSON()  // untuk menampilkan table riwayat tracking secara real-time
    {
        $trackings = Tracking::latest('waktu')->take(100)->get();
        return response()->json([
            'data' => $trackings->map(function ($t, $i) {
                return [
                    'no' => $i + 1,
                    'device_name' => $t->device_name,
                    'lat' => $t->lat,
                    'lng' => $t->lng,
                    'waktu' => \Carbon\Carbon::parse($t->waktu)->translatedFormat('l, d F Y H:i'),
                    'view' => '<a href="https://www.google.com/maps?q=' . $t->lat . ',' . $t->lng . '" target="_blank" class="btn btn-sm btn-primary">Lihat di Maps</a>',
                ];
            })
        ]);
    }

    public function dijkstraRoute()
    {
        $graphService = new GraphService();
        $dijkstra = new DijkstraService();

        $graph = $graphService->getGraph();
        $coords = $graphService->getCoordinates();

        $start = 'A';
        $end   = 'D';

        $path = $dijkstra->shortestPath($graph, $start, $end);

        $route = [];
        foreach ($path as $node) {
            $route[] = [
                'lat' => $coords[$node][0],
                'lng' => $coords[$node][1],
            ];
        }

        return response()->json([
            'path' => $path,
            'route' => $route
        ]);
    }

}
