@extends('home.v_layouts')

@section('title', 'Riwayat Lokasi')

@section('breadcrumb')
    @php
        $items = [
            ['name' => 'Dashboard', 'url' => route('indexAdmin')],
            ['name' => 'Riwayat Lokasi', 'url' => ''],
        ];
    @endphp
    @include('components.breadcrumb', ['items' => $items])
@endsection

@section('content')
    <h2 class="section-title">@yield('title')</h2>
    <div class="card">
        <div class="card-header">
            <h4>Riwayat Lokasi Terakhir</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tabel-tracking" class="table table-bordered table-hover table-striped">
                    <thead class="bg-light"></thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        let table;
        $(document).ready(function () {
            table = $('#tabel-tracking').DataTable({
                ajax: '{{ route('ajaxTrackingJSON') }}',
                columns: [
                    { data: 'no', title: 'No', width: '10' },
                    { data: 'device_name', title: 'Device Name' },
                    { data: 'lat', title: 'LAT' },
                    { data: 'lng', title: 'LNG' },
                    { data: 'waktu', title: 'Waktu' },
                    { data: 'view', title: 'View Maps', orderable: false, searchable: false },
                ],
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    zeroRecords: "Tidak ditemukan data",
                    info: "Menampilkan data _START_ sampai _END_ dari total _TOTAL_ data",
                    infoEmpty: "Tidak ada data tersedia",
                    infoFiltered: "(difilter dari _MAX_ total data)",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Berikutnya",
                        previous: "Sebelumnya"
                    },
                }
            });
            setInterval(() => {     // ⏱ Refresh otomatis setiap 10 detik
                table.ajax.reload(null, false);     // false = tidak reset halaman
            }, 10000);
        });
    </script>
@endpush