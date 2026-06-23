<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Welcome to AAROGYA</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8fafc; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 16px; padding: 40px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: #0EA5E9; margin: 0; }
        .content { color: #334155; line-height: 1.8; }
        .button { display: inline-block; background: #0EA5E9; color: white; padding: 12px 30px; border-radius: 8px; text-decoration: none; margin-top: 20px; }
        .footer { text-align: center; color: #94a3b8; font-size: 12px; margin-top: 30px; border-top: 1px solid #e2e8f0; padding-top: 20px; }
        .role-badge { display: inline-block; background: #e2e8f0; padding: 4px 12px; border-radius: 20px; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>❤️ AAROGYA</h1>
        </div>
        <div class="content">
            <h2>Welcome, {{ $name }}! 👋</h2>
            <p>Thank you for joining <strong>AAROGYA</strong> - Nepal's trusted healthcare platform.</p>
            <p>You have registered as: <span class="role-badge">{{ ucfirst($role) }}</span></p>
            
            <p>With AAROGYA, you can:</p>
            <ul>
                <li>Book appointments with verified doctors</li>
                <li>Manage your health records securely</li>
                <li>Access healthcare services 24/7</li>
            </ul>
            
            <a href="{{ $url }}" class="button">Get Started</a>
        </div>
        <div class="footer">
            <p>© 2026 AAROGYA. All Rights Reserved.</p>
            <p>Need help? Contact us at support@aarogya.com</p>
        </div>
    </div>
</body>
</html>