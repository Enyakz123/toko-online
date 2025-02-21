<?php
session_start();
require_once('config.php'); // Koneksi ke database
require_once('vendor/tcpdf/tcpdf.php'); // Pastikan path sesuai

// Pastikan user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Ambil data pengguna dari database
$query = $conn->prepare("SELECT nama_lengkap, alamat FROM users WHERE username = ?");
$query->bind_param("s", $username);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();

$nama = $user['nama_lengkap'];
$alamat = $user['alamat'];

// Periksa apakah keranjang belanja kosong
if (empty($_SESSION['cart'])) {
    header("Location: keranjang.php");
    exit();
}

// Jika tombol checkout ditekan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $totalHarga = 0;

    // Membuat objek PDF
    $pdf = new TCPDF();
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('TOKO ONLINE');
    $pdf->SetTitle('Nota Pembelian');
    $pdf->AddPage();

    // Judul Nota
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->Cell(0, 10, "TOKO ONLINE", 0, 1, 'C');
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Cell(0, 10, "NOTA PEMBELIAN", 0, 1, 'C');
    $pdf->Ln(5);

    // Informasi Pembeli
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Cell(0, 10, "Nama: $nama", 0, 1);
    $pdf->Cell(0, 10, "Alamat: $alamat", 0, 1);
    $pdf->Ln(5);

    // Header Tabel
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(80, 10, 'Nama Produk', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Qty', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Harga Satuan', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Subtotal', 1, 1, 'C');

    // Isi Tabel Produk
    $pdf->SetFont('helvetica', '', 12);
    foreach ($_SESSION['cart'] as $id => $item) {
        $subtotal = $item['price'] * $item['quantity'];
        $totalHarga += $subtotal;

        $pdf->Cell(80, 10, $item['name'], 1, 0, 'L');
        $pdf->Cell(30, 10, $item['quantity'], 1, 0, 'C');
        $pdf->Cell(40, 10, 'Rp ' . number_format($item['price'], 0, ',', '.'), 1, 0, 'R');
        $pdf->Cell(40, 10, 'Rp ' . number_format($subtotal, 0, ',', '.'), 1, 1, 'R');
    }

    // Total Harga
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(150, 10, "Total Harga", 1, 0, 'R');
    $pdf->Cell(40, 10, 'Rp ' . number_format($totalHarga, 0, ',', '.'), 1, 1, 'R');

    // Simpan & tampilkan PDF
    ob_clean(); // Bersihkan output buffer sebelum mengirim PDF
    $pdf->Output("nota_pembelian.pdf", "D");

    // Kosongkan keranjang setelah checkout
    unset($_SESSION['cart']);

    // Redirect ke halaman sukses
    header("Location: sukses.php");
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
                    
                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Checkout</h1>
                    
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Detail Pembeli</h6>
                                </div>
                                <div class="card-body">
                                    <p><strong>Nama:</strong> <?php echo htmlspecialchars($nama); ?></p>
                                    <p><strong>Alamat:</strong> <?php echo htmlspecialchars($alamat); ?></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-6">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Ringkasan Pembelian</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Nama Produk</th>
                                                <th>Qty</th>
                                                <th>Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $totalHarga = 0;
                                            if (!empty($_SESSION['cart'])) {
                                                foreach ($_SESSION['cart'] as $item) {
                                                    $subtotal = $item['price'] * $item['quantity'];
                                                    $totalHarga += $subtotal;
                                                    echo "<tr>
                                                            <td>" . htmlspecialchars($item['name']) . "</td>
                                                            <td>{$item['quantity']}</td>
                                                            <td>Rp " . number_format($subtotal, 0, ',', '.') . "</td>
                                                        </tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='3' class='text-center'>Keranjang kosong</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                    <h5 class="text-right"><strong>Total: Rp <?php echo number_format($totalHarga, 0, ',', '.'); ?></strong></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tombol Checkout -->
                    <div class="row">
                        <div class="col-lg-12 text-center">
                            <form method="post">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-money-bill-wave"></i> Checkout & Download Nota
                                </button>
                            </form>
                        </div>
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
