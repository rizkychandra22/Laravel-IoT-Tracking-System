<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Registrasi &mdash; Pengguna</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="/template-stisla/dist/assets/modules/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/template-stisla/dist/assets/modules/fontawesome/css/all.min.css">

    <!-- Template CSS -->
    <link rel="stylesheet" href="/template-stisla/dist/assets/css/style.css">
    <link rel="stylesheet" href="/template-stisla/dist/assets/css/components.css">
</head>

<body>
<div id="app">
<section class="section">
<div class="container mt-5">
<div class="row">
<div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">

<div class="card card-primary">
    <div class="card-header" style="margin-left: 200px;">
        <h4>Registrasi</h4>
    </div>

    <div class="card-body" style="margin-top:-35px">
        <hr>

        {{-- ALERT SUCCESS --}}
        @if(session('success'))
            <div id="successAlert" class="alert alert-success text-center">
                <strong>Berhasil</strong>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        {{-- FORM --}}
        <form method="POST" action="{{ route('register.store') }}">
            @csrf

            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="{{ old('username') }}">
                @error('username') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label>No. HP</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label>Nama Perangkat (GPS)</label>
                <input type="text" name="device_name" class="form-control" placeholder="ESP32-GPS-01">
                @error('device_name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="alert alert-info text-center">
                Password default = <b>Username</b>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-lg btn-block">
                    Daftar
                </button>
            </div>
        </form>

        <hr>
        <div class="text-center">
            <a href="{{ route('login') }}">Sudah punya akun? Login</a>
        </div>
    </div>
</div>

</div>
</div>
</div>
</section>
</div>

<!-- JS -->
<script src="/template-stisla/dist/assets/modules/jquery.min.js"></script>
<script src="/template-stisla/dist/assets/modules/bootstrap/js/bootstrap.min.js"></script>
<script>
    setTimeout(() => {
        let alert = document.getElementById('successAlert');
        if(alert){
            alert.style.opacity = 0;
            setTimeout(()=>alert.remove(),500);
        }
    },3000);
</script>
</body>
</html>
