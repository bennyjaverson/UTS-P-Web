<?php
require 'koneksi.php';
$errors = [];

if(isset($_POST['submit'])){
    $nama_barang = trim($_POST['nama_barang']);
    $kategori = $_POST['kategori'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    if ($nama_barang == ''){
        $errors[] = 'Nama barang wajib diisi.';
    }
    if ($kategori == ''){
        $errors[] = 'Kategori wajib dipilih.';
    }
    if (!ctype_digit($harga) || $harga <= 0){
        $errors[] = 'Harga harus berupa angka lebih dari 0.';
    }
    if (!ctype_digit($stok) || $stok <= 0){
        $errors[] = 'Stok harus berupa angka lebih dari 0.';
    }

    if (empty($errors)){
        $nama_barang = mysqli_real_escape_string($conn, $nama_barang);
        $kategori = mysqli_real_escape_string($conn, $kategori);
        $harga = (int) $harga;
        $stok = (int) $stok;

        $cek_barang = mysqli_query($conn, "select * from barang where nama_barang = '$nama_barang' and kategori = '$kategori'");

        if (mysqli_num_rows($cek_barang)){
            $query_update = "update barang set stok = stok + $stok where nama_barang = '$nama_barang' and kategori = '$kategori'";
            mysqli_query($conn, $query_update);
        } else{
            $query_insert = "insert into barang (nama_barang, kategori, harga, stok) values ('$nama_barang', '$kategori', '$harga', '$stok')";
            mysqli_query($conn, $query_insert);
        }

        header("Location: index.php?status=sukses");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Stok Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Manajemen Stok</a>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#form">Form</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#daftar">Daftar Barang</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <main class="container">
        <section id="form">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6">
                    <div id="area-alert">
                        <?php if(isset($_GET['status']) && $_GET['status'] == 'sukses') : ?>
                            <div class="alert alert-success alert-notif">Data barang berhasil disimpan.</div>
                        <?php endif; ?>
                        <?php if(!empty($errors)) : ?>
                            <div class="alert alert-danger alert-notif">
                                <?php foreach($errors as $error) : ?>
                                    <div><?php echo $error; ?></div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="card">
                        <div class="card-body p-4">
                            <p class="title text-center mb-1">Form Input</p>
                            <h4 class="card-title text-center mb-4">Tambah Stok Barang</h4>
                            <form id="form-barang" method="POST" action="" novalidate>
                                <div class="mb-3">
                                    <label class="form-label">Nama Barang</label>
                                    <input type="text" name="nama_barang" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Kategori</label>
                                    <select name="kategori" class="form-select" required>
                                        <option value="" selected>Pilih Kategori</option>
                                        <option value="Elektronik">Elektronik</option>
                                        <option value="Alat Tulis">Alat Tulis</option>
                                        <option value="Pakaian">Pakaian</option>
                                        <option value="Makanan">Makanan</option>
                                        <option value="Minuman">Minuman</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Harga</label>
                                    <input type="number" name="harga" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Stok</label>
                                    <input type="number" name="stok" class="form-control" required>
                                </div>
                                <div class="text-center mt-4">
                                    <button type="submit" name="submit" class="btn btn-primary px-4">Tambah Stok</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section id="daftar" class="mt-5">
            <h4 class="text-center mb-0">Daftar Barang</h4>
            <div class="kotak-tabel mt-3">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col" class="kolom-id">ID</th>
                            <th scope="col">Nama Barang</th>
                            <th scope="col">Kategori</th>
                            <th scope="col" class="kolom-harga">Harga</th>
                            <th scope="col" class="kolom-stok">Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query_tampil = mysqli_query($conn, "select * from barang order by id_barang asc");
                        while($row = mysqli_fetch_assoc($query_tampil)){
                        ?>
                            <tr>
                                <th scope="row" class="kolom-id"><?php echo $row['id_barang']; ?></th>
                                <td><?php echo htmlspecialchars($row['nama_barang']); ?></td>
                                <td><?php echo htmlspecialchars($row['kategori']); ?></td>
                                <td class="kolom-harga">Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                                <td class="kolom-stok">
                                    <?php echo $row['stok']; ?>
                                    <?php if($row['stok'] <= 5) : ?>
                                        <span class="badge badge-menipis">Stok Menipis</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
    <footer>&copy; Benny Javerson D A (252410103106)</footer>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="script.js"></script>
</body>
</html>
`
<!-- 
Update harga belum ditambahin(jadi di baigan daftar barang belum bisa update harga masih pake harga lama)
sama tambahkan atribut misal kapan ditambahin (tanggal) sama kapan terakhir diupdate (timestamp)
tambahkan fitur shorting di bagian daftar barang berdasarkan, kategori, harga, dan stok(nanti ada 
tombol shorting terus pilih mau short berdasarkan apa)
sama tambahkan atribut kalau barang masih dijual atau sudah tidak dijual lagi 
(berarti nanti nambahin kolom status di database) kalau sudah tidak dijual lagi berarti stoknya 0 
dan statusnya tidak dijual lagi
  -->