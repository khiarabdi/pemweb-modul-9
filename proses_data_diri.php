<?php
// Pastikan data diterima melalui metode POST.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: form_data_diri.html');
    exit;
}

$nama = trim($_POST['nama'] ?? '');
$tanggalInput = $_POST['tanggal_lahir'] ?? '';
$hobiInput = $_POST['hobi'] ?? [];

// Daftar bulan dalam bahasa Indonesia.
$bulanIndonesia = [
    1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
];

// Validasi tanggal sekaligus mengubahnya menjadi "7 Januari 2006".
$tanggal = DateTime::createFromFormat('!Y-m-d', $tanggalInput);
if ($nama === '' || !$tanggal || $tanggal->format('Y-m-d') !== $tanggalInput) {
    http_response_code(400);
    exit('Nama atau tanggal lahir tidak valid. <a href="form_data_diri.html">Kembali ke Form</a>');
}
$tanggalIndonesia = (int)$tanggal->format('d') . ' ' .
    $bulanIndonesia[(int)$tanggal->format('m')] . ' ' . $tanggal->format('Y');

// Hanya terima pilihan hobi yang tersedia di formulir.
$pilihanHobi = ['Membaca', 'Olahraga', 'Musik', 'Lainnya'];
$hobiTerpilih = is_array($hobiInput)
    ? array_values(array_intersect($pilihanHobi, $hobiInput))
    : [];
$teksHobi = count($hobiTerpilih) > 0
    ? implode(', ', $hobiTerpilih)
    : 'Tidak ada hobi yang dipilih';

// Cegah HTML yang dimasukkan pengguna dieksekusi di halaman hasil.
function aman($teks) {
    return htmlspecialchars($teks, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hasil Data Diri</title>
  <style>
    * { box-sizing: border-box; }
    body { margin: 0; font-family: Arial, sans-serif; background: #f4f7fb; color: #253247; }
    .card { max-width: 560px; margin: 65px auto; background: white; padding: 32px;
            border-radius: 12px; box-shadow: 0 8px 25px rgba(36, 60, 95, .1); }
    h1 { font-size: 25px; margin: 0 0 24px; padding-bottom: 15px; border-bottom: 1px solid #e2e7ef; }
    .data { display: grid; grid-template-columns: 150px 1fr; gap: 18px; margin: 17px 0; }
    .data strong { font-weight: 600; }
    a { display: inline-block; margin-top: 25px; color: #1877d2; text-decoration: none; }
    a:hover { text-decoration: underline; }
    @media (max-width: 600px) { .card { margin: 20px 14px; padding: 24px; }
      .data { grid-template-columns: 120px 1fr; gap: 10px; } }
  </style>
</head>
<body>
  <main class="card">
    <h1>Tampilan Data Anda</h1>
    <div class="data"><strong>Nama Lengkap:</strong><span><?= aman($nama) ?></span></div>
    <div class="data"><strong>Tanggal Lahir:</strong><span><?= aman($tanggalIndonesia) ?></span></div>
    <div class="data"><strong>Hobi:</strong><span><?= aman($teksHobi) ?></span></div>
    <a href="form_data_diri.html">&laquo; Kembali ke Form</a>
  </main>
</body>
</html>
