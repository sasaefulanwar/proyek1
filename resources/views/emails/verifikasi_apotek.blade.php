<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Verifikasi Akun MediFinder</title>
</head>
<body>
    <h3>Halo, {{ $admin->nama_penanggung_jawab ?? $admin->nama_apotek }}!</h3>

    <p>Akun Anda telah <b>disetujui</b> oleh admin MediFinder.</p>
    <p>Anda sekarang dapat login menggunakan akun Anda dengan detail berikut:</p>

    <ul>
        <li><b>Username:</b> {{ $admin->username }}</li>
        <li><b>Password:</b> {{ decrypt($admin->temp_password) }}</li>
    </ul>

    <p>Silakan login melalui tautan berikut:</p>
    <a href="{{ url('/login') }}">{{ url('/login') }}</a>

    <p>Terima kasih,<br>Tim MediFinder</p>
</body>
</html>
