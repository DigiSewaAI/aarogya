<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Clinic Account Approved</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8fafc; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 16px; padding: 40px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: #0EA5E9; margin: 0; }
        .content { color: #334155; line-height: 1.8; }
        .badge-verified { display: inline-block; background: #22c55e; color: white; padding: 4px 16px; border-radius: 20px; font-size: 14px; font-weight: bold; }
        .button { display: inline-block; background: #0EA5E9; color: white; padding: 12px 30px; border-radius: 8px; text-decoration: none; margin-top: 20px; }
        .button-outline { display: inline-block; background: transparent; color: #0EA5E9; padding: 10px 28px; border-radius: 8px; text-decoration: none; margin-top: 10px; border: 2px solid #0EA5E9; }
        .footer { text-align: center; color: #94a3b8; font-size: 12px; margin-top: 30px; border-top: 1px solid #e2e8f0; padding-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>❤️ AAROGYA</h1>
        </div>
        <div class="content">
            <h2>Welcome to AAROGYA, {{ $clinic->name }}! 👋</h2>

            <p>We are pleased to inform you that your clinic account has been <strong>verified and approved</strong>.</p>

            <div style="background: #ecfdf5; padding: 20px; border-radius: 12px; margin: 20px 0; border-left: 4px solid #22c55e;">
                <p style="margin: 0;"><span class="badge-verified">✓ Verified Clinic</span></p>
                <p style="margin-top: 10px; color: #64748b;">You can now manage doctors and appointments through your dashboard.</p>
            </div>

            <p><strong>Your Clinic Details:</strong></p>
            <ul style="color: #334155; line-height: 1.8;">
                <li><strong>Name:</strong> {{ $clinic->name }}</li>
                <li><strong>Address:</strong> {{ $clinic->address }}</li>
                @if($clinic->phone)
                    <li><strong>Phone:</strong> {{ $clinic->phone }}</li>
                @endif
            </ul>

            <p>To get started, please log in to your dashboard:</p>

            <a href="{{ $dashboard_url }}" class="button">Go to Dashboard</a>
            <br>
            <a href="{{ $login_url }}" class="button-outline">Login</a>
        </div>
        <div class="footer">
            <p>© 2026 AAROGYA. All Rights Reserved.</p>
            <p>Need help? Contact us at support@aarogya.com</p>
        </div>
    </div>
</body>
</html>