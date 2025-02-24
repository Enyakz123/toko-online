<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Pastikan user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Beli Produk</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include 'topbar.php'; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <h1 class="h3 mb-4 text-gray-800">Beli Produk</h1>

                    <div class="row">
                        <?php
                        // Koneksi ke database
                        include("config.php");

                        // Query untuk mengambil data produk
                        $sql = "SELECT * FROM products";
                        $result = mysqli_query($conn, $sql);

                        while ($row = mysqli_fetch_assoc($result)) { ?>
                            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                                <div class="card">
                                    <!-- <img src="img/<?php echo $row['image']; ?>" class="card-img-top" alt="<?php echo $row['name']; ?>"> -->
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo $row['name']; ?></h5>
                                        <p class="card-text">Rp <?php echo number_format($row['price'], 0, ',', '.'); ?></p>
                                        <button class="btn btn-primary" onclick="addToCart(<?php echo htmlspecialchars(json_encode($row)); ?>)">
                                            <i class="fas fa-cart-plus"></i> Tambah ke Keranjang
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php }
                        mysqli_close($conn);
                        ?>
                    </div>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript -->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages -->
    <script src="js/sb-admin-2.min.js"></script>

    <script>
        function addToCart(product) {
            $.ajax({
                url: 'tambah_ke_keranjang.php',
                type: 'POST',
                data: { id: product.id, name: product.name, price: product.price },
                success: function(response) {
                    alert(product.name + " berhasil ditambahkan ke keranjang!");
                }
            });
        }
    </script>

</body>
</html>
