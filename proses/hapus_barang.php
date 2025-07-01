<?php
error_reporting(E_ALL); // Tampilkan semua error PHP
ini_set('display_errors', 1); // Aktifkan display error di browser

include '../config/koneksi.php'; // Pastikan path ke koneksi.php sudah benar

// Pastikan parameter jenis dan id tersedia
if (!isset($_GET['jenis']) || !isset($_GET['id'])) {
    echo "<script>alert('Parameter tidak lengkap.'); window.location.href='../index.php';</script>";
    exit();
}

$jenis = mysqli_real_escape_string($koneksi, $_GET['jenis']);
$id = mysqli_real_escape_string($koneksi, $_GET['id']); // Escape ID untuk keamanan

switch ($jenis) { //
  case 'tanah':
    // 1. Ambil path file PDF dari database sebelum dihapus
    $query_get_file = mysqli_query($koneksi, "SELECT file_pdf FROM tanah WHERE id = '$id'");
    if ($query_get_file) {
        $data_tanah = mysqli_fetch_assoc($query_get_file);
        $file_to_delete = $data_tanah['file_pdf'];

        // 2. Hapus data dari database
        if (mysqli_query($koneksi, "DELETE FROM tanah WHERE id = '$id'")) {
            // 3. Jika penghapusan dari DB berhasil, hapus file fisik
            if (!empty($file_to_delete)) {
                // Pastikan path fisik file benar.
                // Jika file_pdf disimpan sebagai 'uploads/tanah_documents/namafile.pdf'
                // dan skrip ini ada di 'proses/', maka path fisik adalah '../../uploads/tanah_documents/namafile.pdf'
                // Atau lebih aman menggunakan realpath()
                $physical_file_path = realpath(__DIR__ . '/../' . $file_to_delete);
                
                if ($physical_file_path && file_exists($physical_file_path)) {
                    if (unlink($physical_file_path)) {
                        // File berhasil dihapus
                        error_log("File PDF dihapus: " . $physical_file_path);
                    } else {
                        // Gagal menghapus file (mungkin izin)
                        error_log("Gagal menghapus file PDF: " . $physical_file_path . " - Periksa izin.");
                    }
                } else {
                    // File tidak ditemukan secara fisik, meskipun ada di DB, atau path kosong
                    error_log("File PDF tidak ditemukan secara fisik atau path kosong: " . $file_to_delete);
                }
            }
            echo "<script>alert('Data tanah berhasil dihapus!'); window.location.href='../index.php?page=tanah';</script>";
        } else {
            // Error saat menghapus dari database
            echo "<script>alert('Error menghapus data tanah dari database: " . mysqli_error($koneksi) . "'); window.location.href='../index.php?page=tanah';</script>";
        }
    } else {
        // Error saat mengambil data file dari database
        echo "<script>alert('Error mengambil informasi file dari database: " . mysqli_error($koneksi) . "'); window.location.href='../index.php?page=tanah';</script>";
    }
    break;
  case 'asset': //
    mysqli_query($koneksi, "DELETE FROM asset WHERE id = '$id'"); //
    header("Location: ../index.php?page=asset"); //
    break; //
    
  case 'bangunan':
    $query_get_file = mysqli_query($koneksi, "SELECT file_dokumen FROM bangunan WHERE id = '$id'");
    if ($query_get_file) {
        $data_bangunan = mysqli_fetch_assoc($query_get_file);
        $file_to_delete = $data_bangunan['file_dokumen'];

        if (mysqli_query($koneksi, "DELETE FROM bangunan WHERE id = '$id'")) {
            if (!empty($file_to_delete)) {
                $physical_file_path = realpath(__DIR__ . '/../' . $file_to_delete);
                if ($physical_file_path && file_exists($physical_file_path)) {
                    if (unlink($physical_file_path)) {
                        error_log("File dokumen bangunan dihapus: " . $physical_file_path);
                    } else {
                        error_log("Gagal menghapus file dokumen bangunan: " . $physical_file_path . " - Periksa izin.");
                    }
                } else {
                    error_log("File dokumen bangunan tidak ditemukan secara fisik atau path kosong: " . $file_to_delete);
                }
            }
            echo "<script>alert('Data bangunan berhasil dihapus!'); window.location.href='../index.php?page=bangunan';</script>";
        } else {
            echo "<script>alert('Error menghapus data bangunan dari database: " . mysqli_error($koneksi) . "'); window.location.href='../index.php?page=bangunan';</script>";
        }
    } else {
        echo "<script>alert('Error mengambil informasi file dari database: " . mysqli_error($koneksi) . "'); window.location.href='../index.php?page=bangunan';</script>";
    }
    break;

  case 'jij':
    if (mysqli_query($koneksi, "DELETE FROM jij WHERE id = '$id'")) {
        echo "<script>alert('Data JIJ berhasil dihapus!'); window.location.href='../index.php?page=jij';</script>";
    } else {
        echo "<script>alert('Error menghapus data JIJ: " . mysqli_error($koneksi) . "'); window.location.href='../index.php?page=jij';</script>";
    }
    break;

  case 'aset_lain':
    if (mysqli_query($koneksi, "DELETE FROM aset_lain WHERE id = '$id'")) {
        echo "<script>alert('Data Aset Lain berhasil dihapus!'); window.location.href='../index.php?page=aset_lain';</script>";
    } else {
        echo "<script>alert('Error menghapus data Aset Lain: " . mysqli_error($koneksi) . "'); window.location.href='../index.php?page=aset_lain';</script>";
    }
    break;

  
  case 'ruang': //
    mysqli_query($koneksi, "DELETE FROM ruang WHERE id = '$id'"); //
    header("Location: ../index.php?page=ruang"); //
    break; //

  case 'sarana':
    if (mysqli_query($koneksi, "DELETE FROM sarana WHERE id = '$id'")) {
        echo "<script>alert('Data Sarana berhasil dihapus!'); window.location.href='../index.php?page=sarana';</script>";
    } else {
        echo "<script>alert('Error menghapus data Sarana: " . mysqli_error($koneksi) . "'); window.location.href='../index.php?page=sarana';</script>";
    }
    break;

  case 'bhp': //
    mysqli_query($koneksi, "DELETE FROM bhp WHERE id = '$id'"); //
    header("Location: ../index.php?page=bhp"); //
    break; //
    
  case 'peminjaman': //
    mysqli_query($koneksi, "DELETE FROM peminjaman WHERE id = '$id'"); //
    header("Location: ../index.php?page=peminjaman"); //
    break; //
    
  case 'pengguna': //
    mysqli_query($koneksi, "DELETE FROM pengguna WHERE id = '$id'"); //
    header("Location: ../index.php?page=pengguna"); //
    break; //
  default: //
    echo "Jenis tidak dikenali!"; //
}

mysqli_close($koneksi); // Tutup koneksi database
?>