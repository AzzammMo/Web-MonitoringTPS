<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Tempat Pembuangan Sampah</title>
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9a6u80Myri4AuofscXxEnWPSdD8/j+A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
</head>
<body>

<nav class="navbar">
    <div class="logo">
        <a href="{{ route('dashboard') }}">
            <img src="{{ asset('image/iconauth.png') }}" alt="Logo"> 
        </a>
    </div>
    <div class="nav-links">
        <a href="{{ route('dashboard') }}" class="nav-link">Monitoring Tempat Pembuangan Sampah</a>
    </div>
    <div class="user-dropdown">
        @auth
            <div class="user-name" style="cursor: pointer;">
                {{ Auth::user()->name }}
                <i class="fas fa-caret-down"></i> </div>
            <div class="dropdown-content">
                <a href="{{ route('profile.edit') }}">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a onclick="event.preventDefault(); this.closest('form').submit();">Log Out</a>
                </form>
            </div>
        @else
            <a href="{{ route('login') }}" class="nav-link">Log In</a>
            <a href="{{ route('register') }}" class="nav-link">Register</a>
        @endauth
    </div>
</nav>

</body>
</html>
