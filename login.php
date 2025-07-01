<?php
session_start();
include 'config/koneksi.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']); // gunakan hash md5 (bisa diganti bcrypt lebih aman)

    $query = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE username='$username' AND password='$password'");
    $data = mysqli_fetch_assoc($query);

    if ($data) {
        $_SESSION['login'] = true;
        $_SESSION['username'] = $data['username'];
        $_SESSION['level'] = $data['level'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Inventaris Sekolah</title>
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="mb-4">Login</h4>
                    <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
                    <form method="post">
                        <div class="mb-3">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" name="login" class="btn btn-primary w-100">Masuk</button>
                    </form>
                </div>
            </div>
            <p class="text-center mt-3 text-muted">&copy; <?= date('Y') ?> Inventaris Sekolah</p>
        </div>
    </div>
</div>
</body>
</html>
