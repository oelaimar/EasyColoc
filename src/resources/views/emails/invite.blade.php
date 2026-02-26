<!DOCTYPE html>
<html>
<head>
    <style>
        .button { background: #4F46E5; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; }
    </style>
</head>
<body>
<h1>🏠 Invitation to join {{ $colocation->name }}</h1>
<p>Your roommate invited you to join their colocation on our app.</p>
<p>To join, use this invitation token:</p>
<h2 style="color: #4F46E5;">{{ $colocation->invite_token }}</h2>
<p>Paste this code into the "Join House" section of your dashboard.</p>
</body>
</html>
