<?php
session_start();

// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "kasir");
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Proses registrasi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $telepon = mysqli_real_escape_string($conn, $_POST['telepon']);
    
    // Cek apakah username sudah ada
    $check_user = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $check_user);

    if (mysqli_num_rows($result) > 0) {
        $error = "Username sudah digunakan!";
    } else {
        // Enkripsi password sebelum menyimpan
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Simpan data pengguna baru dengan role customer
        $query = "INSERT INTO users (nama_lengkap, username, password, alamat, telepon, role) 
                  VALUES ('$nama_lengkap', '$username', '$hashed_password', '$alamat', '$telepon', 'customer')";
        
        if (mysqli_query($conn, $query)) {
            $_SESSION['username'] = $username;
            $_SESSION['role'] = 'customer'; // Simpan role ke session
            header("Location: login.php");
            exit();
        } else {
            $error = "Gagal mendaftar. Silakan coba lagi!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Register - Kasir</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        .register-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .register-card {
            width: 400px;
        }
    </style>
</head>

<body class="bg-gradient-primary">
    <div class="container register-container">
        <div class="card o-hidden border-0 shadow-lg register-card">
            <div class="card-body p-5">
                <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">REGISTER</h1>
                </div>
                <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
                <form class="user" method="POST" action="">
                    <div class="form-group">
                        <input type="text" name="nama_lengkap" class="form-control form-control-user" placeholder="Nama Lengkap" required>
                    </div>
                    <div class="form-group">
                        <input type="text" name="username" class="form-control form-control-user" placeholder="Username" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control form-control-user" placeholder="Password" required>
                    </div>
                    <div class="form-group">
                        <input type="text" name="alamat" class="form-control form-control-user" placeholder="Alamat" required>
                    </div>
                    <div class="form-group">
                        <input type="text" name="telepon" class="form-control form-control-user" placeholder="No Telepon" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-user btn-block">Daftar</button>
                </form>
                <hr>
                <div class="text-center">
                    <a class="small" href="login.php">Sudah punya akun? Login!</a>
                </div>
            </div>
        </div>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
mysqli_close($conn);
?>
