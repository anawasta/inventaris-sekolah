<?php include 'config/koneksi.php'; ?>
<?php
define('BASE_URL', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/sarpras-smkn1ptb/");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Jaringan, Irigasi, Jalan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<div class="container-fluid">
  <h3 class="mb-4">Data Jaringan, Irigasi, Jalan</h3>

  <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#tambahJIJModal">
    Tambah Data JIJ
  </button>

  <table class="table table-bordered table-hover">
    <thead class="table-dark">
      <tr>
        <th>No</th>
        <th>Kode Barang</th>
        <th>Nama Barang</th>
        <th>Lokasi</th>
        <th>Panjang</th>
        <th>Lebar</th>
        <th>Luas</th>
        <th>Tahun Pembangunan</th>
        <th>Nilai</th>
        <th>Keterangan</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $no = 1;
      $query = mysqli_query($koneksi, "SELECT * FROM jij");
      while ($data = mysqli_fetch_assoc($query)) {
          $tanggal_formatted = date('d-m-Y', strtotime($data['tahun_pembangunan']));
      ?>
      <tr>
        <td><?= $no++ ?></td>
        <td><?= htmlspecialchars($data['kode_barang']) ?></td>
        <td><?= htmlspecialchars($data['nama_barang']) ?></td>
        <td><?= htmlspecialchars($data['lokasi']) ?></td>
        <td><?= number_format($data['panjang'], 2, ',', '.') ?></td>
        <td><?= number_format($data['lebar'], 2, ',', '.') ?></td>
        <td><?= number_format($data['luas'], 2, ',', '.') ?></td>
        <td><?= htmlspecialchars($tanggal_formatted) ?></td>
        <td>Rp. <?= number_format($data['nilai'], 0, ',', '.') ?></td>
        <td><?= htmlspecialchars($data['keterangan']) ?></td>
        <td>
          <button type="button" class="btn btn-warning btn-sm edit-btn"
                  data-bs-toggle="modal"
                  data-bs-target="#editJIJModal"
                  data-id="<?= htmlspecialchars($data['id']) ?>"
                  data-kode_barang="<?= htmlspecialchars($data['kode_barang']) ?>"
                  data-nama_barang="<?= htmlspecialchars($data['nama_barang']) ?>"
                  data-lokasi="<?= htmlspecialchars($data['lokasi']) ?>"
                  data-panjang="<?= htmlspecialchars($data['panjang']) ?>"
                  data-lebar="<?= htmlspecialchars($data['lebar']) ?>"
                  data-luas="<?= htmlspecialchars($data['luas']) ?>"
                  data-tahun_pembangunan="<?= htmlspecialchars($data['tahun_pembangunan']) ?>"
                  data-nilai="<?= htmlspecialchars($data['nilai']) ?>"
                  data-keterangan="<?= htmlspecialchars($data['keterangan']) ?>">
            Edit
          </button>
          <a href="proses/hapus_barang.php?jenis=jij&id=<?= htmlspecialchars($data['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data ini?')">Hapus</a>
        </td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>

<div class="modal fade" id="tambahJIJModal" tabindex="-1" aria-labelledby="tambahJIJModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tambahJIJModalLabel">Tambah Data Jaringan, Irigasi, Jalan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="proses/tambah_barang.php?jenis=jij" method="post" id="formTambahJIJ">
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
                <label for="tambah_lokasi" class="form-label">Lokasi</label>
                <input type="text" name="lokasi" id="tambah_lokasi" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="tambah_panjang" class="form-label">Panjang</label>
                <input type="text" name="panjang" id="tambah_panjang" class="form-control text-end">
            </div>
            <div class="mb-3">
                <label for="tambah_lebar" class="form-label">Lebar</label>
                <input type="text" name="lebar" id="tambah_lebar" class="form-control text-end">
            </div>
            <div class="mb-3">
                <label for="tambah_luas" class="form-label">Luas</label>
                <input type="text" name="luas" id="tambah_luas" class="form-control text-end">
            </div>
            <div class="mb-3">
                <label for="tambah_tahun_pembangunan" class="form-label">Tahun Pembangunan</label>
                <input type="date" name="tahun_pembangunan" id="tambah_tahun_pembangunan" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="tambah_nilai" class="form-label">Nilai</label>
                <input type="text" name="nilai" id="tambah_nilai" class="form-control text-end" required>
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

<div class="modal fade" id="editJIJModal" tabindex="-1" aria-labelledby="editJIJModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editJIJModalLabel">Edit Data Jaringan, Irigasi, Jalan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editJIJForm" method="POST" action="proses/edit_barang.php">
        <div class="modal-body">
            <input type="hidden" name="table" value="jij">
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
                <label for="edit_lokasi" class="form-label">Lokasi</label>
                <input type="text" name="lokasi" id="edit_lokasi" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="edit_panjang" class="form-label">Panjang</label>
                <input type="text" name="panjang" id="edit_panjang" class="form-control text-end">
            </div>
            <div class="mb-3">
                <label for="edit_lebar" class="form-label">Lebar</label>
                <input type="text" name="lebar" id="edit_lebar" class="form-control text-end">
            </div>
            <div class="mb-3">
                <label for="edit_luas" class="form-label">Luas</label>
                <input type="text" name="luas" id="edit_luas" class="form-control text-end">
            </div>
            <div class="mb-3">
                <label for="edit_tahun_pembangunan" class="form-label">Tahun Pembangunan</label>
                <input type="date" name="tahun_pembangunan" id="edit_tahun_pembangunan" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="edit_nilai" class="form-label">Nilai</label>
                <input type="text" name="nilai" id="edit_nilai" class="form-control text-end" required>
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

    $('#tambah_panjang, #tambah_lebar, #tambah_luas, #tambah_nilai, #edit_panjang, #edit_lebar, #edit_luas, #edit_nilai').on('keyup blur', function() {
        let value = $(this).val();
        let unformattedValue = unformatNumber(value);
        if (!isNaN(parseFloat(unformattedValue)) && isFinite(unformattedValue)) {
            $(this).val(formatNumber(unformattedValue, ($(this).attr('id').includes('nilai') ? 0 : 2))); // 0 desimal untuk nilai, 2 untuk panjang/lebar/luas
        } else {
            $(this).val('');
        }
    });

    $('#formTambahJIJ').on('submit', function() {
        $('#tambah_panjang').val(unformatNumber($('#tambah_panjang').val()));
        $('#tambah_lebar').val(unformatNumber($('#tambah_lebar').val()));
        $('#tambah_luas').val(unformatNumber($('#tambah_luas').val()));
        $('#tambah_nilai').val(unformatNumber($('#tambah_nilai').val()));
        return true;
    });

    $('#editJIJModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var modal = $(this);

        modal.find('#edit_id').val(button.data('id'));
        modal.find('#edit_kode_barang').val(button.data('kode_barang'));
        modal.find('#edit_nama_barang').val(button.data('nama_barang'));
        modal.find('#edit_lokasi').val(button.data('lokasi'));
        modal.find('#edit_panjang').val(formatNumber(button.data('panjang'), 2));
        modal.find('#edit_lebar').val(formatNumber(button.data('lebar'), 2));
        modal.find('#edit_luas').val(formatNumber(button.data('luas'), 2));
        modal.find('#edit_tahun_pembangunan').val(button.data('tahun_pembangunan'));
        modal.find('#edit_nilai').val(formatNumber(button.data('nilai'), 0));
        modal.find('#edit_keterangan').val(button.data('keterangan'));
    });

    $('#editJIJForm').on('submit', function() {
        $('#edit_panjang').val(unformatNumber($('#edit_panjang').val()));
        $('#edit_lebar').val(unformatNumber($('#edit_lebar').val()));
        $('#edit_luas').val(unformatNumber($('#edit_luas').val()));
        $('#edit_nilai').val(unformatNumber($('#edit_nilai').val()));
        return true;
    });

    $('#tambahJIJModal').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset();
    });
});
</script>

</body>
</html>