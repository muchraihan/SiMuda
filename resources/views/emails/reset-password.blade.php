<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Kata Sandi</title>
</head>
<body style="margin:0; padding:0; background-color:#f7f7f7; font-family:Arial, sans-serif; color:#111827;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f7f7f7; padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px; background-color:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.06);">
                    <tr>
                        <td style="padding:24px; text-align:center; background-color:#f0fdf4;">
                            <img src="{{ $logoUrl }}" alt="Logo SMP Muhammadiyah 2 Kartasura" style="height:64px; width:auto;">
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:28px 32px;">
                            <h1 style="margin:0 0 12px; font-size:22px; color:#111827;">Permintaan Reset Kata Sandi</h1>
                            <p style="margin:0 0 16px; font-size:14px; color:#374151; line-height:1.6;">
                                Halo{{ isset($user->name) ? ' '.$user->name : '' }},
                            </p>
                            <p style="margin:0 0 16px; font-size:14px; color:#374151; line-height:1.6;">
                                Kami menerima permintaan untuk mengatur ulang kata sandi akun Anda. Silakan klik tombol di bawah untuk melanjutkan.
                            </p>
                            <p style="text-align:center; margin:24px 0;">
                                <a href="{{ $url }}" style="background-color:#16a34a; color:#ffffff; text-decoration:none; padding:12px 20px; border-radius:8px; display:inline-block; font-weight:bold;">
                                    Reset Kata Sandi
                                </a>
                            </p>
                            <p style="margin:0 0 16px; font-size:14px; color:#374151; line-height:1.6;">
                                Tautan ini akan kedaluwarsa dalam {{ $expire }} menit.
                            </p>
                            <p style="margin:0 0 16px; font-size:14px; color:#374151; line-height:1.6;">
                                Jika Anda tidak meminta reset kata sandi, abaikan email ini.
                            </p>
                            <p style="margin:0; font-size:12px; color:#6b7280; line-height:1.6;">
                                Salam hangat,<br>
                                Perpustakaan SMP Muhammadiyah 2 Kartasura
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:16px 24px; text-align:center; background-color:#f9fafb; font-size:12px; color:#9ca3af;">
                            © {{ date('Y') }} Perpustakaan SMP Muhammadiyah 2 Kartasura
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
