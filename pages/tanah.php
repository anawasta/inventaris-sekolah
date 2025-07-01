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
    <title>Data Tanah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<div class="container-fluid">
  <h3 class="mb-4">Data Tanah (KIB A)</h3>

  <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#tambahTanahModal">
    Tambah Data Tanah
  </button>

  <table class="table table-bordered table-hover">
    <thead class="table-dark">
      <tr>
        <th>No</th>
        <th>Kode Barang</th>
        <th>Nama Barang</th>
        <th>Alamat</th>
        <th>Status Tanah</th>
        <th>Tahun Perolehan</th>        
        <th>Luas (m²)</th>
        <th>Nilai</th>
        <th>Nama OPD</th>        
        <th>Sub OPD</th>
        <th>Keterangan</th>
        <th>File PDF</th> <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $no = 1;
      // Perhatikan nama kolom di query SELECT sesuai dengan skema baru
      $query = mysqli_query($koneksi, "SELECT id, kode_barang, nama_barang, alamat, status_tanah, tahun_perolehan, luas, nilai, nama_opd, sub_opd, keterangan, file_pdf FROM tanah");
      while ($data = mysqli_fetch_assoc($query)) {
          // Format tanggal perolehan ke format DD-MM-YYYY
          $tanggal_formatted = date('d-m-Y', strtotime($data['tahun_perolehan']));
      ?>
      <tr>
        <td><?= $no++ ?></td>
        <td><?= htmlspecialchars($data['kode_barang']) ?></td>
        <td><?= htmlspecialchars($data['nama_barang']) ?></td>
        <td><?= htmlspecialchars($data['alamat']) ?></td>
        <td><?= htmlspecialchars($data['status_tanah']) ?></td>
        <td><?= htmlspecialchars($tanggal_formatted) ?></td> 
        <td><?= number_format($data['luas'], 0, ',', '.') ?> m²</td>
        <td>Rp. <?= number_format($data['nilai'], 0, ',', '.') ?></td>
        <td><?= htmlspecialchars($data['nama_opd']) ?></td>
        <td><?= htmlspecialchars($data['sub_opd']) ?></td>
        <td><?= htmlspecialchars($data['keterangan']) ?></td>
       <td>
            <?php if (!empty($data['file_pdf'])) { ?>
                <a href="<?= BASE_URL . htmlspecialchars($data['file_pdf']) ?>" target="_blank" class="btn btn-info btn-sm">Lihat PDF <i class="fas fa-file-pdf"></i></a>
            <?php } else { ?>
                Tidak ada PDF
            <?php } ?>
        </td>
        <td>
          <button type="button" class="btn btn-warning btn-sm edit-btn" 
                  data-bs-toggle="modal" 
                  data-bs-target="#editTanahModal"
                  data-id="<?= htmlspecialchars($data['id']) ?>"
                  data-kode_barang="<?= htmlspecialchars($data['kode_barang']) ?>"
                  data-nama_barang="<?= htmlspecialchars($data['nama_barang']) ?>"
                  data-alamat="<?= htmlspecialchars($data['alamat']) ?>"
                  data-status_tanah="<?= htmlspecialchars($data['status_tanah']) ?>"
                  data-tahun_perolehan="<?= htmlspecialchars($data['tahun_perolehan']) ?>"
                  data-luas="<?= htmlspecialchars($data['luas']) ?>"
                  data-nilai="<?= htmlspecialchars($data['nilai']) ?>"
                  data-nama_opd="<?= htmlspecialchars($data['nama_opd']) ?>"
                  data-sub_opd="<?= htmlspecialchars($data['sub_opd']) ?>"
                  data-keterangan="<?= htmlspecialchars($data['keterangan']) ?>"
                  data-file_pdf="<?= htmlspecialchars($data['file_pdf']) ?>"> 
            Edit
          </button>
          <a href="proses/hapus_barang.php?jenis=tanah&id=<?= htmlspecialchars($data['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data ini?')">Hapus</a>
        </td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>

<div class="modal fade" id="tambahTanahModal" tabindex="-1" aria-labelledby="tambahTanahModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tambahTanahModalLabel">Tambah Data Tanah</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="proses/tambah_barang.php?jenis=tanah" method="post" id="formTambahTanah" enctype="multipart/form-data">
        <div class="modal-body">
            <div class="mb-3">
                <label for="tambah_kode_barang" class="form-label">Kode Barang</label>
                <input type="text" name="kode_barang" id="tambah_kode_barang" class="form-control" placeholder="Kode Barang/ID Barang" required>
            </div>  
            <div class="mb-3">
                <label for="tambah_nama_barang" class="form-label">Nama Barang</label>
                <input type="text" name="nama_barang" id="tambah_nama_barang" class="form-control" placeholder="Nama Barang" required>
            </div>
            <div class="mb-3">
                <label for="tambah_alamat" class="form-label">Alamat</label>
                <input type="text" name="alamat" id="tambah_alamat" class="form-control" placeholder="Alamat" required>
            </div>
            <div class="mb-3">
                <label for="tambah_status_tanah" class="form-label">Status Tanah</label>
                <input type="text" name="status_tanah" id="tambah_status_tanah" class="form-control" placeholder="Status Perolehan Tanah" required>
            </div>
            <div class="mb-3">
                <label for="tambah_tahun_perolehan" class="form-label">Tahun Perolehan</label>
                <input type="date" name="tahun_perolehan" id="tambah_tahun_perolehan" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="tambah_luas" class="form-label">Luas (m²)</label>
                <input type="text" name="luas" id="tambah_luas" class="form-control text-end" placeholder="Luas (m²)" required>
            </div>
            <div class="mb-3">
                <label for="tambah_nilai" class="form-label">Nilai</label>
                <input type="text" name="nilai" id="tambah_nilai" class="form-control text-end" placeholder="Nilai Perolehan" required>
            </div>
            <div class="mb-3">
                <label for="tambah_nama_opd" class="form-label">Nama OPD</label>
                <input type="text" name="nama_opd" id="tambah_nama_opd" class="form-control" placeholder="Nama OPD" required>
            </div>
            <div class="mb-3">
                <label for="tambah_sub_opd" class="form-label">Sub OPD</label>
                <input type="text" name="sub_opd" id="tambah_sub_opd" class="form-control" placeholder="Sub OPD" required>
            </div>
            <div class="mb-3">
                <label for="tambah_keterangan" class="form-label">Keterangan</label>
                <textarea name="keterangan" id="tambah_keterangan" class="form-control" rows="3" placeholder="Keterangan tambahan"></textarea>
            </div>
            <div class="mb-3">
                <label for="tambah_file_pdf" class="form-label">Upload File PDF</label>
                <input type="file" name="file_pdf" id="tambah_file_pdf" class="form-control" accept=".pdf">
                <small class="form-text text-muted">Upload file dokumen terkait (maks. 5MB, format .pdf).</small>
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

<div class="modal fade" id="editTanahModal" tabindex="-1" aria-labelledby="editTanahModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editTanahModalLabel">Edit Data Tanah</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editTanahForm" method="POST" action="proses/edit_barang.php" enctype="multipart/form-data"> <div class="modal-body">
            <input type="hidden" name="table" value="tanah">
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
                <label for="edit_status_tanah" class="form-label">Status Tanah</label>
                <input type="text" name="status_tanah" id="edit_status_tanah" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="edit_tahun_perolehan" class="form-label">Tahun Perolehan</label>
                <input type="date" name="tahun_perolehan" id="edit_tahun_perolehan" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="edit_luas" class="form-label">Luas (m²)</label>
                <input type="text" name="luas" id="edit_luas" class="form-control text-end" required>
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
                <label class="form-label">Dokumen PDF Saat Ini:</label>
                <div id="current_file_pdf_display">
                    </div>
                <label for="edit_file_pdf" class="form-label mt-2">Upload PDF Baru (Opsional)</label>
                <input type="file" name="file_pdf" id="edit_file_pdf" class="form-control" accept=".pdf">
                <small class="form-text text-muted">Upload file dokumen terkait (maks. 5MB, format .pdf). Jika diunggah, file lama akan diganti.</small>
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
    // Function to format number for display (e.g., 1000000 -> 1.000.000 or 1234.56 -> 1.234,56)
    function formatNumber(num, decimalPlaces = 0) {
        if (num === null || num === undefined || num === '') {
            return '';
        }
        // Ensure num is a number before formatting
        const numberValue = parseFloat(num);
        if (isNaN(numberValue)) {
            return '';
        }
        return new Intl.NumberFormat('id-ID', {
            minimumFractionDigits: decimalPlaces,
            maximumFractionDigits: decimalPlaces
        }).format(numberValue);
    }

    // Function to unformat number for submission (e.g., 1.000.000,00 -> 1000000.00)
    function unformatNumber(str) {
        if (str === null || str === undefined || str === '') {
            return '';
        }
        // Remove thousands separators (dots) and replace decimal comma with a dot
        return str.replace(/\./g, '').replace(/,/g, '.');
    }

    // Apply formatting to input fields on keyup and blur for "Tambah Data" modal
    $('#tambah_luas').on('keyup blur', function() {
        let value = $(this).val();
        let unformattedValue = unformatNumber(value);
        if (!isNaN(parseFloat(unformattedValue)) && isFinite(unformattedValue)) {
            $(this).val(formatNumber(unformattedValue, 0)); // 0 decimal places for luas
        } else {
            $(this).val(''); // Clear if not a valid number
        }
    });

    $('#tambah_nilai').on('keyup blur', function() {
        let value = $(this).val();
        let unformattedValue = unformatNumber(value);
        if (!isNaN(parseFloat(unformattedValue)) && isFinite(unformattedValue)) {
            $(this).val(formatNumber(unformattedValue, 0)); // 0 decimal places for nilai
        } else {
            $(this).val(''); // Clear if not a valid number
        }
    });

    // Apply formatting to input fields on keyup and blur for "Edit Data" modal
    $('#edit_luas').on('keyup blur', function() {
        let value = $(this).val();
        let unformattedValue = unformatNumber(value);
        if (!isNaN(parseFloat(unformattedValue)) && isFinite(unformattedValue)) {
            $(this).val(formatNumber(unformattedValue, 0)); // 0 decimal places for luas
        } else {
            $(this).val(''); // Clear if not a valid number
        }
    });

    $('#edit_nilai').on('keyup blur', function() {
        let value = $(this).val();
        let unformattedValue = unformatNumber(value);
        if (!isNaN(parseFloat(unformattedValue)) && isFinite(unformattedValue)) {
            $(this).val(formatNumber(unformattedValue, 0)); // 0 decimal places for nilai
        } else {
            $(this).val(''); // Clear if not a valid number
        }
    });

    // Handle form submission for "Tambah Data Tanah"
    $('#formTambahTanah').on('submit', function() {
        // Unformat 'luas' and 'nilai' before submission
        $('#tambah_luas').val(unformatNumber($('#tambah_luas').val()));
        $('#tambah_nilai').val(unformatNumber($('#tambah_nilai').val()));
        return true; // Allow form submission
    });

    // Script untuk Modal Edit
    $('#editTanahModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Tombol yang memicu modal
        var modal = $(this);

        // Isi data ke dalam form modal
        modal.find('#edit_id').val(button.data('id'));
        modal.find('#edit_kode_barang').val(button.data('kode_barang'));
        modal.find('#edit_nama_barang').val(button.data('nama_barang'));
        modal.find('#edit_alamat').val(button.data('alamat'));
        modal.find('#edit_status_tanah').val(button.data('status_tanah'));
        modal.find('#edit_tahun_perolehan').val(button.data('tahun_perolehan'));
        // Format luas and nilai for display when editing
        modal.find('#edit_luas').val(formatNumber(button.data('luas'), 0)); // 0 decimal places for luas
        modal.find('#edit_nilai').val(formatNumber(button.data('nilai'), 0)); // 0 decimal places for nilai
        modal.find('#edit_nama_opd').val(button.data('nama_opd'));
        modal.find('#edit_sub_opd').val(button.data('sub_opd'));
        modal.find('#edit_keterangan').val(button.data('keterangan'));
        // Handle PDF display in edit modal
        var currentPdfPath = button.data('file_pdf');
        var pdfDisplayDiv = modal.find('#current_file_pdf_display');
        if (currentPdfPath) {
            // Gunakan BASE_URL untuk menampilkan link PDF di modal edit
            pdfDisplayDiv.html('<a href="<?= BASE_URL ?>' + currentPdfPath + '" target="_blank" class="btn btn-outline-primary btn-sm"><i class="fas fa-file-pdf"></i> Lihat PDF</a>');
        } else {
            pdfDisplayDiv.html('Tidak ada dokumen PDF terunggah.');
        }
        modal.find('#edit_file_pdf').val('');
    });

    // Handle form submission for "Edit Data Tanah"
    $('#editTanahForm').on('submit', function() {
        // Unformat 'luas' and 'nilai' before submission
        $('#edit_luas').val(unformatNumber($('#edit_luas').val()));
        $('#edit_nilai').val(unformatNumber($('#edit_nilai').val()));
        return true; // Allow form submission
    });

    // Opsional: Reset form Tambah saat modal ditutup (agar data sebelumnya bersih)
    $('#tambahTanahModal').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset();
        // Also clear the file input
        $(this).find('#tambah_file_pdf').val('');
    });
});
</script>

</body>
</html>