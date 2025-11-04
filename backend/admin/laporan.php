<?php
// backend/admin/laporan.php
include '../koneksi.php';

$pemasukan_query = "SELECT tanggal, nama, keterangan, jumlah FROM pemasukan ORDER BY tanggal DESC";
$pengeluaran_query = "SELECT tanggal, keterangan, jumlah FROM pengeluaran ORDER BY tanggal DESC";

$pemasukan_result = mysqli_query($conn, $pemasukan_query);
$pengeluaran_result = mysqli_query($conn, $pengeluaran_query);

$total_pemasukan = 0;
$total_pengeluaran = 0;

while ($p = mysqli_fetch_assoc($pemasukan_result)) {
    $data_pemasukan[] = $p;
    $total_pemasukan += $p['jumlah'];
}

while ($k = mysqli_fetch_assoc($pengeluaran_result)) {
    $data_pengeluaran[] = $k;
    $total_pengeluaran += $k['jumlah'];
}

$saldo = $total_pemasukan - $total_pengeluaran;
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Laporan Keuangan Kas RT</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { font-family: 'Poppins', sans-serif; background-color: #fff; padding: 20px; }
    h3 { color: #1565c0; text-align: center; margin-bottom: 20px; }
    table { margin-bottom: 30px; }
    .table thead { background-color: #e3f2fd; }
    .footer { text-align: center; margin-top: 40px; font-size: 14px; color: #555; }
  </style>
</head>
<body>

  <h3>Laporan Keuangan Kas RT</h3>

  <h5 class="fw-bold text-primary">Pemasukan</h5>
  <table class="table table-bordered text-center">
    <thead>
      <tr>
        <th>Tanggal</th><th>Nama</th><th>Keterangan</th><th>Jumlah (Rp)</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($data_pemasukan)): ?>
        <?php foreach ($data_pemasukan as $row): ?>
          <tr>
            <td><?= htmlspecialchars($row['tanggal']) ?></td>
            <td><?= htmlspecialchars($row['nama'] ?? '-') ?></td>
            <td><?= htmlspecialchars($row['keterangan']) ?></td>
            <td><?= number_format($row['jumlah'], 0, ',', '.') ?></td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="4">Belum ada data pemasukan</td></tr>
      <?php endif; ?>
    </tbody>
    <tfoot>
      <tr class="table-success fw-bold">
        <td colspan="3">Total Pemasukan</td>
        <td>Rp <?= number_format($total_pemasukan, 0, ',', '.') ?></td>
      </tr>
    </tfoot>
  </table>

  <h5 class="fw-bold text-danger">Pengeluaran</h5>
  <table class="table table-bordered text-center">
    <thead>
      <tr>
        <th>Tanggal</th><th>Keterangan</th><th>Jumlah (Rp)</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($data_pengeluaran)): ?>
        <?php foreach ($data_pengeluaran as $row): ?>
          <tr>
            <td><?= htmlspecialchars($row['tanggal']) ?></td>
            <td><?= htmlspecialchars($row['keterangan']) ?></td>
            <td><?= number_format($row['jumlah'], 0, ',', '.') ?></td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="3">Belum ada data pengeluaran</td></tr>
      <?php endif; ?>
    </tbody>
    <tfoot>
      <tr class="table-warning fw-bold">
        <td colspan="2">Total Pengeluaran</td>
        <td>Rp <?= number_format($total_pengeluaran, 0, ',', '.') ?></td>
      </tr>
    </tfoot>
  </table>

  <div class="alert alert-info text-center fw-bold fs-5">
    Saldo Akhir: Rp <?= number_format($saldo, 0, ',', '.') ?>
  </div>

  <div class="text-center">
    <button class="btn btn-primary" onclick="window.print()">Cetak / Simpan PDF</button>
  </div>

  <div class="footer">
    © <?= date('Y') ?> Kas RT — Sistem Keuangan RT
  </div>
</body>
</html>