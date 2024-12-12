<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Expired</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f8f9fa;
        }
        .expired-container {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="expired-container">
        <h1>Session Expired</h1>
        <p>Your session has expired due to inactivity. Please log in again.</p>
        <a href="{{ route('login') }}" class="btn btn-primary">Go to Login</a>
    </div>
</body>
</html>
