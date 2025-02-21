<?php
session_start();
require_once('config.php'); // Pastikan file ini ada dan berisi koneksi ke database

// Cek apakah user adalah admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Ambil semua data user dari database
$sql = "SELECT * FROM users";
$result = $conn->query($sql);

// Tambah user baru
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tambah_user'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $nama_lengkap = $_POST['nama_lengkap'];
    $alamat = $_POST['alamat'];
    $telepon = $_POST['telepon'];
    $role = $_POST['role'];

    $conn->query("INSERT INTO users (username, password, nama_lengkap, alamat, telepon, role) VALUES ('$username', '$password', '$nama_lengkap', '$alamat', '$telepon', '$role')");
    header("Location: users.php");
    exit();
}

// Edit user
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_user'])) {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $alamat = $_POST['alamat'];
    $telepon = $_POST['telepon'];
    $role = $_POST['role'];

    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $conn->query("UPDATE users SET username='$username', password='$password', nama_lengkap='$nama_lengkap', alamat='$alamat', telepon='$telepon', role='$role' WHERE id=$id");
    } else {
        $conn->query("UPDATE users SET username='$username', nama_lengkap='$nama_lengkap', alamat='$alamat', telepon='$telepon', role='$role' WHERE id=$id");
    }
    header("Location: users.php");
    exit();
}

// Hapus user
if (isset($_POST['hapus_user'])) {
    $id = $_POST['id'];
    $conn->query("DELETE FROM users WHERE id=$id");
    header("Location: users.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Kelola Users</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</head>
<body id="page-top">
    <div id="wrapper">
        <?php include 'sidebar.php'; ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include 'topbar.php'; ?>
                <div class="container-fluid">
                    <h1 class="h3 mb-4 text-gray-800">Kelola Users</h1>

                    <!-- Tambah User -->
                    <div class="card mb-4">
                        <div class="card-header">Tambah User</div>
                        <div class="card-body">
                            <form method="POST">
                                <div class="mb-3">
                                    <label>Username</label>
                                    <input type="text" class="form-control" name="username" required>
                                </div>
                                <div class="mb-3">
                                    <label>Password</label>
                                    <input type="password" class="form-control" name="password" required>
                                </div>
                                <div class="mb-3">
                                    <label>Nama Lengkap</label>
                                    <input type="text" class="form-control" name="nama_lengkap" required>
                                </div>
                                <div class="mb-3">
                                    <label>Alamat</label>
                                    <textarea class="form-control" name="alamat" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label>Telepon</label>
                                    <input type="text" class="form-control" name="telepon" required>
                                </div>
                                <div class="mb-3">
                                    <label>Role</label>
                                    <select class="form-control" name="role">
                                        <option value="admin">Admin</option>
                                        <option value="customer">Customer</option>
                                    </select>
                                </div>
                                <button type="submit" name="tambah_user" class="btn btn-success">Tambah User</button>
                            </form>
                        </div>
                    </div>

                    <!-- Tabel Data Users -->
                    <div class="card">
                        <div class="card-header">Daftar User</div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Username</th>
                                        <th>Nama Lengkap</th>
                                        <th>Alamat</th>
                                        <th>Telepon</th>
                                        <th>Role</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $no = 1;
                                    while ($row = $result->fetch_assoc()) { ?>
                                        <tr>
                                            <td><?php echo $no++; ?></td>
                                            <td><?php echo $row['username']; ?></td>
                                            <td><?php echo $row['nama_lengkap']; ?></td>
                                            <td><?php echo $row['alamat']; ?></td>
                                            <td><?php echo $row['telepon']; ?></td>
                                            <td><?php echo $row['role']; ?></td>
                                            <td>
                                                <button class="btn btn-warning btn-sm edit-btn" 
                                                    data-id="<?php echo $row['id']; ?>"
                                                    data-username="<?php echo $row['username']; ?>"
                                                    data-nama="<?php echo $row['nama_lengkap']; ?>"
                                                    data-alamat="<?php echo $row['alamat']; ?>"
                                                    data-telepon="<?php echo $row['telepon']; ?>"
                                                    data-role="<?php echo $row['role']; ?>"
                                                >Edit</button>
                                                <button class="btn btn-danger btn-sm hapus-btn" data-id="<?php echo $row['id']; ?>">Hapus</button>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Modal Edit User -->
                     
                    <!-- Modal Edit User -->
                    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel">Edit User</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST">
                                        <!-- Hidden ID -->
                                        <input type="hidden" name="id" id="edit-id">

                                        <!-- Username -->
                                        <div class="mb-3">
                                            <label for="edit-username" class="form-label">Username</label>
                                            <input type="text" class="form-control" name="username" id="edit-username" required>
                                        </div>

                                        <!-- Password (Opsional) -->
                                        <div class="mb-3">
                                            <label for="edit-password" class="form-label">Password (Kosongkan jika tidak ingin mengubah)</label>
                                            <input type="password" class="form-control" name="password">
                                        </div>

                                        <!-- Nama Lengkap -->
                                        <div class="mb-3">
                                            <label for="edit-nama" class="form-label">Nama Lengkap</label>
                                            <input type="text" class="form-control" name="nama_lengkap" id="edit-nama" required>
                                        </div>

                                        <!-- Alamat -->
                                        <div class="mb-3">
                                            <label for="edit-alamat" class="form-label">Alamat</label>
                                            <textarea class="form-control" name="alamat" id="edit-alamat" required></textarea>
                                        </div>

                                        <!-- Telepon -->
                                        <div class="mb-3">
                                            <label for="edit-telepon" class="form-label">Telepon</label>
                                            <input type="text" class="form-control" name="telepon" id="edit-telepon" required>
                                        </div>

                                        <!-- Role -->
                                        <div class="mb-3">
                                            <label for="edit-role" class="form-label">Role</label>
                                            <select class="form-control" name="role" id="edit-role" required>
                                                <option value="admin">Admin</option>
                                                <option value="customer">Customer</option>
                                            </select>
                                        </div>

                                        <!-- Tombol Simpan -->
                                        <button type="submit" name="edit_user" class="btn btn-primary">Simpan</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
    <script>
    $(document).ready(function() {
        // Klik tombol Edit
        $(".edit-btn").click(function() {
            var id = $(this).data("id");
            var username = $(this).data("username");
            var nama = $(this).data("nama");
            var alamat = $(this).data("alamat");
            var telepon = $(this).data("telepon");
            var role = $(this).data("role");

            $("#edit-id").val(id);
            $("#edit-username").val(username);
            $("#edit-nama").val(nama);
            $("#edit-alamat").val(alamat);
            $("#edit-telepon").val(telepon);
            $("#edit-role").val(role);

            $("#editModal").modal("show");
        });

        // Klik tombol Hapus
        $(".hapus-btn").click(function() {
            var id = $(this).data("id");
            if (confirm("Apakah Anda yakin ingin menghapus user ini?")) {
                $.post("users.php", { hapus_user: true, id: id }, function() {
                    location.reload();
                });
            }
        });
    });
    </script>
</body>
</html>
