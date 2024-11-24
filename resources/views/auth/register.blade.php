<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar</title>
    <link rel="stylesheet" href="{{asset('css/styleRegister.css')}}"> <!-- Pastikan CSS Anda terhubung -->
</head>
<body>
    <div class="container">
       
        <div class="registration form">
            <header class="header">Daftar Akun</header>
            <img src="{{ asset('/imageAuth/reg_back.png') }}" alt="Register Image" class="login-image"> <!-- Gambar di atas -->

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name">{{ __('Nama') }}</label>
                    <input id="name" type="text" name="name" required autofocus autocomplete="name">
                    @error('name')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email Address -->
                <div class="mt-4">
                    <label for="email">{{ __('Email') }}</label>
                    <input id="email" type="email" name="email" required autocomplete="username">
                    @error('email')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <label for="password">{{ __('Password') }}</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password">
                    @error('password')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <label for="password_confirmation">{{ __('Konfirmasi Password') }}</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
                    @error('password_confirmation')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mt-4"> 
                    <label for="role">{{ __('Role') }}</label>
                    <select name="role" id="role" required>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                    @error('role')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex items-center justify-between mt-4">
                    <div class="signup">
                        <span>Apakah Sudah Memiliki Akun?
                            <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Masuk</a>
                        </span>
                    </div>

                    <button type="submit" class="button">Daftar</button> <!-- Tombol Registrasi -->
                </div>
            </form>
        </div>
    </div>
</body>
</html>
