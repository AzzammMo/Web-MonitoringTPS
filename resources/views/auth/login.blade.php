<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/styleLogin.css') }}">
    <style>
        .status-message {
            color: green; 
            margin-bottom: 1rem; 
            text-align: center; 
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login form">
            <header>Masuk Akun</header>
            <img src="{{ asset('/imageAuth/log_back.png') }}" alt="" class="login-image">

            @if (session('status'))
                <div class="status-message" id="status-message">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <input type="email" name="email" placeholder="Masukkan Email" required autofocus autocomplete="username">
                <x-input-error :messages="$errors->get('email')" class="mt-2 input-error" />

                <input type="password" name="password" placeholder="Masukkan Password" required autocomplete="current-password">
                <x-input-error :messages="$errors->get('password')" class="mt-2 input-error"/>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">Lupa Password?</a>
                @endif
                <button type="submit" class="button">Masuk</button>
            </form>
            <div class="signup">
                <span>Apakah Belum Memiliki Akun? <a href="{{ route('register') }}">Daftar</a></span>
            </div>
        </div>
    </div>

    @if (session('status'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Sukses!',
                text: '{{ session('status') }}', 
                icon: 'success',
                confirmButtonText: 'OK'
            });
        });
    </script>
@endif
</body>
</html>
