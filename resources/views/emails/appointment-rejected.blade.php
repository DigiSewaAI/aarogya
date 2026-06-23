<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Appointment Rejected</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8fafc; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 16px; padding: 40px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: #0EA5E9; margin: 0; }
        .content { color: #334155; line-height: 1.8; }
        .appointment-details { background: #fef2f2; padding: 20px; border-radius: 12px; margin: 20px 0; border-left: 4px solid #ef4444; }
        .badge-rejected { display: inline-block; background: #ef4444; color: white; padding: 4px 16px; border-radius: 20px; font-size: 14px; font-weight: bold; }
        .reason-box { background: #f8fafc; padding: 15px; border-radius: 8px; margin: 15px 0; border: 1px solid #e2e8f0; }
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
            <h2>Appointment Rejected ❌</h2>
            <p>Dear {{ $patient->name }},</p>
            <p>We regret to inform you that your appointment with Dr. {{ $doctor->name }} has been <strong>rejected</strong>.</p>

            <div class="appointment-details">
                <p><strong>Doctor:</strong> Dr. {{ $doctor->name }}</p>
                <p><strong>Specialization:</strong> {{ $doctor->specialization }}</p>
                <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}</p>
                <p><strong>Time:</strong> {{ \Carbon\Carbon::parse($time)->format('g:i A') }}</p>
                <p><strong>Status:</strong> <span class="badge-rejected">Rejected</span></p>
            </div>

            @if($reason)
                <div class="reason-box">
                    <p><strong>Reason given by doctor:</strong></p>
                    <p style="color: #64748b;">{{ $reason }}</p>
                </div>
            @endif

            <p>You can try booking with another doctor or at a different time.</p>

            <a href="{{ route('doctors') }}" class="button">Find Another Doctor</a>
        </div>
        <div class="footer">
            <p>© 2026 AAROGYA. All Rights Reserved.</p>
            <p>Need help? Contact us at support@aarogya.com</p>
        </div>
    </div>
</body>
</html>