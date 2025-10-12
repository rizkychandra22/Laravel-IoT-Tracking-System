<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Tracking;
use Carbon\Carbon;

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

    public function storeLocation(Request $request) // fungsi untuk menyimpan data lokasi ke cache dan ke DB
    {
        if ($request->has(['lat', 'lng', 'device_name'])) {
            $lat = $request->lat;
            $lng = $request->lng;
            $device = $request->device_name;

            // Simpan ke cache (untuk realtime peta)
            Cache::put('latest_lat', $lat, now()->addMinutes(10));
            Cache::put('latest_lng', $lng, now()->addMinutes(10));
            Cache::put('latest_device', $device, now()->addMinutes(10));

            // Simpan ke database jika terakhir lebih dari 1 jam
            $this->AutoSaveDatabase($device, $lat, $lng);

            return response()->json([
                'device' => $device,
                'status' => 'success',
                'message' => 'Koordinat berhasil diproses',
            ]);
        }

        return response()->json([
            'device' => $request->device_name ?? null,
            'status' => 'error',
            'message' => 'Parameter lat/lng/device tidak lengkap',
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
}
