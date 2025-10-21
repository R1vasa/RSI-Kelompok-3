<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Verify OTP</title>
</head>

<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f6f6f6;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #f6f6f6;">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table role="presentation" cellpadding="0" cellspacing="0"
                    style="max-width: 400px; width: 100%; background: #ffffff; border: 1px solid #ddd; border-radius: 8px; padding: 20px;">
                    <tr>
                        <td align="center" style="font-size: 20px; font-weight: bold; color: #333;">
                            Halo {{ $user->nama }}!
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding: 10px 0; color: #555;">
                            Gunakan kode OTP berikut untuk verifikasi akun Anda:
                        </td>
                    </tr>
                    <tr>
                        <td align="center"
                            style="font-size: 36px; font-weight: bold; letter-spacing: 4px; color: #000;">
                            {{ $otp }}
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding: 10px 0; color: #555;">
                            Kode ini berlaku selama <strong>10 menit</strong>.
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding-top: 10px; color: #333;">
                            Terima kasih telah menggunakan <strong>FinTrack!</strong>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>


</html>
