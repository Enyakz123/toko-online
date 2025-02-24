<?php
session_start();

include("config.php");

// Proses login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        // Verifikasi password
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $row['role']; // Simpan role dalam session

            // Redirect ke halaman sesuai role
            if ($row['role'] == 'admin') {
                header("Location: product.php");
            } else {
                header("Location: transactions.php");
            }
            exit();
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login - Kasir</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-card {
            width: 400px;
        }
    </style>
</head>

<body class="bg-gradient-primary">
    <div class="container login-container">
        <div class="card o-hidden border-0 shadow-lg login-card">
            <div class="card-body p-5">
                <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">LOGIN</h1>
                </div>
                <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
                <form class="user" method="POST" action="">
                    <div class="form-group">
                        <input type="text" name="username" class="form-control form-control-user" placeholder="Username" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control form-control-user" placeholder="Password" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-user btn-block">Login</button>
                </form>
                <hr>
                <div class="text-center">
                    <a class="small" href="register.php">Belum punya akun? Daftar di sini!</a>
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
