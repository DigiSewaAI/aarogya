<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code - {{ $doctor->name }}</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
        .container { max-width: 600px; margin: 0 auto; }
        .qr-wrapper { background: white; padding: 40px; border: 2px solid #ddd; border-radius: 20px; }
        img { max-width: 100%; }
        .doctor-name { font-size: 24px; font-weight: bold; margin-top: 20px; }
        .specialization { color: #666; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="qr-wrapper">
            <h1>AAROGYA</h1>
            <p class="doctor-name">{{ $doctor->name }}</p>
            <p class="specialization">{{ $doctor->specialization }}</p>
            <div style="margin: 20px 0;">
                {!! QrCode::size(250)->generate(route('doctor.show', $doctor->id)) !!}
            </div>
            <p style="color: #888; font-size: 12px;">Scan to view profile</p>
            <p style="color: #aaa; font-size: 10px; margin-top: 20px;">{{ route('doctor.show', $doctor->id) }}</p>
        </div>
        <div class="no-print" style="margin-top: 20px;">
            <button onclick="window.print()" style="padding: 10px 30px; background: #0891b2; color: white; border: none; border-radius: 10px; cursor: pointer; font-size: 16px;">
                🖨️ Print
            </button>
            <button onclick="window.close()" style="padding: 10px 30px; background: #ccc; color: #333; border: none; border-radius: 10px; cursor: pointer; font-size: 16px; margin-left: 10px;">
                Close
            </button>
        </div>
    </div>
    <script>
        // Auto-print when page loads
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>