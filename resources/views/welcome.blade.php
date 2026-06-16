<!-- resources/views/welcome.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>AAROGYA - तपाईको स्वास्थ्य सहयोगी</title>
    <style>
        .logo {
            max-width: 200px;
            height: auto;
            margin: 20px auto;
            display: block;
        }
        .nav {
            background: #2E8B57;
            padding: 15px;
            text-align: center;
        }
        .nav a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="nav">
        <img src="{{ asset('images/logo.png') }}" alt="AAROGYA Logo" class="logo">
        <a href="/">गृहपृष्ठ</a>
        <a href="/symptom-checker">लक्षण जाँच</a>
        <a href="/doctors">डाक्टरहरू</a>
    </div>
    
    <div style="text-align: center; padding: 50px;">
        <h1>🏥 AAROGYA मा स्वागत छ!</h1>
        <p>तपाईको AI-सहयोगी स्वास्थ्य सहयोगी</p>
        
        <div style="margin-top: 30px;">
            <a href="/symptom-checker" style="background: #2E8B57; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; margin: 10px;">
                लक्षण जाँच गर्नुहोस्
            </a>
            <a href="/doctors" style="background: #4169E1; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; margin: 10px;">
                डाक्टर भेट्नुहोस्
            </a>
        </div>
    </div>
</body>
</html>