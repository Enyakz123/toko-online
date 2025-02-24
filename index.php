<?php
include("config.php");

// Query produk
$sql = "SELECT * FROM products";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Etalase Produk</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top">

<div id="wrapper">
    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
            <div class="sidebar-brand-text mx-3">TOKO ONLINE</div>
        </a>
        
        <li class="nav-item">
            <a class="nav-link" href="index.php">
                <i class="fas fa-fw fa-box"></i>
                <span>Produk</span>
            </a>
        </li>
    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">

            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="btn btn-primary" href="login.php">Login</a>
                    </li>
                </ul>
            </nav>
            <!-- End of Topbar -->

            <!-- Page Content -->
            <div class="container-fluid">
                <h1 class="h3 mb-4 text-gray-800 text-center">Etalase Produk</h1>

                <div class="row">
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="card shadow">
                                <div class="card-body text-center">
                                    <h5 class="card-title"><?= htmlspecialchars($row['name']); ?></h5>
                                    <p class="card-text">Rp <?= number_format($row['price'], 0, ',', '.'); ?></p>
                                    <a href="login.php" class="btn btn-warning">Login untuk Membeli</a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <!-- End Page Content -->

        </div>
    </div>
</div>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="js/sb-admin-2.min.js"></script>

</body>
</html>

<?php mysqli_close($conn); ?>
