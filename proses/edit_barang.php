<?php
error_reporting(E_ALL); // Tampilkan semua error PHP
ini_set('display_errors', 1); // Aktifkan display error di browser

include '../config/koneksi.php'; // Pastikan path ke koneksi.php sudah benar

// Pastikan request adalah POST dan id serta table tersedia
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id']) && isset($_POST['table'])) {
    $id = mysqli_real_escape_string($koneksi, $_POST['id']);
    $table = mysqli_real_escape_string($koneksi, $_POST['table']);

    switch ($table) {
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

            if (!is_numeric($luas_unformatted) || !is_numeric($nilai_unformatted)) {
                echo "<script>alert('Luas dan Nilai harus berupa angka yang valid.'); window.location.href='../index.php?page=tanah';</script>";
                exit();
            }

            $set_clause = "kode_barang='$kode_barang', 
                           nama_barang='$nama_barang', 
                           alamat='$alamat', 
                           status_tanah='$status_tanah', 
                           tahun_perolehan='$tahun_perolehan', 
                           luas='$luas', 
                           nilai='$nilai', 
                           nama_opd='$nama_opd', 
                           sub_opd='$sub_opd', 
                           keterangan='$keterangan'";

            $new_file_pdf_for_db = NULL;
            $physical_new_file_path = NULL;

            $old_file_pdf_from_db = NULL;
            $old_file_query = mysqli_query($koneksi, "SELECT file_pdf FROM tanah WHERE id = '$id'");
            if ($old_file_query && $old_file_data = mysqli_fetch_assoc($old_file_query)) {
                $old_file_pdf_from_db = $old_file_data['file_pdf'];
            }

            if (isset($_FILES['file_pdf']) && $_FILES['file_pdf']['error'] == UPLOAD_ERR_OK) {
                $file_name = $_FILES['file_pdf']['name'];
                $file_tmp = $_FILES['file_pdf']['tmp_name'];
                $file_size = $_FILES['file_pdf']['size'];
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
                $upload_dir_physical = realpath(__DIR__ . '/../uploads/tanah_documents/');
                
                if (!is_dir($upload_dir_physical)) {
                    mkdir($upload_dir_physical, 0777, true);
                }

                $physical_new_file_path = $upload_dir_physical . DIRECTORY_SEPARATOR . $new_file_name;

                if (move_uploaded_file($file_tmp, $physical_new_file_path)) {
                    $new_file_pdf_for_db = 'uploads/tanah_documents/' . $new_file_name;
                    $set_clause .= ", file_pdf='" . mysqli_real_escape_string($koneksi, $new_file_pdf_for_db) . "'";
                } else {
                    echo "<script>alert('Gagal mengupload file baru. Pastikan direktori uploads/tanah_documents/ memiliki izin tulis.'); window.location.href='../index.php?page=tanah';</script>";
                    exit();
                }
            }

            $update_query = "UPDATE tanah SET " . $set_clause . " WHERE id='$id'";

            if (mysqli_query($koneksi, $update_query)) {
                if ($new_file_pdf_for_db && $old_file_pdf_from_db && file_exists(realpath(__DIR__ . '/../' . $old_file_pdf_from_db))) {
                    unlink(realpath(__DIR__ . '/../' . $old_file_pdf_from_db));
                    error_log("Old file deleted: " . realpath(__DIR__ . '/../' . $old_file_pdf_from_db));
                }
                echo "<script>alert('Data tanah berhasil diupdate!'); window.location.href='../index.php?page=tanah';</script>";
            } else {
                if ($physical_new_file_path && file_exists($physical_new_file_path)) {
                    unlink($physical_new_file_path);
                    error_log("New file deleted due to query error: " . $physical_new_file_path);
                }
                echo "<script>alert('Error mengupdate data tanah: " . mysqli_error($koneksi) . "'); window.location.href='../index.php?page=tanah';</script>";
            }
            break;

        case 'asset':
            $nama_barang = mysqli_real_escape_string($koneksi, $_POST['nama_barang']);
            $merk = mysqli_real_escape_string($koneksi, $_POST['merk']);
            $jumlah = mysqli_real_escape_string($koneksi, $_POST['jumlah']);
            $kondisi = mysqli_real_escape_string($koneksi, $_POST['kondisi']);
            $tahun_perolehan = mysqli_real_escape_string($koneksi, $_POST['tahun_perolehan']);
            $harga_perolehan = mysqli_real_escape_string($koneksi, str_replace('.', '', $_POST['harga_perolehan'])); // Remove dot for number
            $lokasi = mysqli_real_escape_string($koneksi, $_POST['lokasi']);
            $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan']);

            if (!is_numeric($jumlah) || $jumlah < 0) {
                echo "<script>alert('Jumlah harus berupa angka positif.'); window.location.href='../index.php?page=asset';</script>";
                exit();
            }
            if (!is_numeric($harga_perolehan)) {
                echo "<script>alert('Harga Perolehan harus berupa angka yang valid.'); window.location.href='../index.php?page=asset';</script>";
                exit();
            }

            $set_clause = "nama_barang='$nama_barang', 
                           merk='$merk', 
                           jumlah='$jumlah', 
                           kondisi='$kondisi', 
                           tahun_perolehan='$tahun_perolehan', 
                           harga_perolehan='$harga_perolehan', 
                           lokasi='$lokasi', 
                           keterangan='$keterangan'";

            $new_file_dokumen_for_db = NULL;
            $physical_new_file_path = NULL;

            $old_file_dokumen_from_db = NULL;
            $old_file_query = mysqli_query($koneksi, "SELECT file_dokumen FROM asset WHERE id = '$id'");
            if ($old_file_query && $old_file_data = mysqli_fetch_assoc($old_file_query)) {
                $old_file_dokumen_from_db = $old_file_data['file_dokumen'];
            }

            if (isset($_FILES['file_dokumen']) && $_FILES['file_dokumen']['error'] == UPLOAD_ERR_OK) {
                $file_name = $_FILES['file_dokumen']['name'];
                $file_tmp = $_FILES['file_dokumen']['tmp_name'];
                $file_size = $_FILES['file_dokumen']['size'];
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

                $allowed_extensions = array("pdf", "jpg", "jpeg", "png");
                $max_file_size = 5 * 1024 * 1024; // 5MB

                if (in_array($file_ext, $allowed_extensions) === false) {
                    echo "<script>alert('Ekstensi file tidak diizinkan. Hanya PDF/Gambar yang diperbolehkan.'); window.location.href='../index.php?page=asset';</script>";
                    exit();
                }
                if ($file_size > $max_file_size) {
                    echo "<script>alert('Ukuran file terlalu besar. Maksimal 5MB.'); window.location.href='../index.php?page=asset';</script>";
                    exit();
                }

                $new_file_name = uniqid('asset_doc_') . '.' . $file_ext;
                $upload_dir_physical = realpath(__DIR__ . '/../uploads/asset_documents/');
                
                if (!is_dir($upload_dir_physical)) {
                    mkdir($upload_dir_physical, 0777, true);
                }
                $physical_new_file_path = $upload_dir_physical . DIRECTORY_SEPARATOR . $new_file_name;

                if (move_uploaded_file($file_tmp, $physical_new_file_path)) {
                    $new_file_dokumen_for_db = 'uploads/asset_documents/' . $new_file_name;
                    $set_clause .= ", file_dokumen='" . mysqli_real_escape_string($koneksi, $new_file_dokumen_for_db) . "'";
                } else {
                    echo "<script>alert('Gagal mengupload file dokumen. Pastikan direktori uploads/asset_documents/ memiliki izin tulis.'); window.location.href='../index.php?page=asset';</script>";
                    exit();
                }
            }

            $update_query = "UPDATE asset SET " . $set_clause . " WHERE id='$id'";

            if (mysqli_query($koneksi, $update_query)) {
                if ($new_file_dokumen_for_db && $old_file_dokumen_from_db && file_exists(realpath(__DIR__ . '/../' . $old_file_dokumen_from_db))) {
                    unlink(realpath(__DIR__ . '/../' . $old_file_dokumen_from_db));
                    error_log("Old asset file deleted: " . realpath(__DIR__ . '/../' . $old_file_dokumen_from_db));
                }
                echo "<script>alert('Data aset berhasil diupdate!'); window.location.href='../index.php?page=asset';</script>";
            } else {
                if ($physical_new_file_path && file_exists($physical_new_file_path)) {
                    unlink($physical_new_file_path);
                }
                echo "<script>alert('Error mengupdate data aset: " . mysqli_error($koneksi) . "'); window.location.href='../index.php?page=asset';</script>";
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

            if (!is_numeric($luas_bangunan_unformatted) || !is_numeric($nilai_unformatted)) {
                echo "<script>alert('Luas Bangunan dan Nilai harus berupa angka yang valid.'); window.location.href='../index.php?page=bangunan';</script>";
                exit();
            }

            $set_clause = "kode_barang='$kode_barang', 
                           nama_barang='$nama_barang', 
                           alamat='$alamat', 
                           status_kepemilikan='$status_kepemilikan', 
                           tahun_perolehan='$tahun_perolehan', 
                           luas_bangunan='$luas_bangunan', 
                           nilai='$nilai', 
                           nama_opd='$nama_opd', 
                           sub_opd='$sub_opd', 
                           keterangan='$keterangan'";

            $new_file_dokumen_for_db = NULL;
            $physical_new_file_path = NULL;

            $old_file_dokumen_from_db = NULL;
            $old_file_query = mysqli_query($koneksi, "SELECT file_dokumen FROM bangunan WHERE id = '$id'");
            if ($old_file_query && $old_file_data = mysqli_fetch_assoc($old_file_query)) {
                $old_file_dokumen_from_db = $old_file_data['file_dokumen'];
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
                $physical_new_file_path = $upload_dir_physical . DIRECTORY_SEPARATOR . $new_file_name;

                if (move_uploaded_file($file_tmp, $physical_new_file_path)) {
                    $new_file_dokumen_for_db = 'uploads/bangunan_documents/' . $new_file_name;
                    $set_clause .= ", file_dokumen='" . mysqli_real_escape_string($koneksi, $new_file_dokumen_for_db) . "'";
                } else {
                    echo "<script>alert('Gagal mengupload file dokumen. Pastikan direktori uploads/bangunan_documents/ memiliki izin tulis.'); window.location.href='../index.php?page=bangunan';</script>";
                    exit();
                }
            }

            $update_query = "UPDATE bangunan SET " . $set_clause . " WHERE id='$id'";

            if (mysqli_query($koneksi, $update_query)) {
                if ($new_file_dokumen_for_db && $old_file_dokumen_from_db && file_exists(realpath(__DIR__ . '/../' . $old_file_dokumen_from_db))) {
                    unlink(realpath(__DIR__ . '/../' . $old_file_dokumen_from_db));
                    error_log("Old file deleted: " . realpath(__DIR__ . '/../' . $old_file_dokumen_from_db));
                }
                echo "<script>alert('Data bangunan berhasil diupdate!'); window.location.href='../index.php?page=bangunan';</script>";
            } else {
                if ($physical_new_file_path && file_exists($physical_new_file_path)) {
                    unlink($physical_new_file_path);
                }
                echo "<script>alert('Error mengupdate data bangunan: " . mysqli_error($koneksi) . "'); window.location.href='../index.php?page=bangunan';</script>";
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

            if (!is_numeric($nilai_unformatted) || (!empty($panjang_unformatted) && !is_numeric($panjang_unformatted)) || (!empty($lebar_unformatted) && !is_numeric($lebar_unformatted)) || (!empty($luas_unformatted) && !is_numeric($luas_unformatted))) {
                echo "<script>alert('Nilai, Panjang, Lebar, dan Luas harus berupa angka yang valid.'); window.location.href='../index.php?page=jij';</script>";
                exit();
            }

            $update_query = "UPDATE jij SET 
                             kode_barang='$kode_barang', 
                             nama_barang='$nama_barang', 
                             lokasi='$lokasi', 
                             panjang=" . ($panjang_unformatted ? "'$panjang_unformatted'" : "NULL") . ", 
                             lebar=" . ($lebar_unformatted ? "'$lebar_unformatted'" : "NULL") . ", 
                             luas=" . ($luas_unformatted ? "'$luas_unformatted'" : "NULL") . ", 
                             tahun_pembangunan='$tahun_pembangunan', 
                             nilai='$nilai', 
                             keterangan='$keterangan' 
                             WHERE id='$id'";

            if (mysqli_query($koneksi, $update_query)) {
                echo "<script>alert('Data JIJ berhasil diupdate!'); window.location.href='../index.php?page=jij';</script>";
            } else {
                echo "<script>alert('Error mengupdate data JIJ: " . mysqli_error($koneksi) . "'); window.location.href='../index.php?page=jij';</script>";
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

            if (!is_numeric($jumlah) || $jumlah < 0) {
                echo "<script>alert('Jumlah harus berupa angka positif.'); window.location.href='../index.php?page=aset_lain';</script>";
                exit();
            }
            if (!is_numeric($nilai_unformatted)) {
                echo "<script>alert('Nilai harus berupa angka yang valid.'); window.location.href='../index.php?page=aset_lain';</script>";
                exit();
            }

            $update_query = "UPDATE aset_lain SET 
                             kode_barang='$kode_barang', 
                             nama_barang='$nama_barang', 
                             jumlah='$jumlah', 
                             satuan='$satuan', 
                             tahun_perolehan='$tahun_perolehan', 
                             nilai='$nilai', 
                             kondisi='$kondisi', 
                             keterangan='$keterangan' 
                             WHERE id='$id'";

            if (mysqli_query($koneksi, $update_query)) {
                echo "<script>alert('Data Aset Lain berhasil diupdate!'); window.location.href='../index.php?page=aset_lain';</script>";
            } else {
                echo "<script>alert('Error mengupdate data Aset Lain: " . mysqli_error($koneksi) . "'); window.location.href='../index.php?page=aset_lain';</script>";
            }
            break;

        case 'ruang':
            $nama_ruang = mysqli_real_escape_string($koneksi, $_POST['nama_ruang']);
            $kapasitas = mysqli_real_escape_string($koneksi, $_POST['kapasitas']);
            $lokasi = mysqli_real_escape_string($koneksi, $_POST['lokasi']);
            $kondisi = mysqli_real_escape_string($koneksi, $_POST['kondisi']);
            $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan']);

            if (!is_numeric($kapasitas) || $kapasitas < 0) {
                echo "<script>alert('Kapasitas harus berupa angka positif.'); window.location.href='../index.php?page=ruang';</script>";
                exit();
            }

            $update_query = "UPDATE ruang SET 
                             nama_ruang='$nama_ruang', 
                             kapasitas='$kapasitas', 
                             lokasi='$lokasi', 
                             kondisi='$kondisi', 
                             keterangan='$keterangan' 
                             WHERE id='$id'";

            if (mysqli_query($koneksi, $update_query)) {
                echo "<script>alert('Data ruang berhasil diupdate!'); window.location.href='../index.php?page=ruang';</script>";
            } else {
                echo "<script>alert('Error mengupdate data ruang: " . mysqli_error($koneksi) . "'); window.location.href='../index.php?page=ruang';</script>";
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

            if (!is_numeric($jumlah) || $jumlah < 0) {
                echo "<script>alert('Jumlah harus berupa angka positif.'); window.location.href='../index.php?page=sarana';</script>";
                exit();
            }

            $update_query = "UPDATE sarana SET 
                             nama_sarana='$nama_sarana', 
                             kode_sarana='$kode_sarana', 
                             lokasi='$lokasi', 
                             jumlah='$jumlah', 
                             kondisi='$kondisi', 
                             tahun_perolehan=" . ($tahun_perolehan ? "'$tahun_perolehan'" : "NULL") . ", 
                             keterangan='$keterangan' 
                             WHERE id='$id'";

            if (mysqli_query($koneksi, $update_query)) {
                echo "<script>alert('Data Sarana berhasil diupdate!'); window.location.href='../index.php?page=sarana';</script>";
            } else {
                echo "<script>alert('Error mengupdate data Sarana: " . mysqli_error($koneksi) . "'); window.location.href='../index.php?page=sarana';</script>";
            }
            break;
            
        case 'bhp':
            $nama_barang = mysqli_real_escape_string($koneksi, $_POST['nama_barang']);
            $jumlah = mysqli_real_escape_string($koneksi, $_POST['jumlah']);
            $satuan = mysqli_real_escape_string($koneksi, $_POST['satuan']);
            $tanggal_masuk = mysqli_real_escape_string($koneksi, $_POST['tanggal_masuk']);
            $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan']);

            if (!is_numeric($jumlah) || $jumlah < 0) {
                echo "<script>alert('Jumlah harus berupa angka positif.'); window.location.href='../index.php?page=bhp';</script>";
                exit();
            }

            $update_query = "UPDATE bhp SET 
                             nama_barang='$nama_barang', 
                             jumlah='$jumlah', 
                             satuan='$satuan', 
                             tanggal_masuk='$tanggal_masuk', 
                             keterangan='$keterangan' 
                             WHERE id='$id'";

            if (mysqli_query($koneksi, $update_query)) {
                echo "<script>alert('Data BHP berhasil diupdate!'); window.location.href='../index.php?page=bhp';</script>";
            } else {
                echo "<script>alert('Error mengupdate data BHP: " . mysqli_error($koneksi) . "'); window.location.href='../index.php?page=bhp';</script>";
            }
            break;

        case 'peminjaman':
            $id_asset = mysqli_real_escape_string($koneksi, $_POST['id_asset']);
            $peminjam = mysqli_real_escape_string($koneksi, $_POST['peminjam']);
            $tanggal_pinjam = mysqli_real_escape_string($koneksi, $_POST['tanggal_pinjam']);
            $tanggal_kembali = !empty($_POST['tanggal_kembali']) ? mysqli_real_escape_string($koneksi, $_POST['tanggal_kembali']) : NULL;
            $status = mysqli_real_escape_string($koneksi, $_POST['status']);
            $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan']);

            $update_query = "UPDATE peminjaman SET 
                             id_asset='$id_asset', 
                             peminjam='$peminjam', 
                             tanggal_pinjam='$tanggal_pinjam', 
                             tanggal_kembali=" . ($tanggal_kembali ? "'$tanggal_kembali'" : "NULL") . ", 
                             status='$status', 
                             keterangan='$keterangan' 
                             WHERE id='$id'";

            if (mysqli_query($koneksi, $update_query)) {
                echo "<script>alert('Data peminjaman berhasil diupdate!'); window.location.href='../index.php?page=peminjaman';</script>";
            } else {
                echo "<script>alert('Error mengupdate data peminjaman: " . mysqli_error($koneksi) . "'); window.location.href='../index.php?page=peminjaman';</script>";
            }
            break;

        case 'profil':
            $username = mysqli_real_escape_string($koneksi, $_POST['username']);
            $nama_lengkap = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
            $email = mysqli_real_escape_string($koneksi, $_POST['email']);
            $role = mysqli_real_escape_string($koneksi, $_POST['role']);
            // Password tidak diupdate di sini untuk keamanan. Ini harus dilakukan di form terpisah.
            // $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 

            $update_query = "UPDATE pengguna SET 
                             username='$username', 
                             nama_lengkap='$nama_lengkap', 
                             email='$email', 
                             role='$role' 
                             WHERE id='$id'"; // Diasumsikan 'profil' mengedit pengguna yang sedang login, tapi id didapat dari form.

            if (mysqli_query($koneksi, $update_query)) {
                echo "<script>alert('Data profil berhasil diupdate!'); window.location.href='../index.php?page=profil';</script>";
            } else {
                echo "<script>alert('Error mengupdate data profil: " . mysqli_error($koneksi) . "'); window.location.href='../index.php?page=profil';</script>";
            }
            break;

        case 'pengguna':
            $username = mysqli_real_escape_string($koneksi, $_POST['username']);
            $nama_lengkap = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
            $email = mysqli_real_escape_string($koneksi, $_POST['email']);
            $role = mysqli_real_escape_string($koneksi, $_POST['role']);
            
            $set_clause = "username='$username', nama_lengkap='$nama_lengkap', email='$email', role='$role'";
            
            // Periksa jika password baru diisi
            if (!empty($_POST['password'])) {
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $set_clause .= ", password='$password'";
            }

            $update_query = "UPDATE pengguna SET " . $set_clause . " WHERE id='$id'";

            if (mysqli_query($koneksi, $update_query)) {
                echo "<script>alert('Data pengguna berhasil diupdate!'); window.location.href='../index.php?page=pengguna';</script>";
            } else {
                echo "<script>alert('Error mengupdate data pengguna: " . mysqli_error($koneksi) . "'); window.location.href='../index.php?page=pengguna';</script>";
            }
            break;

        default:
            echo "<script>alert('Jenis tabel tidak dikenali.'); window.location.href='../index.php';</script>";
            break;
    }
    mysqli_close($koneksi);
} else {
    echo "<script>alert('Akses tidak sah atau parameter tidak lengkap.'); window.location.href='../index.php';</script>";
}
?>