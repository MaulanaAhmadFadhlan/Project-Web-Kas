<?php
// BACKEND/admin/laporan.php (versi fix sesuai struktur database kasdesa)
require_once('../helpers/koneksi.php');

$pemasukan_q = "
  SELECT p.tanggal, p.sumber AS keterangan, p.jumlah
  FROM pemasukan p
  ORDER BY p.tanggal DESC
";
$res1 = $conn->query($pemasukan_q);

$pengeluaran_q = "
  SELECT tanggal, keperluan AS keterangan, jumlah
  FROM pengeluaran
  ORDER BY tanggal DESC
";
$res2 = $conn->query($pengeluaran_q);

$total_pemasukan = 0;
$pemasukan_rows = [];
while ($r = $res1->fetch_assoc()) {
    $pemasukan_rows[] = $r;
    $total_pemasukan += (float)$r['jumlah'];
}

$total_pengeluaran = 0;
$pengeluaran_rows = [];
while ($r = $res2->fetch_assoc()) {
    $pengeluaran_rows[] = $r;
    $total_pengeluaran += (float)$r['jumlah'];
}

$saldo = $total_pemasukan - $total_pengeluaran;
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8" />
<title>Laporan Keuangan - Kas RT</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
  body { font-family: Poppins, sans-serif; padding: 20px; background-color: #f8fbff; }
  h3 { color: #1565c0; text-align: center; }
  .table thead { background: #e3f2fd; }
</style>
</head>
<body>
  <h3 class="fw-bold">Laporan Keuangan Kas RT</h3>

  <h5 class="mt-4">Data Pemasukan</h5>
  <table class="table table-bordered table-sm">
    <thead>
      <tr>
        <th>Tanggal</th>
        <th>Keterangan</th>
        <th class="text-end">Jumlah (Rp)</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($pemasukan_rows)): foreach($pemasukan_rows as $row): ?>
        <tr>
          <td><?= htmlspecialchars($row['tanggal']) ?></td>
          <td><?= htmlspecialchars($row['keterangan']) ?></td>
          <td class="text-end"><?= number_format($row['jumlah'], 0, ',', '.') ?></td>
        </tr>
      <?php endforeach; else: ?>
        <tr><td colspan="3" class="text-center">Belum ada data pemasukan</td></tr>
      <?php endif; ?>
    </tbody>
    <tfoot>
      <tr class="table-success fw-bold">
        <td colspan="2">Total Pemasukan</td>
        <td class="text-end">Rp <?= number_format($total_pemasukan, 0, ',', '.') ?></td>
      </tr>
    </tfoot>
  </table>

  <h5 class="mt-4">Data Pengeluaran</h5>
  <table class="table table-bordered table-sm">
    <thead>
      <tr>
        <th>Tanggal</th>
        <th>Keterangan</th>
        <th class="text-end">Jumlah (Rp)</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($pengeluaran_rows)): foreach($pengeluaran_rows as $row): ?>
        <tr>
          <td><?= htmlspecialchars($row['tanggal']) ?></td>
          <td><?= htmlspecialchars($row['keterangan']) ?></td>
          <td class="text-end"><?= number_format($row['jumlah'], 0, ',', '.') ?></td>
        </tr>
      <?php endforeach; else: ?>
        <tr><td colspan="3" class="text-center">Belum ada data pengeluaran</td></tr>
      <?php endif; ?>
    </tbody>
    <tfoot>
      <tr class="table-warning fw-bold">
        <td colspan="2">Total Pengeluaran</td>
        <td class="text-end">Rp <?= number_format($total_pengeluaran, 0, ',', '.') ?></td>
      </tr>
    </tfoot>
  </table>

  <div class="alert alert-info text-center fw-bold mt-4">
    Saldo Akhir: Rp <?= number_format($saldo, 0, ',', '.') ?>
  </div>

  <div class="text-center mt-3">
    <button class="btn btn-primary" onclick="window.print()">Cetak / Simpan PDF</button>
  </div>
</body>
</html>
