<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code - {{ $doctor->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f8fafc; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; padding: 20px; }
        .qr-container { background: white; border-radius: 30px; padding: 40px; max-width: 400px; width: 100%; box-shadow: 0 20px 60px rgba(0,0,0,0.1); text-align: center; }
        .qr-image { background: white; padding: 20px; border-radius: 20px; border: 2px solid #e2e8f0; display: inline-block; }
        @media (max-width: 480px) { .qr-container { padding: 20px; } }
    </style>
</head>
<body>
    <div class="qr-container">
        <h1 class="text-2xl font-bold text-cyan-600">AAROGYA</h1>
        <div class="qr-image mt-4">
            {!! $qrCode !!}
        </div>
        <h2 class="text-xl font-bold text-slate-800 mt-4">{{ $doctor->name }}</h2>
        <p class="text-slate-500">{{ $doctor->specialization }}</p>
        <p class="text-sm text-slate-400 mt-2">{{ $doctor->clinic_name ?? '' }}</p>
        <div class="mt-6">
            <a href="{{ route('doctor.show', $doctor->id) }}" 
               class="bg-cyan-600 text-white px-6 py-3 rounded-xl hover:bg-cyan-700 transition inline-block">
                👤 View Full Profile
            </a>
        </div>
        <p class="text-xs text-slate-400 mt-4">Scan with any QR reader</p>
    </div>
</body>
</html>