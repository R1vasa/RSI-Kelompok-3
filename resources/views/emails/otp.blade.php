<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Pengaturan dasar halaman email -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Judul email (tidak ditampilkan di isi pesan, tapi untuk metadata email) -->
    <title>Verify OTP</title>
</head>

<!-- Gaya dasar email menggunakan inline CSS agar kompatibel di semua email client -->

<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f6f6f6;">

    <!-- STRUKTUR EMAIL UTAMA (TABLE LAYOUT) Email marketing & notifikasi biasanya memakai <table> karena lebih kompatibel di Gmail, Outlook, Yahoo, dll. -->
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #f6f6f6;">
        <tr>
            <!-- Sel utama: semua konten email di dalamnya -->
            <td align="center" style="padding: 40px 0;">

                <!-- Kartu email (kotak putih di tengah) -->
                <table role="presentation" cellpadding="0" cellspacing="0"
                    style="max-width: 400px; width: 100%; background: #ffffff; border: 1px solid #ddd; border-radius: 8px; padding: 20px;">

                    <!-- Bagian Header Email -->
                    <tr>
                        <td align="center" style="font-size: 20px; font-weight: bold; color: #333;">
                            <!-- Nama pengguna diambil dari variabel $user->nama -->
                            Halo {{ $user->nama }}!
                        </td>
                    </tr>

                    <!-- Pesan pembuka -->
                    <tr>
                        <td align="center" style="padding: 10px 0; color: #555;">
                            Gunakan kode OTP berikut untuk verifikasi akun Anda:
                        </td>
                    </tr>

                    <!-- Kode OTP Utama -->
                    <tr>
                        <td align="center"
                            style="font-size: 36px; font-weight: bold; letter-spacing: 4px; color: #000;">
                            <!-- Nilai OTP dikirim dari controller -->
                            {{ $otp }}
                        </td>
                    </tr>

                    <!-- Informasi durasi / masa berlaku OTP -->
                    <tr>
                        <td align="center" style="padding: 10px 0; color: #555;">
                            Kode ini berlaku selama <strong>10 menit</strong>.
                        </td>
                    </tr>

                    <!-- Footer Email -->
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
