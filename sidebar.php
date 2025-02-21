<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Pastikan user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Ambil role user dari session
$role = $_SESSION['role'];
?>


<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard.php">
        <div class="sidebar-brand-text mx-3">TOKO ONLINE</div>
    </a>

    <!-- Admin Menu -->
    <?php if ($role == "admin") { ?>
        <!-- <li class="nav-item">
            <a class="nav-link" href="dashboard.php">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li> -->
        <li class="nav-item">
            <a class="nav-link" href="product.php">
                <i class="fas fa-fw fa-box"></i>
                <span>Produk</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="users.php">
                <i class="fas fa-fw fa-users"></i>
                <span>Pengguna</span>
            </a>
        </li>
    <?php } ?>

    <!-- Customer Menu -->
    <?php if ($role == "customer") { ?>
        <li class="nav-item">
            <a class="nav-link" href="transactions.php">
                <i class="fas fa-fw fa-shopping-cart"></i>
                <span>Transaksi</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="keranjang.php">
                <i class="fas fa-fw fa-shopping-basket"></i>
                <span>Keranjang</span>
            </a>
        </li>
    <?php } ?>

    <!-- Tampilkan Logout Jika Login -->
    <?php if ($role) { ?>
        <li class="nav-item">
            <a class="nav-link" href="logout.php">
                <i class="fas fa-fw fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </li>
    <?php } ?>

</ul>
