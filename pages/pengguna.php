<?php include 'config/koneksi.php'; ?>
<?php
// Define BASE_URL early, assuming index.php is in sarpras-smkn1ptb folder
// If your project is directly in htdocs, remove /sarpras-smkn1ptb
define('BASE_URL', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/sarpras-smkn1ptb/");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Bangunan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<div class="container-fluid">
  <h3 class="mb-4">Data Pengguna</h3>

  <form action="proses/tambah_barang.php?jenis=pengguna" method="post" class="row g-3 mb-4">
    <div class="col-md-4">
      <input type="text" name="nama" class="form-control" placeholder="Nama" required>
    </div>
    <div class="col-md-3">
      <input type="text" name="username" class="form-control" placeholder="Username" required>
    </div>
    <div class="col-md-3">
      <input type="password" name="password" class="form-control" placeholder="Password" required>
    </div>
    <div class="col-md-2">
      <select name="level" class="form-select">
        <option value="admin">Admin</option>
        <option value="petugas">Petugas</option>
      </select>
    </div>
    <div class="col-12">
      <button type="submit" class="btn btn-primary">Tambah</button>
    </div>
  </form>

  <table class="table table-bordered table-hover">
    <thead class="table-dark">
      <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Username</th>
        <th>Level</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $no = 1;
      $query = mysqli_query($koneksi, "SELECT * FROM pengguna");
      while ($data = mysqli_fetch_assoc($query)) {
      ?>
      <tr>
        <td><?= $no++ ?></td>
        <td><?= $data['nama'] ?></td>
        <td><?= $data['username'] ?></td>
        <td><?= $data['level'] ?></td>
        <td>
          <a href="proses/hapus_barang.php?jenis=pengguna&id=<?= $data['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus?')">Hapus</a>
        </td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
