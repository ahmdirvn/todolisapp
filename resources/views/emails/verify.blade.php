<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Email Verification - Todolisap</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #6f42c1, #9d6fe7);
            font-family: 'Poppins', sans-serif;
        }
        .card {
            background: #fff;
            border-radius: 16px;
            padding: 40px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }
        .btn-gradient {
            background: linear-gradient(135deg, #6f42c1, #9d6fe7);
            border: none;
            border-radius: 12px;
            padding: 12px 20px;
            font-size: 16px;
            font-weight: 600;
            color: #fff;
            transition: 0.3s;
        }
        .btn-gradient:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">
    <div class="card text-center">
        <div class="text-3xl font-bold mb-4 text-[#6f42c1]">Todolisap</div>

        @if ($status === 'success')
            <h2 class="text-2xl font-semibold text-green-600 mb-2">✅ Success</h2>
            <p class="text-gray-700">{{ $message }}</p>
            <a href="{{ route('login') }}" class="btn-gradient mt-6 inline-block">Go to Login</a>

        @elseif ($status === 'warning')
            <h2 class="text-2xl font-semibold text-yellow-500 mb-2">⚠️ Warning</h2>
            <p class="text-gray-700">{{ $message }}</p>

        @else
            <h2 class="text-2xl font-semibold text-red-600 mb-2">❌ Failed</h2>
            <p class="text-gray-700">{{ $message }}</p>
        @endif
    </div>
</body>
</html>
