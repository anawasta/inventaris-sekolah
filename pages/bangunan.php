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
  <h3 class="mb-4">Data Bangunan</h3>

  <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#tambahBangunanModal">
    Tambah Data Bangunan
  </button>

  <table class="table table-bordered table-hover">
    <thead class="table-dark">
      <tr>
        <th>No</th>
        <th>Kode Barang</th>
        <th>Nama Barang</th>
        <th>Alamat</th>
        <th>Status Kepemilikan</th>
        <th>Tahun Perolehan</th>
        <th>Luas Bangunan (m²)</th>
        <th>Nilai</th>
        <th>Nama OPD</th>
        <th>Sub OPD</th>
        <th>Keterangan</th>
        <th>Dokumen</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $no = 1;
      $query = mysqli_query($koneksi, "SELECT * FROM bangunan");
      while ($data = mysqli_fetch_assoc($query)) {
          $tanggal_formatted = date('d-m-Y', strtotime($data['tahun_perolehan']));
      ?>
      <tr>
        <td><?= $no++ ?></td>
        <td><?= htmlspecialchars($data['kode_barang']) ?></td>
        <td><?= htmlspecialchars($data['nama_barang']) ?></td>
        <td><?= htmlspecialchars($data['alamat']) ?></td>
        <td><?= htmlspecialchars($data['status_kepemilikan']) ?></td>
        <td><?= htmlspecialchars($tanggal_formatted) ?></td>
        <td><?= number_format($data['luas_bangunan'], 0, ',', '.') ?> m²</td>
        <td>Rp. <?= number_format($data['nilai'], 0, ',', '.') ?></td>
        <td><?= htmlspecialchars($data['nama_opd']) ?></td>
        <td><?= htmlspecialchars($data['sub_opd']) ?></td>
        <td><?= htmlspecialchars($data['keterangan']) ?></td>
        <td>
            <?php if (!empty($data['file_dokumen'])) { ?>
                <a href="<?= BASE_URL . htmlspecialchars($data['file_dokumen']) ?>" target="_blank" class="btn btn-info btn-sm">Lihat Dokumen <i class="fas fa-file-alt"></i></a>
            <?php } else { ?>
                Tidak ada dokumen
            <?php } ?>
        </td>
        <td>
          <button type="button" class="btn btn-warning btn-sm edit-btn"
                  data-bs-toggle="modal"
                  data-bs-target="#editBangunanModal"
                  data-id="<?= htmlspecialchars($data['id']) ?>"
                  data-kode_barang="<?= htmlspecialchars($data['kode_barang']) ?>"
                  data-nama_barang="<?= htmlspecialchars($data['nama_barang']) ?>"
                  data-alamat="<?= htmlspecialchars($data['alamat']) ?>"
                  data-status_kepemilikan="<?= htmlspecialchars($data['status_kepemilikan']) ?>"
                  data-tahun_perolehan="<?= htmlspecialchars($data['tahun_perolehan']) ?>"
                  data-luas_bangunan="<?= htmlspecialchars($data['luas_bangunan']) ?>"
                  data-nilai="<?= htmlspecialchars($data['nilai']) ?>"
                  data-nama_opd="<?= htmlspecialchars($data['nama_opd']) ?>"
                  data-sub_opd="<?= htmlspecialchars($data['sub_opd']) ?>"
                  data-keterangan="<?= htmlspecialchars($data['keterangan']) ?>"
                  data-file_dokumen="<?= htmlspecialchars($data['file_dokumen']) ?>">
            Edit
          </button>
          <a href="proses/hapus_barang.php?jenis=bangunan&id=<?= htmlspecialchars($data['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data ini?')">Hapus</a>
        </td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>

<div class="modal fade" id="tambahBangunanModal" tabindex="-1" aria-labelledby="tambahBangunanModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tambahBangunanModalLabel">Tambah Data Bangunan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="proses/tambah_barang.php?jenis=bangunan" method="post" id="formTambahBangunan" enctype="multipart/form-data">
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
                <label for="tambah_alamat" class="form-label">Alamat</label>
                <input type="text" name="alamat" id="tambah_alamat" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="tambah_status_kepemilikan" class="form-label">Status Kepemilikan</label>
                <input type="text" name="status_kepemilikan" id="tambah_status_kepemilikan" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="tambah_tahun_perolehan" class="form-label">Tahun Perolehan</label>
                <input type="date" name="tahun_perolehan" id="tambah_tahun_perolehan" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="tambah_luas_bangunan" class="form-label">Luas Bangunan (m²)</label>
                <input type="text" name="luas_bangunan" id="tambah_luas_bangunan" class="form-control text-end" required>
            </div>
            <div class="mb-3">
                <label for="tambah_nilai" class="form-label">Nilai</label>
                <input type="text" name="nilai" id="tambah_nilai" class="form-control text-end" required>
            </div>
            <div class="mb-3">
                <label for="tambah_nama_opd" class="form-label">Nama OPD</label>
                <input type="text" name="nama_opd" id="tambah_nama_opd" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="tambah_sub_opd" class="form-label">Sub OPD</label>
                <input type="text" name="sub_opd" id="tambah_sub_opd" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="tambah_keterangan" class="form-label">Keterangan</label>
                <textarea name="keterangan" id="tambah_keterangan" class="form-control" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label for="tambah_file_dokumen" class="form-label">Upload Dokumen (PDF/Gambar)</label>
                <input type="file" name="file_dokumen" id="tambah_file_dokumen" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                <small class="form-text text-muted">Upload file dokumen terkait (maks. 5MB, format .pdf/.jpg/.jpeg/.png).</small>
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

<div class="modal fade" id="editBangunanModal" tabindex="-1" aria-labelledby="editBangunanModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editBangunanModalLabel">Edit Data Bangunan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editBangunanForm" method="POST" action="proses/edit_barang.php" enctype="multipart/form-data">
        <div class="modal-body">
            <input type="hidden" name="table" value="bangunan">
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
                <label for="edit_alamat" class="form-label">Alamat</label>
                <input type="text" name="alamat" id="edit_alamat" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="edit_status_kepemilikan" class="form-label">Status Kepemilikan</label>
                <input type="text" name="status_kepemilikan" id="edit_status_kepemilikan" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="edit_tahun_perolehan" class="form-label">Tahun Perolehan</label>
                <input type="date" name="tahun_perolehan" id="edit_tahun_perolehan" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="edit_luas_bangunan" class="form-label">Luas Bangunan (m²)</label>
                <input type="text" name="luas_bangunan" id="edit_luas_bangunan" class="form-control text-end" required>
            </div>
            <div class="mb-3">
                <label for="edit_nilai" class="form-label">Nilai</label>
                <input type="text" name="nilai" id="edit_nilai" class="form-control text-end" required>
            </div>
            <div class="mb-3">
                <label for="edit_nama_opd" class="form-label">Nama OPD</label>
                <input type="text" name="nama_opd" id="edit_nama_opd" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="edit_sub_opd" class="form-label">Sub OPD</label>
                <input type="text" name="sub_opd" id="edit_sub_opd" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="edit_keterangan" class="form-label">Keterangan</label>
                <textarea name="keterangan" id="edit_keterangan" class="form-control" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Dokumen Saat Ini:</label>
                <div id="current_file_dokumen_display"></div>
                <label for="edit_file_dokumen" class="form-label mt-2">Upload Dokumen Baru (Opsional)</label>
                <input type="file" name="file_dokumen" id="edit_file_dokumen" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                <small class="form-text text-muted">Upload file dokumen terkait (maks. 5MB, format .pdf/.jpg/.jpeg/.png). Jika diunggah, file lama akan diganti.</small>
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

    $('#tambah_luas_bangunan, #tambah_nilai, #edit_luas_bangunan, #edit_nilai').on('keyup blur', function() {
        let value = $(this).val();
        let unformattedValue = unformatNumber(value);
        if (!isNaN(parseFloat(unformattedValue)) && isFinite(unformattedValue)) {
            $(this).val(formatNumber(unformattedValue, 0));
        } else {
            $(this).val('');
        }
    });

    $('#formTambahBangunan').on('submit', function() {
        $('#tambah_luas_bangunan').val(unformatNumber($('#tambah_luas_bangunan').val()));
        $('#tambah_nilai').val(unformatNumber($('#tambah_nilai').val()));
        return true;
    });

    $('#editBangunanModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var modal = $(this);

        modal.find('#edit_id').val(button.data('id'));
        modal.find('#edit_kode_barang').val(button.data('kode_barang'));
        modal.find('#edit_nama_barang').val(button.data('nama_barang'));
        modal.find('#edit_alamat').val(button.data('alamat'));
        modal.find('#edit_status_kepemilikan').val(button.data('status_kepemilikan'));
        modal.find('#edit_tahun_perolehan').val(button.data('tahun_perolehan'));
        modal.find('#edit_luas_bangunan').val(formatNumber(button.data('luas_bangunan'), 0));
        modal.find('#edit_nilai').val(formatNumber(button.data('nilai'), 0));
        modal.find('#edit_nama_opd').val(button.data('nama_opd'));
        modal.find('#edit_sub_opd').val(button.data('sub_opd'));
        modal.find('#edit_keterangan').val(button.data('keterangan'));

        var currentDokumenPath = button.data('file_dokumen');
        var dokumenDisplayDiv = modal.find('#current_file_dokumen_display');
        if (currentDokumenPath) {
            // Check file extension to display appropriate icon/preview
            var fileExt = currentDokumenPath.split('.').pop().toLowerCase();
            if (fileExt === 'pdf') {
                dokumenDisplayDiv.html('<a href="<?= BASE_URL ?>' + currentDokumenPath + '" target="_blank" class="btn btn-outline-primary btn-sm"><i class="fas fa-file-pdf"></i> Lihat PDF</a>');
            } else if (['jpg', 'jpeg', 'png'].includes(fileExt)) {
                dokumenDisplayDiv.html('<a href="<?= BASE_URL ?>' + currentDokumenPath + '" target="_blank" class="btn btn-outline-primary btn-sm"><i class="fas fa-image"></i> Lihat Gambar</a>');
            } else {
                dokumenDisplayDiv.html('<a href="<?= BASE_URL ?>' + currentDokumenPath + '" target="_blank" class="btn btn-outline-primary btn-sm"><i class="fas fa-file"></i> Lihat Dokumen</a>');
            }
        } else {
            dokumenDisplayDiv.html('Tidak ada dokumen terunggah.');
        }
        modal.find('#edit_file_dokumen').val('');
    });

    $('#editBangunanForm').on('submit', function() {
        $('#edit_luas_bangunan').val(unformatNumber($('#edit_luas_bangunan').val()));
        $('#edit_nilai').val(unformatNumber($('#edit_nilai').val()));
        return true;
    });

    $('#tambahBangunanModal').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset();
        $(this).find('#tambah_file_dokumen').val('');
    });
});
</script>

</body>
</html>