<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - POS Gudang Sparepart</title>
    
    <!-- CSS Bawaan Kaiadmin -->
    <link rel="stylesheet" href="{{ asset('template/assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('template/assets/css/kaiadmin.min.css') }}" />
    
    <!-- CSS Custom Model Split-Screen -->
    <link rel="stylesheet" href="{{ asset('template/assets/css/login-custom.css') }}" />
</head>
<body class="login-body">

    <div class="login-wrapper">
        
        <!-- SISI KIRI: Form Input (Nama & PIN) -->
        <div class="login-form-side">
            <div class="mb-4">
                <h3 class="fw-bold text-dark mb-1">Masuk Sistem</h3>
                <p class="text-muted small">Silakan masukkan nama petugas dan PIN.</p>
            </div>
            
            @if(session('error'))
                <div class="alert alert-danger py-2 small fw-bold text-center">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                
                <div class="form-group mb-3">
                    <label class="fw-bold text-dark mb-2">Nama Petugas</label>
                    <input type="text" name="nama" class="form-control form-control-lg" 
                           placeholder="Masukkan Nama" required autofocus>
                </div>
                
                <div class="form-group mb-4">
                    <label class="fw-bold text-dark mb-2">PIN Akses</label>
                    <input type="password" name="password" class="form-control form-control-lg" 
                           placeholder="Masukkan Password" required>
                </div>

                <button type="submit" class="btn btn-login-custom w-100 fs-5 py-3 shadow-sm">
                    MASUK DASHBOARD
                </button>
            </form>
        </div>

        <!-- SISI KANAN: Banner Gradasi Biru Estetik -->
        <div class="login-banner-side">
            <div style="font-size: 3.5rem; margin-bottom: 15px;">🛠️</div>
            <h2>MagangHub Gudang</h2>
            <p>Sistem manajemen pencatatan peminjaman sparepart alat pabrik secara transparan, akurat, dan terintegrasi.</p>
        </div>

    </div>

</body>
</html>