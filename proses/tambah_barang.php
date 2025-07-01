<?php
error_reporting(E_ALL); // Tampilkan semua error PHP
ini_set('display_errors', 1); // Aktifkan display error di browser

include '../config/koneksi.php'; // Pastikan path ke koneksi.php sudah benar

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Memastikan request adalah POST
    $jenis = $_GET['jenis']; // Mengambil jenis dari parameter URL

    switch ($jenis) { // Menggunakan switch berdasarkan jenis barang
        case 'tanah':
            $kode_barang = mysqli_real_escape_string($koneksi, $_POST['kode_barang']);
            $nama_barang = mysqli_real_escape_string($koneksi, $_POST['nama_barang']);
            $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
            $status_tanah = mysqli_real_escape_string($koneksi, $_POST['status_tanah']);
            $tahun_perolehan = mysqli_real_escape_string($koneksi, $_POST['tahun_perolehan']);

            $luas_unformatted = str_replace('.', '', $_POST['luas']);
            $luas_unformatted = str_replace(',', '.', $luas_unformatted);
            $luas = mysqli_real_escape_string($koneksi, $luas_unformatted);

            $nilai_unformatted = str_replace('.', '', $_POST['nilai']);
            $nilai_unformatted = str_replace(',', '.', $nilai_unformatted);
            $nilai = mysqli_real_escape_string($koneksi, $nilai_unformatted);

            $nama_opd = mysqli_real_escape_string($koneksi, $_POST['nama_opd']);
            $sub_opd = mysqli_real_escape_string($koneksi, $_POST['sub_opd']);
            $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan']);

            $file_pdf_for_db = NULL; // Default to NULL for database path
            $physical_file_path = NULL; // Store physical path for potential deletion

            if (empty($kode_barang) || empty($nama_barang) || empty($alamat) || empty($status_tanah) || empty($tahun_perolehan) || empty($luas_unformatted) || empty($nilai_unformatted) || empty($nama_opd) || empty($sub_opd)) {
                echo "<script>alert('Semua field wajib diisi!'); window.location.href='../index.php?page=tanah';</script>";
                exit();
            }

            if (!is_numeric($luas_unformatted) || !is_numeric($nilai_unformatted)) {
                echo "<script>alert('Luas dan Nilai harus berupa angka yang valid.'); window.location.href='../index.php?page=tanah';</script>";
                exit();
            }

            if (isset($_FILES['file_pdf']) && $_FILES['file_pdf']['error'] == UPLOAD_ERR_OK) {
                $file_name = $_FILES['file_pdf']['name'];
                $file_tmp = $_FILES['file_pdf']['tmp_name'];
                $file_size = $_FILES['file_pdf']['size'];
                $file_type = $_FILES['file_pdf']['type'];
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

                $allowed_extensions = array("pdf");
                $max_file_size = 5 * 1024 * 1024; // 5MB

                if (in_array($file_ext, $allowed_extensions) === false) {
                    echo "<script>alert('Ekstensi file tidak diizinkan. Hanya file PDF yang diperbolehkan.'); window.location.href='../index.php?page=tanah';</script>";
                    exit();
                }

                if ($file_size > $max_file_size) {
                    echo "<script>alert('Ukuran file terlalu besar. Maksimal 5MB.'); window.location.href='../index.php?page=tanah';</script>";
                    exit();
                }

                $new_file_name = uniqid('tanah_doc_') . '.' . $file_ext;
                // Physical path for storing the file
                $upload_dir_physical = realpath(__DIR__ . '/../uploads/tanah_documents/');
                
                // Create directory if it doesn't exist
                if (!is_dir($upload_dir_physical)) {
                    mkdir($upload_dir_physical, 0777, true);
                }

                $physical_file_path = $upload_dir_physical . DIRECTORY_SEPARATOR . $new_file_name;

                if (move_uploaded_file($file_tmp, $physical_file_path)) {
                    // Path to store in database (relative to web root)
                    $file_pdf_for_db = 'uploads/tanah_documents/' . $new_file_name;
                } else {
                    echo "<script>alert('Gagal mengupload file PDF. Pastikan direktori uploads/tanah_documents/ memiliki izin tulis.'); window.location.href='../index.php?page=tanah';</script>";
                    exit();
                }
            }

            $query = "INSERT INTO tanah (kode_barang, nama_barang, alamat, status_tanah, tahun_perolehan, luas, nilai, nama_opd, sub_opd, keterangan, file_pdf) 
                      VALUES ('$kode_barang', '$nama_barang', '$alamat', '$status_tanah', '$tahun_perolehan', '$luas', '$nilai', '$nama_opd', '$sub_opd', '$keterangan', " . ($file_pdf_for_db ? "'$file_pdf_for_db'" : "NULL") . ")";
            
            if (mysqli_query($koneksi, $query)) {
                echo "<script>alert('Data tanah berhasil ditambahkan!'); window.location.href='../index.php?page=tanah';</script>";
            } else {
                // If query fails, delete the uploaded physical file if it exists
                if ($physical_file_path && file_exists($physical_file_path)) {
                    unlink($physical_file_path);
                }
                echo "<script>alert('Error: " . mysqli_error($koneksi) . "'); window.location.href='../index.php?page=tanah';</script>";
            }
            break;

        case 'asset':
            $kode_barang = mysqli_real_escape_string($koneksi, $_POST['kode_barang']);
            $nama_barang = mysqli_real_escape_string($koneksi, $_POST['nama_barang']);
            $perolehan = mysqli_real_escape_string($koneksi, $_POST['perolehan']);
            $no_spk_faktur_kuitansi = mysqli_real_escape_string($koneksi, $_POST['no_spk_faktur_kuitansi']);
            $no_bast = mysqli_real_escape_string($koneksi, $_POST['no_bast']);
            $tanggal_bast = !empty($_POST['tanggal_bast']) ? mysqli_real_escape_string($koneksi, $_POST['tanggal_bast']) : NULL;
            $merk_tipe = mysqli_real_escape_string($koneksi, $_POST['merk_tipe']);
            $satuan = mysqli_real_escape_string($koneksi, $_POST['satuan']);
            
            $volume_unformatted = str_replace('.', '', $_POST['volume']);
            $volume_unformatted = str_replace(',', '.', $volume_unformatted);
            $volume = mysqli_real_escape_string($koneksi, $volume_unformatted);

            $harga_satuan_unformatted = str_replace('.', '', $_POST['harga_satuan']);
            $harga_satuan_unformatted = str_replace(',', '.', $harga_satuan_unformatted);
            $harga_satuan = mysqli_real_escape_string($koneksi, $harga_satuan_unformatted);

            $nilai_perolehan_unformatted = str_replace('.', '', $_POST['nilai_perolehan']);
            $nilai_perolehan_unformatted = str_replace(',', '.', $nilai_perolehan_unformatted);
            $nilai_perolehan = mysqli_real_escape_string($koneksi, $nilai_perolehan_unformatted);
            
            $umur_ekonomis = !empty($_POST['umur_ekonomis']) ? mysqli_real_escape_string($koneksi, $_POST['umur_ekonomis']) : NULL;
            $kondisi = mysqli_real_escape_string($koneksi, $_POST['kondisi']);
            $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan']); // Keep existing keterangan field


            $file_bast_for_db = NULL;
            $physical_file_bast_path = NULL;
            $file_foto_for_db = NULL;
            $physical_file_foto_path = NULL;

            if (empty($kode_barang) || empty($nama_barang) || empty($perolehan) || empty($no_spk_faktur_kuitansi) || empty($no_bast) || empty($tanggal_bast) || empty($merk_tipe) || empty($satuan) || empty($volume_unformatted) || empty($harga_satuan_unformatted) || empty($nilai_perolehan_unformatted) || empty($kondisi)) {
                echo "<script>alert('Semua field wajib diisi kecuali Umur Ekonomis, Upload BAST, dan Upload Foto!'); window.location.href='../index.php?page=asset';</script>";
                exit();
            }

            if (!is_numeric($volume_unformatted) || $volume_unformatted < 0) {
                echo "<script>alert('Volume harus berupa angka positif.'); window.location.href='../index.php?page=asset';</script>";
                exit();
            }
            if (!is_numeric($harga_satuan_unformatted) || $harga_satuan_unformatted < 0) {
                echo "<script>alert('Harga Satuan harus berupa angka positif.'); window.location.href='../index.php?page=asset';</script>";
                exit();
            }
            if (!is_numeric($nilai_perolehan_unformatted) || $nilai_perolehan_unformatted < 0) {
                echo "<script>alert('Nilai Perolehan harus berupa angka positif.'); window.location.href='../index.php?page=asset';</script>";
                exit();
            }
            if (!empty($umur_ekonomis) && (!is_numeric($umur_ekonomis) || $umur_ekonomis < 0)) {
                echo "<script>alert('Umur Ekonomis harus berupa angka positif jika diisi.'); window.location.href='../index.php?page=asset';</script>";
                exit();
            }

            // File Upload for BAST
            if (isset($_FILES['upload_bast']) && $_FILES['upload_bast']['error'] == UPLOAD_ERR_OK) {
                $file_name = $_FILES['upload_bast']['name'];
                $file_tmp = $_FILES['upload_bast']['tmp_name'];
                $file_size = $_FILES['upload_bast']['size'];
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

                $allowed_extensions = array("pdf");
                $max_file_size = 5 * 1024 * 1024; // 5MB

                if (in_array($file_ext, $allowed_extensions) === false) {
                    echo "<script>alert('Ekstensi file BAST tidak diizinkan. Hanya file PDF yang diperbolehkan.'); window.location.href='../index.php?page=asset';</script>";
                    exit();
                }

                if ($file_size > $max_file_size) {
                    echo "<script>alert('Ukuran file BAST terlalu besar. Maksimal 5MB.'); window.location.href='../index.php?page=asset';</script>";
                    exit();
                }

                $new_file_name = uniqid('asset_bast_') . '.' . $file_ext;
                $upload_dir_physical = realpath(__DIR__ . '/../uploads/asset_documents/bast/');
                
                if (!is_dir($upload_dir_physical)) {
                    mkdir($upload_dir_physical, 0777, true);
                }

                $physical_file_bast_path = $upload_dir_physical . DIRECTORY_SEPARATOR . $new_file_name;

                if (move_uploaded_file($file_tmp, $physical_file_bast_path)) {
                    $file_bast_for_db = 'uploads/asset_documents/bast/' . $new_file_name;
                } else {
                    echo "<script>alert('Gagal mengupload file BAST. Pastikan direktori uploads/asset_documents/bast/ memiliki izin tulis.'); window.location.href='../index.php?page=asset';</script>";
                    exit();
                }
            }

            // File Upload for Foto
            if (isset($_FILES['upload_foto']) && $_FILES['upload_foto']['error'] == UPLOAD_ERR_OK) {
                $file_name = $_FILES['upload_foto']['name'];
                $file_tmp = $_FILES['upload_foto']['tmp_name'];
                $file_size = $_FILES['upload_foto']['size'];
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

                $allowed_extensions = array("jpg", "jpeg", "png");
                $max_file_size = 5 * 1024 * 1024; // 5MB

                if (in_array($file_ext, $allowed_extensions) === false) {
                    echo "<script>alert('Ekstensi file Foto tidak diizinkan. Hanya file JPG, JPEG, PNG yang diperbolehkan.'); window.location.href='../index.php?page=asset';</script>";
                    exit();
                }

                if ($file_size > $max_file_size) {
                    echo "<script>alert('Ukuran file Foto terlalu besar. Maksimal 5MB.'); window.location.href='../index.php?page=asset';</script>";
                    exit();
                }

                $new_file_name = uniqid('asset_foto_') . '.' . $file_ext;
                $upload_dir_physical = realpath(__DIR__ . '/../uploads/asset_documents/foto/');
                
                if (!is_dir($upload_dir_physical)) {
                    mkdir($upload_dir_physical, 0777, true);
                }

                $physical_file_foto_path = $upload_dir_physical . DIRECTORY_SEPARATOR . $new_file_name;

                if (move_uploaded_file($file_tmp, $physical_file_foto_path)) {
                    $file_foto_for_db = 'uploads/asset_documents/foto/' . $new_file_name;
                } else {
                    echo "<script>alert('Gagal mengupload file Foto. Pastikan direktori uploads/asset_documents/foto/ memiliki izin tulis.'); window.location.href='../index.php?page=asset';</script>";
                    exit();
                }
            }


            $query_asset = "INSERT INTO asset (kode_barang, nama_barang, perolehan, no_spk_faktur_kuitansi, no_bast, tanggal_bast, merk_tipe, satuan, volume, harga_satuan, nilai_perolehan, umur_ekonomis, kondisi, keterangan, file_bast, file_foto) 
                            VALUES (
                                '$kode_barang', 
                                '$nama_barang', 
                                '$perolehan', 
                                '$no_spk_faktur_kuitansi', 
                                '$no_bast', 
                                " . ($tanggal_bast ? "'$tanggal_bast'" : "NULL") . ",
                                '$merk_tipe', 
                                '$satuan', 
                                '$volume', 
                                '$harga_satuan', 
                                '$nilai_perolehan', 
                                " . ($umur_ekonomis ? "'$umur_ekonomis'" : "NULL") . ",
                                '$kondisi', 
                                '$keterangan',
                                " . ($file_bast_for_db ? "'$file_bast_for_db'" : "NULL") . ",
                                " . ($file_foto_for_db ? "'$file_foto_for_db'" : "NULL") . "
                            )";
            
            if (mysqli_query($koneksi, $query_asset)) {
                echo "<script>alert('Data aset berhasil ditambahkan!'); window.location.href='../index.php?page=asset';</script>";
            } else {
                // If query fails, delete any uploaded files
                if ($physical_file_bast_path && file_exists($physical_file_bast_path)) {
                    unlink($physical_file_bast_path);
                }
                if ($physical_file_foto_path && file_exists($physical_file_foto_path)) {
                    unlink($physical_file_foto_path);
                }
                echo "<script>alert('Error menambahkan data aset: " . mysqli_error($koneksi) . "'); window.location.href='../index.php?page=asset';</script>";
            }
            break;

        case 'bangunan':
            $kode_barang = mysqli_real_escape_string($koneksi, $_POST['kode_barang']);
            $nama_barang = mysqli_real_escape_string($koneksi, $_POST['nama_barang']);
            $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
            $status_kepemilikan = mysqli_real_escape_string($koneksi, $_POST['status_kepemilikan']);
            $tahun_perolehan = mysqli_real_escape_string($koneksi, $_POST['tahun_perolehan']);
            
            $luas_bangunan_unformatted = str_replace('.', '', $_POST['luas_bangunan']);
            $luas_bangunan_unformatted = str_replace(',', '.', $luas_bangunan_unformatted);
            $luas_bangunan = mysqli_real_escape_string($koneksi, $luas_bangunan_unformatted);

            $nilai_unformatted = str_replace('.', '', $_POST['nilai']);
            $nilai_unformatted = str_replace(',', '.', $nilai_unformatted);
            $nilai = mysqli_real_escape_string($koneksi, $nilai_unformatted);

            $nama_opd = mysqli_real_escape_string($koneksi, $_POST['nama_opd']);
            $sub_opd = mysqli_real_escape_string($koneksi, $_POST['sub_opd']);
            $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan']);

            $file_dokumen_for_db = NULL;
            $physical_file_path = NULL;

            if (empty($kode_barang) || empty($nama_barang) || empty($alamat) || empty($status_kepemilikan) || empty($tahun_perolehan) || empty($luas_bangunan_unformatted) || empty($nilai_unformatted) || empty($nama_opd) || empty($sub_opd)) {
                echo "<script>alert('Semua field wajib diisi!'); window.location.href='../index.php?page=bangunan';</script>";
                exit();
            }
            if (!is_numeric($luas_bangunan_unformatted) || !is_numeric($nilai_unformatted)) {
                echo "<script>alert('Luas Bangunan dan Nilai harus berupa angka yang valid.'); window.location.href='../index.php?page=bangunan';</script>";
                exit();
            }

            if (isset($_FILES['file_dokumen']) && $_FILES['file_dokumen']['error'] == UPLOAD_ERR_OK) {
                $file_name = $_FILES['file_dokumen']['name'];
                $file_tmp = $_FILES['file_dokumen']['tmp_name'];
                $file_size = $_FILES['file_dokumen']['size'];
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

                $allowed_extensions = array("pdf", "jpg", "jpeg", "png");
                $max_file_size = 5 * 1024 * 1024; // 5MB

                if (in_array($file_ext, $allowed_extensions) === false) {
                    echo "<script>alert('Ekstensi file tidak diizinkan. Hanya PDF/Gambar yang diperbolehkan.'); window.location.href='../index.php?page=bangunan';</script>";
                    exit();
                }
                if ($file_size > $max_file_size) {
                    echo "<script>alert('Ukuran file terlalu besar. Maksimal 5MB.'); window.location.href='../index.php?page=bangunan';</script>";
                    exit();
                }

                $new_file_name = uniqid('bangunan_doc_') . '.' . $file_ext;
                $upload_dir_physical = realpath(__DIR__ . '/../uploads/bangunan_documents/');
                
                if (!is_dir($upload_dir_physical)) {
                    mkdir($upload_dir_physical, 0777, true);
                }
                $physical_file_path = $upload_dir_physical . DIRECTORY_SEPARATOR . $new_file_name;

                if (move_uploaded_file($file_tmp, $physical_file_path)) {
                    $file_dokumen_for_db = 'uploads/bangunan_documents/' . $new_file_name;
                } else {
                    echo "<script>alert('Gagal mengupload file dokumen. Pastikan direktori uploads/bangunan_documents/ memiliki izin tulis.'); window.location.href='../index.php?page=bangunan';</script>";
                    exit();
                }
            }

            $query = "INSERT INTO bangunan (kode_barang, nama_barang, alamat, status_kepemilikan, tahun_perolehan, luas_bangunan, nilai, nama_opd, sub_opd, keterangan, file_dokumen) 
                      VALUES ('$kode_barang', '$nama_barang', '$alamat', '$status_kepemilikan', '$tahun_perolehan', '$luas_bangunan', '$nilai', '$nama_opd', '$sub_opd', '$keterangan', " . ($file_dokumen_for_db ? "'$file_dokumen_for_db'" : "NULL") . ")";
            
            if (mysqli_query($koneksi, $query)) {
                echo "<script>alert('Data bangunan berhasil ditambahkan!'); window.location.href='../index.php?page=bangunan';</script>";
            } else {
                if ($physical_file_path && file_exists($physical_file_path)) {
                    unlink($physical_file_path);
                }
                echo "<script>alert('Error: " . mysqli_error($koneksi) . "'); window.location.href='../index.php?page=bangunan';</script>";
            }
            break;

        case 'jij':
            $kode_barang = mysqli_real_escape_string($koneksi, $_POST['kode_barang']);
            $nama_barang = mysqli_real_escape_string($koneksi, $_POST['nama_barang']);
            $lokasi = mysqli_real_escape_string($koneksi, $_POST['lokasi']);
            
            $panjang_unformatted = !empty($_POST['panjang']) ? str_replace(',', '.', str_replace('.', '', $_POST['panjang'])) : NULL;
            $lebar_unformatted = !empty($_POST['lebar']) ? str_replace(',', '.', str_replace('.', '', $_POST['lebar'])) : NULL;
            $luas_unformatted = !empty($_POST['luas']) ? str_replace(',', '.', str_replace('.', '', $_POST['luas'])) : NULL;
            
            $tahun_pembangunan = mysqli_real_escape_string($koneksi, $_POST['tahun_pembangunan']);
            
            $nilai_unformatted = str_replace('.', '', $_POST['nilai']);
            $nilai_unformatted = str_replace(',', '.', $nilai_unformatted);
            $nilai = mysqli_real_escape_string($koneksi, $nilai_unformatted);
            
            $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan']);

            if (empty($kode_barang) || empty($nama_barang) || empty($lokasi) || empty($tahun_pembangunan) || empty($nilai_unformatted)) {
                echo "<script>alert('Kode Barang, Nama Barang, Lokasi, Tahun Pembangunan, dan Nilai wajib diisi!'); window.location.href='../index.php?page=jij';</script>";
                exit();
            }
            if (!is_numeric($nilai_unformatted) || (!empty($panjang_unformatted) && !is_numeric($panjang_unformatted)) || (!empty($lebar_unformatted) && !is_numeric($lebar_unformatted)) || (!empty($luas_unformatted) && !is_numeric($luas_unformatted))) {
                echo "<script>alert('Nilai, Panjang, Lebar, dan Luas harus berupa angka yang valid.'); window.location.href='../index.php?page=jij';</script>";
                exit();
            }

            $query = "INSERT INTO jij (kode_barang, nama_barang, lokasi, panjang, lebar, luas, tahun_pembangunan, nilai, keterangan) 
                      VALUES ('$kode_barang', '$nama_barang', '$lokasi', " . ($panjang_unformatted ? "'$panjang_unformatted'" : "NULL") . ", " . ($lebar_unformatted ? "'$lebar_unformatted'" : "NULL") . ", " . ($luas_unformatted ? "'$luas_unformatted'" : "NULL") . ", '$tahun_pembangunan', '$nilai', '$keterangan')";
            
            if (mysqli_query($koneksi, $query)) {
                echo "<script>alert('Data JIJ berhasil ditambahkan!'); window.location.href='../index.php?page=jij';</script>";
            } else {
                echo "<script>alert('Error: " . mysqli_error($koneksi) . "'); window.location.href='../index.php?page=jij';</script>";
            }
            break;

        case 'aset_lain':
            $kode_barang = mysqli_real_escape_string($koneksi, $_POST['kode_barang']);
            $nama_barang = mysqli_real_escape_string($koneksi, $_POST['nama_barang']);
            $jumlah = mysqli_real_escape_string($koneksi, $_POST['jumlah']);
            $satuan = mysqli_real_escape_string($koneksi, $_POST['satuan']);
            $tahun_perolehan = mysqli_real_escape_string($koneksi, $_POST['tahun_perolehan']);
            
            $nilai_unformatted = str_replace('.', '', $_POST['nilai']);
            $nilai_unformatted = str_replace(',', '.', $nilai_unformatted);
            $nilai = mysqli_real_escape_string($koneksi, $nilai_unformatted);
            
            $kondisi = mysqli_real_escape_string($koneksi, $_POST['kondisi']);
            $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan']);

            if (empty($kode_barang) || empty($nama_barang) || empty($jumlah) || empty($satuan) || empty($tahun_perolehan) || empty($nilai_unformatted)) {
                echo "<script>alert('Semua field wajib diisi!'); window.location.href='../index.php?page=aset_lain';</script>";
                exit();
            }
            if (!is_numeric($jumlah) || $jumlah < 0) {
                echo "<script>alert('Jumlah harus berupa angka positif.'); window.location.href='../index.php?page=aset_lain';</script>";
                exit();
            }
            if (!is_numeric($nilai_unformatted)) {
                echo "<script>alert('Nilai harus berupa angka yang valid.'); window.location.href='../index.php?page=aset_lain';</script>";
                exit();
            }

            $query = "INSERT INTO aset_lain (kode_barang, nama_barang, jumlah, satuan, tahun_perolehan, nilai, kondisi, keterangan) 
                      VALUES ('$kode_barang', '$nama_barang', '$jumlah', '$satuan', '$tahun_perolehan', '$nilai', '$kondisi', '$keterangan')";
            
            if (mysqli_query($koneksi, $query)) {
                echo "<script>alert('Data Aset Lain berhasil ditambahkan!'); window.location.href='../index.php?page=aset_lain';</script>";
            } else {
                echo "<script>alert('Error: " . mysqli_error($koneksi) . "'); window.location.href='../index.php?page=aset_lain';</script>";
            }
            break;
            
        case 'ruang':
            $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
            $kode_ruang = mysqli_real_escape_string($koneksi, $_POST['kode_ruang']);
            $lokasi = mysqli_real_escape_string($koneksi, $_POST['lokasi']);
            $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan']);

            if (empty($nama) || empty($kode_ruang) || empty($lokasi)) {
                echo "<script>alert('Nama, Kode Ruang, dan Lokasi wajib diisi!'); window.location.href='../index.php?page=ruang';</script>";
                exit();
            }

            $query_ruang = "INSERT INTO ruang (nama, kode_ruang, lokasi, keterangan) VALUES ('$nama', '$kode_ruang', '$lokasi', '$keterangan')";
            if (mysqli_query($koneksi, $query_ruang)) {
                header("Location: ../index.php?page=ruang");
            } else {
                echo "<script>alert('Error menambahkan data ruang: " . mysqli_error($koneksi) . "'); window.location.href='../index.php?page=ruang';</script>";
            }
            break;

        case 'sarana':
            $nama_sarana = mysqli_real_escape_string($koneksi, $_POST['nama_sarana']);
            $kode_sarana = mysqli_real_escape_string($koneksi, $_POST['kode_sarana']);
            $lokasi = mysqli_real_escape_string($koneksi, $_POST['lokasi']);
            $jumlah = mysqli_real_escape_string($koneksi, $_POST['jumlah']);
            $kondisi = mysqli_real_escape_string($koneksi, $_POST['kondisi']);
            $tahun_perolehan = !empty($_POST['tahun_perolehan']) ? mysqli_real_escape_string($koneksi, $_POST['tahun_perolehan']) : NULL;
            $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan']);

            if (empty($nama_sarana) || empty($kode_sarana) || empty($lokasi) || empty($jumlah) || empty($kondisi)) {
                echo "<script>alert('Nama Sarana, Kode Sarana, Lokasi, Jumlah, dan Kondisi wajib diisi!'); window.location.href='../index.php?page=sarana';</script>";
                exit();
            }
            if (!is_numeric($jumlah) || $jumlah < 0) {
                echo "<script>alert('Jumlah harus berupa angka positif.'); window.location.href='../index.php?page=sarana';</script>";
                exit();
            }

            $query = "INSERT INTO sarana (nama_sarana, kode_sarana, lokasi, jumlah, kondisi, tahun_perolehan, keterangan) 
                      VALUES ('$nama_sarana', '$kode_sarana', '$lokasi', '$jumlah', '$kondisi', " . ($tahun_perolehan ? "'$tahun_perolehan'" : "NULL") . ", '$keterangan')";
            
            if (mysqli_query($koneksi, $query)) {
                echo "<script>alert('Data Sarana berhasil ditambahkan!'); window.location.href='../index.php?page=sarana';</script>";
            } else {
                echo "<script>alert('Error: " . mysqli_error($koneksi) . "'); window.location.href='../index.php?page=sarana';</script>";
            }
            break;
            
        case 'bhp':
            $nama_barang = mysqli_real_escape_string($koneksi, $_POST['nama_barang']);
            $jumlah = mysqli_real_escape_string($koneksi, $_POST['jumlah']);
            $satuan = mysqli_real_escape_string($koneksi, $_POST['satuan']);
            $tanggal_masuk = mysqli_real_escape_string($koneksi, $_POST['tanggal_masuk']);
            $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan']);

            if (empty($nama_barang) || empty($jumlah) || empty($satuan) || empty($tanggal_masuk)) {
                echo "<script>alert('Nama Barang, Jumlah, Satuan, dan Tanggal Masuk wajib diisi!'); window.location.href='../index.php?page=bhp';</script>";
                exit();
            }
            if (!is_numeric($jumlah) || $jumlah < 0) {
                echo "<script>alert('Jumlah harus berupa angka positif.'); window.location.href='../index.php?page=bhp';</script>";
                exit();
            }

            $query_bhp = "INSERT INTO bhp (nama_barang, jumlah, satuan, tanggal_masuk, keterangan) VALUES ('$nama_barang', '$jumlah', '$satuan', '$tanggal_masuk', '$keterangan')";
            if (mysqli_query($koneksi, $query_bhp)) {
                header("Location: ../index.php?page=bhp");
            } else {
                echo "<script>alert('Error menambahkan data BHP: " . mysqli_error($koneksi) . "'); window.location.href='../index.php?page=bhp';</script>";
            }
            break;

        case 'peminjaman':
            $nama_peminjam = mysqli_real_escape_string($koneksi, $_POST['nama_peminjam']);
            $nama_barang = mysqli_real_escape_string($koneksi, $_POST['nama_barang']);
            $jumlah = mysqli_real_escape_string($koneksi, $_POST['jumlah']);
            $tanggal_pinjam = mysqli_real_escape_string($koneksi, $_POST['tanggal_pinjam']);
            $tanggal_kembali = !empty($_POST['tanggal_kembali']) ? mysqli_real_escape_string($koneksi, $_POST['tanggal_kembali']) : NULL; // Tanggal kembali bisa kosong
            $status = mysqli_real_escape_string($koneksi, $_POST['status']);

            if (empty($nama_peminjam) || empty($nama_barang) || empty($jumlah) || empty($tanggal_pinjam) || empty($status)) {
                echo "<script>alert('Nama Peminjam, Nama Barang, Jumlah, Tanggal Pinjam, dan Status wajib diisi!'); window.location.href='../index.php?page=peminjaman';</script>";
                exit();
            }
            if (!is_numeric($jumlah) || $jumlah < 0) {
                echo "<script>alert('Jumlah harus berupa angka positif.'); window.location.href='../index.php?page=peminjaman';</script>";
                exit();
            }


            $query_pinjam = "INSERT INTO peminjaman (nama_peminjam, nama_barang, jumlah, tanggal_pinjam, tanggal_kembali, status) VALUES ('$nama_peminjam', '$nama_barang', '$jumlah', '$tanggal_pinjam', " . ($tanggal_kembali ? "'$tanggal_kembali'" : "NULL") . ", '$status')";
            if (mysqli_query($koneksi, $query_pinjam)) {
                header("Location: ../index.php?page=peminjaman");
            } else {
                echo "<script>alert('Error menambahkan data peminjaman: " . mysqli_error($koneksi) . "'); window.location.href='../index.php?page=peminjaman';</script>";
            }
            break;

        case 'pengguna':
            $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
            $username = mysqli_real_escape_string($koneksi, $_POST['username']);
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password untuk keamanan
            $level = mysqli_real_escape_string($koneksi, $_POST['level']);

            if (empty($nama) || empty($username) || empty($_POST['password']) || empty($level)) {
                echo "<script>alert('Nama, Username, Password, dan Level wajib diisi!'); window.location.href='../index.php?page=pengguna';</script>";
                exit();
            }

            // Cek apakah username sudah ada
            $check_username_query = mysqli_query($koneksi, "SELECT id FROM pengguna WHERE username = '$username'");
            if (mysqli_num_rows($check_username_query) > 0) {
                echo "<script>alert('Username sudah terdaftar. Silakan gunakan username lain.'); window.location.href='../index.php?page=pengguna';</script>";
                exit();
            }

            $query_pengguna = "INSERT INTO pengguna (nama, username, password, level) VALUES ('$nama', '$username', '$password', '$level')";
            if (mysqli_query($koneksi, $query_pengguna)) {
                header("Location: ../index.php?page=pengguna");
            } else {
                echo "<script>alert('Error menambahkan data pengguna: " . mysqli_error($koneksi) . "'); window.location.href='../index.php?page=pengguna';</script>";
            }
            break;

        default:
            echo "<script>alert('Jenis tidak dikenali!'); window.location.href='../index.php';</script>";
            break;
    }

    mysqli_close($koneksi); // Tutup koneksi database setelah semua operasi selesai
} else {
    echo "<script>alert('Akses tidak sah.'); window.location.href='../index.php';</script>";
}
?>