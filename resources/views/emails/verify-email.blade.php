<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Verify Your Email - POS System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .button {
            display: inline-block;
            background: #007bff;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Welcome to POS System!</h1>
        <p>Please verify your email address</p>
    </div>
    
    <div class="content">
        <h2>Hello {{ $user->first_name }}!</h2>
        
        <p>Thank you for registering with our POS System. To complete your registration, please verify your email address by clicking the button below:</p>
        
        <div style="text-align: center;">
            <a href="{{ $verificationUrl }}" class="button">Verify Email Address</a>
        </div>
        
        <p>If the button doesn't work, you can copy and paste this link into your browser:</p>
        <p style="word-break: break-all; color: #007bff;">{{ $verificationUrl }}</p>
        
        <p>This verification link will expire in 60 minutes.</p>
        
        <p>If you didn't create an account, no further action is required.</p>
        
        <p>Best regards,<br>
        The POS System Team</p>
    </div>
    
    <div class="footer">
        <p>This email was sent to {{ $user->email }}. If you have any questions, please contact our support team.</p>
    </div>
</body>
</html> 