<!DOCTYPE html>
<html>
<head>
    <title>Your OTP Code</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <div style="text-align: center; margin-bottom: 20px;">
            <h2 style="color: #00827F; margin: 0;">CareConnect</h2>
        </div>
        
        <p style="font-size: 16px; color: #333333;">Hello,</p>
        <p style="font-size: 16px; color: #333333;">You are receiving this email because we received a password reset request for your account.</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <span style="display: inline-block; padding: 15px 30px; font-size: 32px; font-weight: bold; letter-spacing: 5px; color: #00827F; background-color: #f0fdfa; border: 2px dashed #00827F; border-radius: 8px;">
                {{ $otp }}
            </span>
        </div>
        
        <p style="font-size: 14px; color: #666666;">Please enter this 6-digit code on the verification page to reset your password. This code will expire in 60 minutes.</p>
        
        <p style="font-size: 14px; color: #666666; margin-top: 30px;">If you did not request a password reset, no further action is required.</p>
        
        <hr style="border: none; border-top: 1px solid #eeeeee; margin: 30px 0;">
        <p style="font-size: 12px; color: #999999; text-align: center;">&copy; {{ date('Y') }} CareConnect. All rights reserved.</p>
    </div>
</body>
</html>
