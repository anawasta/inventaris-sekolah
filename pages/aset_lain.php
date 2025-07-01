<?php include 'config/koneksi.php'; ?>
<?php
define('BASE_URL', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/sarpras-smkn1ptb/");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Aset Lain</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<div class="container-fluid">
  <h3 class="mb-4">Data Aset Lain</h3>

  <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#tambahAsetLainModal">
    Tambah Data Aset Lain
  </button>

  <table class="table table-bordered table-hover">
    <thead class="table-dark">
      <tr>
        <th>No</th>
        <th>Kode Barang</th>
        <th>Nama Barang</th>
        <th>Jumlah</th>
        <th>Satuan</th>
        <th>Tahun Perolehan</th>
        <th>Nilai</th>
        <th>Kondisi</th>
        <th>Keterangan</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $no = 1;
      $query = mysqli_query($koneksi, "SELECT * FROM aset_lain");
      while ($data = mysqli_fetch_assoc($query)) {
          $tanggal_formatted = date('d-m-Y', strtotime($data['tahun_perolehan']));
      ?>
      <tr>
        <td><?= $no++ ?></td>
        <td><?= htmlspecialchars($data['kode_barang']) ?></td>
        <td><?= htmlspecialchars($data['nama_barang']) ?></td>
        <td><?= htmlspecialchars($data['jumlah']) ?></td>
        <td><?= htmlspecialchars($data['satuan']) ?></td>
        <td><?= htmlspecialchars($tanggal_formatted) ?></td>
        <td>Rp. <?= number_format($data['nilai'], 0, ',', '.') ?></td>
        <td><?= htmlspecialchars($data['kondisi']) ?></td>
        <td><?= htmlspecialchars($data['keterangan']) ?></td>
        <td>
          <button type="button" class="btn btn-warning btn-sm edit-btn"
                  data-bs-toggle="modal"
                  data-bs-target="#editAsetLainModal"
                  data-id="<?= htmlspecialchars($data['id']) ?>"
                  data-kode_barang="<?= htmlspecialchars($data['kode_barang']) ?>"
                  data-nama_barang="<?= htmlspecialchars($data['nama_barang']) ?>"
                  data-jumlah="<?= htmlspecialchars($data['jumlah']) ?>"
                  data-satuan="<?= htmlspecialchars($data['satuan']) ?>"
                  data-tahun_perolehan="<?= htmlspecialchars($data['tahun_perolehan']) ?>"
                  data-nilai="<?= htmlspecialchars($data['nilai']) ?>"
                  data-kondisi="<?= htmlspecialchars($data['kondisi']) ?>"
                  data-keterangan="<?= htmlspecialchars($data['keterangan']) ?>">
            Edit
          </button>
          <a href="proses/hapus_barang.php?jenis=aset_lain&id=<?= htmlspecialchars($data['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data ini?')">Hapus</a>
        </td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>

<div class="modal fade" id="tambahAsetLainModal" tabindex="-1" aria-labelledby="tambahAsetLainModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tambahAsetLainModalLabel">Tambah Data Aset Lain</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="proses/tambah_barang.php?jenis=aset_lain" method="post" id="formTambahAsetLain">
        <div class="modal-body">
            <div class="mb-3">
                <label for="tambah_kode_barang" class="form-label">Kode Barang</label>
                <input type="text" name="kode_barang" id="tambah_kode_barang" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="tambah_nama_barang" class="form-label">Nama Barang</label>
                <input type="text" name="nama_barang" id="tambah_nama_barang" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="tambah_jumlah" class="form-label">Jumlah</label>
                <input type="number" name="jumlah" id="tambah_jumlah" class="form-control" required min="1">
            </div>
            <div class="mb-3">
                <label for="tambah_satuan" class="form-label">Satuan</label>
                <input type="text" name="satuan" id="tambah_satuan" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="tambah_tahun_perolehan" class="form-label">Tahun Perolehan</label>
                <input type="date" name="tahun_perolehan" id="tambah_tahun_perolehan" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="tambah_nilai" class="form-label">Nilai</label>
                <input type="text" name="nilai" id="tambah_nilai" class="form-control text-end" required>
            </div>
            <div class="mb-3">
                <label for="tambah_kondisi" class="form-label">Kondisi</label>
                <input type="text" name="kondisi" id="tambah_kondisi" class="form-control">
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

<div class="modal fade" id="editAsetLainModal" tabindex="-1" aria-labelledby="editAsetLainModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editAsetLainModalLabel">Edit Data Aset Lain</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editAsetLainForm" method="POST" action="proses/edit_barang.php">
        <div class="modal-body">
            <input type="hidden" name="table" value="aset_lain">
            <input type="hidden" name="id" id="edit_id">

            <div class="mb-3">
                <label for="edit_kode_barang" class="form-label">Kode Barang</label>
                <input type="text" name="kode_barang" id="edit_kode_barang" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="edit_nama_barang" class="form-label">Nama Barang</label>
                <input type="text" name="nama_barang" id="edit_nama_barang" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="edit_jumlah" class="form-label">Jumlah</label>
                <input type="number" name="jumlah" id="edit_jumlah" class="form-control" required min="1">
            </div>
            <div class="mb-3">
                <label for="edit_satuan" class="form-label">Satuan</label>
                <input type="text" name="satuan" id="edit_satuan" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="edit_tahun_perolehan" class="form-label">Tahun Perolehan</label>
                <input type="date" name="tahun_perolehan" id="edit_tahun_perolehan" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="edit_nilai" class="form-label">Nilai</label>
                <input type="text" name="nilai" id="edit_nilai" class="form-control text-end" required>
            </div>
            <div class="mb-3">
                <label for="edit_kondisi" class="form-label">Kondisi</label>
                <input type="text" name="kondisi" id="edit_kondisi" class="form-control">
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
    function formatNumber(num, decimalPlaces = 0) {
        if (num === null || num === undefined || num === '') {
            return '';
        }
        const numberValue = parseFloat(num);
        if (isNaN(numberValue)) {
            return '';
        }
        return new Intl.NumberFormat('id-ID', {
            minimumFractionDigits: decimalPlaces,
            maximumFractionDigits: decimalPlaces
        }).format(numberValue);
    }

    function unformatNumber(str) {
        if (str === null || str === undefined || str === '') {
            return '';
        }
        return str.replace(/\./g, '').replace(/,/g, '.');
    }

    $('#tambah_nilai, #edit_nilai').on('keyup blur', function() {
        let value = $(this).val();
        let unformattedValue = unformatNumber(value);
        if (!isNaN(parseFloat(unformattedValue)) && isFinite(unformattedValue)) {
            $(this).val(formatNumber(unformattedValue, 0));
        } else {
            $(this).val('');
        }
    });

    $('#formTambahAsetLain').on('submit', function() {
        $('#tambah_nilai').val(unformatNumber($('#tambah_nilai').val()));
        return true;
    });

    $('#editAsetLainModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var modal = $(this);

        modal.find('#edit_id').val(button.data('id'));
        modal.find('#edit_kode_barang').val(button.data('kode_barang'));
        modal.find('#edit_nama_barang').val(button.data('nama_barang'));
        modal.find('#edit_jumlah').val(button.data('jumlah'));
        modal.find('#edit_satuan').val(button.data('satuan'));
        modal.find('#edit_tahun_perolehan').val(button.data('tahun_perolehan'));
        modal.find('#edit_nilai').val(formatNumber(button.data('nilai'), 0));
        modal.find('#edit_kondisi').val(button.data('kondisi'));
        modal.find('#edit_keterangan').val(button.data('keterangan'));
    });

    $('#editAsetLainForm').on('submit', function() {
        $('#edit_nilai').val(unformatNumber($('#edit_nilai').val()));
        return true;
    });

    $('#tambahAsetLainModal').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset();
    });
});
</script>

</body>
</html>