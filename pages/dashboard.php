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
  <h3 class="mb-4">Dashboard</h3>
  <div class="row">
    <div class="col-md-3 mb-3">
      <div class="card bg-primary text-white shadow">
        <div class="card-body">
          <h5>Total Tanah</h5>
          <p>
            <?php
            include 'config/koneksi.php';
            $tanah = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tanah"));
            echo $tanah . " lokasi";
            ?>
          </p>
        </div>
      </div>
    </div>
    <div class="col-md-3 mb-3">
      <div class="card bg-success text-white shadow">
        <div class="card-body">
          <h5>Total Ruang</h5>
          <p>
            <?php
            $ruang = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM ruang"));
            echo $ruang . " ruang";
            ?>
          </p>
        </div>
      </div>
    </div>
    <div class="col-md-3 mb-3">
      <div class="card bg-warning text-white shadow">
        <div class="card-body">
          <h5>Total Aset</h5>
          <p>
            <?php
            $aset = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM asset"));
            echo $aset . " barang";
            ?>
          </p>
        </div>
      </div>
    </div>
    <div class="col-md-3 mb-3">
      <div class="card bg-danger text-white shadow">
        <div class="card-body">
          <h5>Total Peminjaman</h5>
          <p>
            <?php
            $peminjaman = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM peminjaman"));
            echo $peminjaman . " transaksi";
            ?>
          </p>
        </div>
      </div>
    </div>
    <div class="col-md-3 mb-3">
      <div class="card bg-info text-white shadow">
        <div class="card-body">
          <h5>Total Bangunan</h5>
          <p>
            <?php
            $bangunan = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM bangunan"));
            echo $bangunan . " bangunan";
            ?>
          </p>
        </div>
      </div>
    </div>
    <div class="col-md-3 mb-3">
      <div class="card bg-secondary text-white shadow">
        <div class="card-body">
          <h5>Total JIJ</h5>
          <p>
            <?php
            $jij = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM jij"));
            echo $jij . " item";
            ?>
          </p>
        </div>
      </div>
    </div>
    <div class="col-md-3 mb-3">
      <div class="card bg-dark text-white shadow">
        <div class="card-body">
          <h5>Total Aset Lain</h5>
          <p>
            <?php
            $aset_lain = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM aset_lain"));
            echo $aset_lain . " barang";
            ?>
          </p>
        </div>
      </div>
    </div>
    <div class="col-md-3 mb-3">
      <div class="card bg-light text-dark shadow">
        <div class="card-body">
          <h5>Total Sarana</h5>
          <p>
            <?php
            $sarana = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM sarana"));
            echo $sarana . " unit";
            ?>
          </p>
        </div>
      </div>
    </div>
    <div class="col-md-3 mb-3">
      <div class="card bg-success text-white shadow">
        <div class="card-body">
          <h5>Total BHP</h5>
          <p>
            <?php
            $bhp = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM bhp"));
            echo $bhp . " jenis";
            ?>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>