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
    <title>Data Aset</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .table-responsive {
            overflow-x: auto;
        }
        .table th, .table td {
            white-space: nowrap; /* Prevent text wrapping in table cells */
            vertical-align: middle;
        }
        .action-buttons a {
            margin-right: 5px;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <h3 class="mb-4">Daftar Aset</h3> 
    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-primary me-2"><i class="fas fa-file-import me-2"></i>Import Excel</button>
        <button class="btn btn-success me-2"><i class="fas fa-file-export me-2"></i>Export Excel</button>
        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#tambahAssetModal">
            <i class="fas fa-plus me-2"></i>Tambah Aset
        </button>
    </div>

    <div class="modal fade" id="tambahAssetModal" tabindex="-1" aria-labelledby="tambahAssetModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahAssetModalLabel">Tambah Data Aset</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="proses/tambah_barang.php?jenis=asset" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="kode_barang" class="form-label">Kode Barang <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="kode_barang" name="kode_barang" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="nama_barang" class="form-label">Nama Barang <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama_barang" name="nama_barang" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="perolehan" class="form-label">Perolehan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="perolehan" name="perolehan" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="no_spk_faktur_kuitansi" class="form-label">No. SPK / Faktur / Kuitansi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="no_spk_faktur_kuitansi" name="no_spk_faktur_kuitansi" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="no_bast" class="form-label">No BAST <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="no_bast" name="no_bast" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_bast" class="form-label">Tanggal BAST <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="tanggal_bast" name="tanggal_bast" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="merk_tipe" class="form-label">Merk / Tipe <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="merk_tipe" name="merk_tipe" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="satuan" class="form-label">Satuan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="satuan" name="satuan" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="volume" class="form-label">Volume <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="volume" name="volume" required pattern="[0-9.,]+" title="Hanya angka, koma, dan titik yang diizinkan">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="harga_satuan" class="form-label">Harga Satuan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="harga_satuan" name="harga_satuan" required pattern="[0-9.,]+" title="Hanya angka, koma, dan titik yang diizinkan">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="nilai_perolehan" class="form-label">Nilai Perolehan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nilai_perolehan" name="nilai_perolehan" required pattern="[0-9.,]+" title="Hanya angka, koma, dan titik yang diizinkan">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="umur_ekonomis" class="form-label">Umur Ekonomis (Tahun)</label>
                                <input type="number" class="form-control" id="umur_ekonomis" name="umur_ekonomis" min="0">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="kondisi" class="form-label">Kondisi <span class="text-danger">*</span></label>
                                <select class="form-select" id="kondisi" name="kondisi" required>
                                    <option value="">Pilih Kondisi</option>
                                    <option value="Baik">Baik</option>
                                    <option value="Rusak Ringan">Rusak Ringan</option>
                                    <option value="Rusak Berat">Rusak Berat</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="upload_bast" class="form-label">Upload BAST (PDF)</label>
                                <input class="form-control" type="file" id="upload_bast" name="upload_bast" accept=".pdf">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="upload_foto" class="form-label">Upload Foto (JPG, JPEG, PNG)</label>
                                <input class="form-control" type="file" id="upload_foto" name="upload_foto" accept=".jpg, .jpeg, .png">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Perolehan</th>
                            <th>Merk/Tipe</th>
                            <th>Volume</th>
                            <th>Tahun</th>
                            <th>Kondisi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        // Mengambil hanya kolom yang dibutuhkan dan mengekstrak tahun dari tanggal_bast
                        $query = mysqli_query($koneksi, "SELECT id, kode_barang, nama_barang, perolehan, merk_tipe, volume, YEAR(tanggal_bast) AS tahun_perolehan, kondisi FROM asset ORDER BY id DESC");
                        while ($data = mysqli_fetch_array($query)) {
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($data['kode_barang']) ?></td>
                            <td><?= htmlspecialchars($data['nama_barang']) ?></td>
                            <td><?= htmlspecialchars($data['perolehan']) ?></td>
                            <td><?= htmlspecialchars($data['merk_tipe']) ?></td>
                            <td><?= number_format($data['volume'], 2, ',', '.') ?></td>
                            <td><?= htmlspecialchars($data['tahun_perolehan']) ?></td>
                            <td><?= htmlspecialchars($data['kondisi']) ?></td>
                            <td class="action-buttons">
                                <a href="edit_barang.php?jenis=asset&id=<?= $data['id'] ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                <a href="proses/hapus_barang.php?jenis=asset&id=<?= $data['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data ini?')"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="data-info">
                    Menampilkan 1 sampai <?= mysqli_num_rows($query) < 5 ? mysqli_num_rows($query) : 5 ?> dari <?= mysqli_num_rows($query) ?> entri
                </div>
                <nav aria-label="Page navigation example">
                    <ul class="pagination mb-0">
                        <li class="page-item disabled"><a class="page-link" href="#">Sebelumnya</a></li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">4</a></li>
                        <li class="page-item"><a class="page-link" href="#">Selanjutnya</a></li>
                        <li class="page-item"><a class="page-link" href="#">Terakhir</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // JavaScript for formatting number inputs (Volume, Harga Satuan, Nilai Perolehan)
    document.addEventListener('DOMContentLoaded', function() {
        const formatNumberInput = (inputElement) => {
            inputElement.addEventListener('input', function(e) {
                let value = e.target.value;
                // Remove existing thousand separators and replace comma with dot for decimal
                value = value.replace(/\./g, '').replace(/,/g, '.');
                
                // Allow only numbers and a single decimal point
                value = value.target.value.replace(/[^\d.]/g, '');

                // Ensure only one decimal point
                const parts = value.split('.');
                if (parts.length > 2) {
                    value = parts[0] + '.' + parts.slice(1).join('');
                }

                // Format with thousand separators and comma for decimal display
                const formattedValue = new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 0, // No minimum fraction digits for input
                    maximumFractionDigits: 2, // Max 2 decimal places for input
                }).format(parseFloat(value) || 0);

                e.target.value = formattedValue;
            });
        };

        const volumeInput = document.getElementById('volume');
        const hargaSatuanInput = document.getElementById('harga_satuan');
        const nilaiPerolehanInput = document.getElementById('nilai_perolehan');

        if (volumeInput) formatNumberInput(volumeInput);
        if (hargaSatuanInput) formatNumberInput(hargaSatuanInput);
        if (nilaiPerolehanInput) formatNumberInput(nilaiPerolehanInput);
    });
</script>
</body>
</html>