<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Konfirmasi Newsletter</title>
</head>
<body style="font-family: Arial, sans-serif; color: #0f172a;">
    <h2>Halo!</h2>
    <p>Terima kasih sudah mendaftar newsletter ABC Blog dengan email <strong>{{ $email }}</strong>.</p>
    <p>Silakan konfirmasi dengan klik tombol berikut:</p>
    <p>
        <a href="{{ $confirmUrl }}" style="display: inline-block; background: #4f46e5; color: #fff; text-decoration: none; padding: 10px 16px; border-radius: 8px;">
            Konfirmasi Newsletter
        </a>
    </p>
    <p>Jika Anda tidak merasa mendaftar, Anda bisa abaikan email ini atau berhenti berlangganan:</p>
    <p><a href="{{ $unsubscribeUrl }}">Unsubscribe</a></p>
</body>
</html>
