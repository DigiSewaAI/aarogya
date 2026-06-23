<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Appointment Booked</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8fafc; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 16px; padding: 40px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: #0EA5E9; margin: 0; }
        .content { color: #334155; line-height: 1.8; }
        .appointment-details { background: #f1f5f9; padding: 20px; border-radius: 12px; margin: 20px 0; }
        .button { display: inline-block; background: #0EA5E9; color: white; padding: 12px 30px; border-radius: 8px; text-decoration: none; margin-top: 20px; }
        .footer { text-align: center; color: #94a3b8; font-size: 12px; margin-top: 30px; border-top: 1px solid #e2e8f0; padding-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>❤️ AAROGYA</h1>
        </div>
        <div class="content">
            <h2>Appointment Booked!</h2>
            <p>Dear {{ $patient->name }},</p>
            <p>Your appointment has been booked successfully.</p>

            <div class="appointment-details">
                <p><strong>Doctor:</strong> Dr. {{ $doctor->name }}</p>
                <p><strong>Specialization:</strong> {{ $doctor->specialization }}</p>
                <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}</p>
                <p><strong>Time:</strong> {{ \Carbon\Carbon::parse($time)->format('g:i A') }}</p>
                <p><strong>Status:</strong> Pending (Waiting for doctor's approval)</p>
            </div>

            @if($doctor->clinic)
                <p><strong>Clinic:</strong> {{ $doctor->clinic->name }}</p>
                <p><strong>Address:</strong> {{ $doctor->clinic->address }}</p>
            @endif

            <p>You will receive a confirmation email once the doctor approves your appointment.</p>

            <a href="{{ route('patient.appointments') }}" class="button">View My Appointments</a>
        </div>
        <div class="footer">
            <p>© 2026 AAROGYA. All Rights Reserved.</p>
            <p>Need help? Contact us at support@aarogya.com</p>
        </div>
    </div>
</body>
</html>