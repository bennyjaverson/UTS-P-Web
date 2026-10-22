<?php
require 'koneksi.php';
$errors = [];

if(isset($_POST['submit'])){
    $nama_barang = trim($_POST['nama_barang']);
    $id_kategori = $_POST['id_kategori'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    if ($nama_barang == ''){
        $errors[] = 'Nama barang wajib diisi.';
    }
    if ($id_kategori == ''){
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
        $id_kategori = (int) $id_kategori;
        $harga = (int) $harga;
        $stok = (int) $stok;

        $cek_barang = mysqli_query($conn, "select * from barang where nama_barang = '$nama_barang' and id_kategori = $id_kategori");

        if (mysqli_num_rows($cek_barang) > 0){
            $query_update = "update barang set stok = stok + $stok where nama_barang = '$nama_barang' and id_kategori = $id_kategori";
            mysqli_query($conn, $query_update);
        } else{
            $query_insert = "insert into barang (nama_barang, id_kategori, harga, stok) values ('$nama_barang', $id_kategori, $harga, $stok)";
            mysqli_query($conn, $query_insert);
        }

        header("Location: index.php?status=sukses");
        exit;
    }
}

$sort = isset($_GET['sort']) ? $_GET['sort'] : '';
$order_clause = "order by b.id_barang asc";

if ($sort == 'kategori'){
    $order_clause = "order by k.nama_kategori asc";
} elseif ($sort == 'harga_asc'){
    $order_clause = "order by b.harga asc";
} elseif ($sort == 'harga_desc'){
    $order_clause = "order by b.harga desc";
} elseif ($sort == 'stok_asc'){
    $order_clause = "order by b.stok asc";
} elseif ($sort == 'stok_desc'){
    $order_clause = "order by b.stok desc";
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
                                    <select name="id_kategori" class="form-select" required>
                                        <option value="" selected>Pilih Kategori</option>
                                        <?php
                                        $query_kat = mysqli_query($conn, "select * from kategori order by nama_kategori asc");
                                        while($kat = mysqli_fetch_assoc($query_kat)){
                                            echo '<option value="' . $kat['id_kategori'] . '">' . htmlspecialchars($kat['nama_kategori']) . '</option>';
                                        }
                                        ?>
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
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">Daftar Barang</h4>
                <form method="GET" action="index.php#daftar" class="d-flex align-items-center gap-2">
                    <label class="form-label mb-0 text-nowrap">Urutkan:</label>
                    <select name="sort" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                        <option value="" <?php if($sort == '') echo 'selected'; ?>>Default (ID)</option>
                        <option value="kategori" <?php if($sort == 'kategori') echo 'selected'; ?>>Kategori (A-Z)</option>
                        <option value="harga_asc" <?php if($sort == 'harga_asc') echo 'selected'; ?>>Harga Terendah</option>
                        <option value="harga_desc" <?php if($sort == 'harga_desc') echo 'selected'; ?>>Harga Tertinggi</option>
                        <option value="stok_asc" <?php if($sort == 'stok_asc') echo 'selected'; ?>>Stok Terkecil</option>
                        <option value="stok_desc" <?php if($sort == 'stok_desc') echo 'selected'; ?>>Stok Terbanyak</option>
                    </select>
                </form>
            </div>
            <div class="kotak-tabel">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col" class="kolom-id">ID</th>
                            <th scope="col">Nama Barang</th>
                            <th scope="col">Kategori</th>
                            <th scope="col" class="kolom-harga">Harga</th>
                            <th scope="col" class="kolom-stok">Stok</th>
                            <th scope="col">Status</th>
                            <th scope="col">Tanggal Ditambah</th>
                            <th scope="col">Terakhir Update</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query_tampil = mysqli_query($conn, "select b.*, k.nama_kategori from barang b join kategori k on b.id_kategori = k.id_kategori $order_clause");
                        while($row = mysqli_fetch_assoc($query_tampil)){
                        ?>
                            <tr>
                                <th scope="row" class="kolom-id"><?php echo $row['id_barang']; ?></th>
                                <td><?php echo htmlspecialchars($row['nama_barang']); ?></td>
                                <td><?php echo htmlspecialchars($row['nama_kategori']); ?></td>
                                <td class="kolom-harga">Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                                <td class="kolom-stok">
                                    <?php echo $row['stok']; ?>
                                    <?php if($row['stok'] <= 5) : ?>
                                        <span class="badge badge-menipis">Stok Menipis</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge <?php echo $row['status'] == 'dijual' ? 'badge-dijual' : 'badge-tidak-dijual'; ?>">
                                        <?php echo $row['status'] == 'dijual' ? 'Dijual' : 'Tidak Dijual'; ?>
                                    </span>
                                </td>
                                <td><?php echo date('d-m-Y H:i', strtotime($row['created_at'])); ?></td>
                                <td><?php echo date('d-m-Y H:i', strtotime($row['updated_at'])); ?></td>
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