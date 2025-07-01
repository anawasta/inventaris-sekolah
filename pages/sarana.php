<?php include 'config/koneksi.php'; ?>
<?php
define('BASE_URL', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/sarpras-smkn1ptb/");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Sarana</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<div class="container-fluid">
  <h3 class="mb-4">Data Sarana</h3>

  <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#tambahSaranaModal">
    Tambah Data Sarana
  </button>

  <table class="table table-bordered table-hover">
    <thead class="table-dark">
      <tr>
        <th>No</th>
        <th>Nama Sarana</th>
        <th>Kode Sarana</th>
        <th>Lokasi</th>
        <th>Jumlah</th>
        <th>Kondisi</th>
        <th>Tahun Perolehan</th>
        <th>Keterangan</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $no = 1;
      $query = mysqli_query($koneksi, "SELECT * FROM sarana");
      while ($data = mysqli_fetch_assoc($query)) {
          $tanggal_formatted = !empty($data['tahun_perolehan']) ? date('d-m-Y', strtotime($data['tahun_perolehan'])) : '-';
      ?>
      <tr>
        <td><?= $no++ ?></td>
        <td><?= htmlspecialchars($data['nama_sarana']) ?></td>
        <td><?= htmlspecialchars($data['kode_sarana']) ?></td>
        <td><?= htmlspecialchars($data['lokasi']) ?></td>
        <td><?= htmlspecialchars($data['jumlah']) ?></td>
        <td><?= htmlspecialchars($data['kondisi']) ?></td>
        <td><?= htmlspecialchars($tanggal_formatted) ?></td>
        <td><?= htmlspecialchars($data['keterangan']) ?></td>
        <td>
          <button type="button" class="btn btn-warning btn-sm edit-btn"
                  data-bs-toggle="modal"
                  data-bs-target="#editSaranaModal"
                  data-id="<?= htmlspecialchars($data['id']) ?>"
                  data-nama_sarana="<?= htmlspecialchars($data['nama_sarana']) ?>"
                  data-kode_sarana="<?= htmlspecialchars($data['kode_sarana']) ?>"
                  data-lokasi="<?= htmlspecialchars($data['lokasi']) ?>"
                  data-jumlah="<?= htmlspecialchars($data['jumlah']) ?>"
                  data-kondisi="<?= htmlspecialchars($data['kondisi']) ?>"
                  data-tahun_perolehan="<?= htmlspecialchars($data['tahun_perolehan']) ?>"
                  data-keterangan="<?= htmlspecialchars($data['keterangan']) ?>">
            Edit
          </button>
          <a href="proses/hapus_barang.php?jenis=sarana&id=<?= htmlspecialchars($data['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data ini?')">Hapus</a>
        </td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>

<div class="modal fade" id="tambahSaranaModal" tabindex="-1" aria-labelledby="tambahSaranaModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tambahSaranaModalLabel">Tambah Data Sarana</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="proses/tambah_barang.php?jenis=sarana" method="post" id="formTambahSarana">
        <div class="modal-body">
            <div class="mb-3">
                <label for="tambah_nama_sarana" class="form-label">Nama Sarana</label>
                <input type="text" name="nama_sarana" id="tambah_nama_sarana" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="tambah_kode_sarana" class="form-label">Kode Sarana</label>
                <input type="text" name="kode_sarana" id="tambah_kode_sarana" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="tambah_lokasi" class="form-label">Lokasi</label>
                <input type="text" name="lokasi" id="tambah_lokasi" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="tambah_jumlah" class="form-label">Jumlah</label>
                <input type="number" name="jumlah" id="tambah_jumlah" class="form-control" required min="1">
            </div>
            <div class="mb-3">
                <label for="tambah_kondisi" class="form-label">Kondisi</label>
                <input type="text" name="kondisi" id="tambah_kondisi" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="tambah_tahun_perolehan" class="form-label">Tahun Perolehan (Opsional)</label>
                <input type="date" name="tahun_perolehan" id="tambah_tahun_perolehan" class="form-control">
            </div>
            <div class="mb-3">
                <label for="tambah_keterangan" class="form-label">Keterangan</label>
                <textarea name="keterangan" id="tambah_keterangan" class="form-control" rows="3"></textarea>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Tambah Data</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="editSaranaModal" tabindex="-1" aria-labelledby="editSaranaModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editSaranaModalLabel">Edit Data Sarana</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editSaranaForm" method="POST" action="proses/edit_barang.php">
        <div class="modal-body">
            <input type="hidden" name="table" value="sarana">
            <input type="hidden" name="id" id="edit_id">

            <div class="mb-3">
                <label for="edit_nama_sarana" class="form-label">Nama Sarana</label>
                <input type="text" name="nama_sarana" id="edit_nama_sarana" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="edit_kode_sarana" class="form-label">Kode Sarana</label>
                <input type="text" name="kode_sarana" id="edit_kode_sarana" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="edit_lokasi" class="form-label">Lokasi</label>
                <input type="text" name="lokasi" id="edit_lokasi" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="edit_jumlah" class="form-label">Jumlah</label>
                <input type="number" name="jumlah" id="edit_jumlah" class="form-control" required min="1">
            </div>
            <div class="mb-3">
                <label for="edit_kondisi" class="form-label">Kondisi</label>
                <input type="text" name="kondisi" id="edit_kondisi" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="edit_tahun_perolehan" class="form-label">Tahun Perolehan (Opsional)</label>
                <input type="date" name="tahun_perolehan" id="edit_tahun_perolehan" class="form-control">
            </div>
            <div class="mb-3">
                <label for="edit_keterangan" class="form-label">Keterangan</label>
                <textarea name="keterangan" id="edit_keterangan" class="form-control" rows="3"></textarea>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
$(document).ready(function() {
    $('#formTambahSarana').on('submit', function() {
        return true;
    });

    $('#editSaranaModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var modal = $(this);

        modal.find('#edit_id').val(button.data('id'));
        modal.find('#edit_nama_sarana').val(button.data('nama_sarana'));
        modal.find('#edit_kode_sarana').val(button.data('kode_sarana'));
        modal.find('#edit_lokasi').val(button.data('lokasi'));
        modal.find('#edit_jumlah').val(button.data('jumlah'));
        modal.find('#edit_kondisi').val(button.data('kondisi'));
        modal.find('#edit_tahun_perolehan').val(button.data('tahun_perolehan'));
        modal.find('#edit_keterangan').val(button.data('keterangan'));
    });

    $('#editSaranaForm').on('submit', function() {
        return true;
    });

    $('#tambahSaranaModal').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset();
    });
});
</script>

</body>
</html>