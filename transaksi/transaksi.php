<?php
require './app/koneksi.php';

$query = "SELECT MAX(kode_transaksi) as last_code FROM transaksi";
$result = mysqli_query($koneksi, $query);
$row = mysqli_fetch_assoc($result);
$last_code = $row['last_code'];

if ($last_code) {
    $parts = explode('-', $last_code);
    $number = intval($parts[2]) + 1;
    $new_code = "SXY-".date('Y')."-" . str_pad($number, 4, '0', STR_PAD_LEFT);
} else {
    $new_code = "SXY-".date('Y')."-0001";
}

$query = "SELECT MAX(resi) AS last_resi FROM transaksi WHERE resi LIKE 'SXY-JNE-%'";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Query Error: " . mysqli_error($koneksi));
}

$row = mysqli_fetch_assoc($result);
$last_resi = $row['last_resi'];
if ($last_resi && preg_match('/^SXY-JNE-(\d{4})$/', $last_resi, $matches)) {
    $number = intval($matches[1]) + 1;
    $new_resi = "SXY-JNE-" . str_pad($number, 4, '0', STR_PAD_LEFT);
} else {
    $new_resi = "SXY-JNE-0001";
}

$kategori = mysqli_query($koneksi, "SELECT * FROM kategori");
$cstm_bahan = mysqli_query($koneksi, "SELECT * FROM cstm_pbahn");
$produk = mysqli_query($koneksi, "SELECT * FROM produk");
$bahan = mysqli_query($koneksi, "SELECT * FROM bahan");
$uk_baju = mysqli_query($koneksi, "SELECT * FROM uk_baju");
$uk_celana = mysqli_query($koneksi, "SELECT * FROM uk_celana");
?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="./style/transaksi.css">
    <div class="form-container">
        <h1>Kasir | Konveksi</h1>
        <form action="./transaksi/proses_simpan.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="kode_transaksi">Kode Transaksi:</label>
                <input type="text" id="kode_transaksi" name="kode_transaksi" value="<?php echo $new_code; ?>" readonly>
            </div>
            
            <div class="form-group">
                <label for="nama_customer">Nama Customer:</label>
                <input type="text" id="nama_customer" name="nama_customer" required onblur="calculateTotal()">
            </div>

            <div class="form-group">
                <label for="pembelian">pembelian:</label>
                <select id="pembelian" name="pembelian" required onchange="togglePembelianFields()">
                    <option value="">-- Pilih Jenis Pembelian --</option>
                    <option value="siap pakai">Siap pakai</option>
                    <option value="jahit">Jahit</option>
                </select>
            </div>
            <div id="custom_fields" style="display:none;">
                <div class="form-group">
                    <label for="cstm_produk">Custom Produk:</label>
                    <input type="text" id="cstm_produk" name="cstm_produk">
                </div>
                
                <div class="form-group">
                <label for="cstm_bahan">Custom Bahan:</label>
                <select id="cstm_bahan" name="cstm_bahan" onchange="getHargaCustom()">
                    <option value="">-- Pilih Custom bahan--</option>
                    <?php 
                    mysqli_data_seek($cstm_bahan, 0);
                    while($row = mysqli_fetch_assoc($cstm_bahan)): ?>
                        <option value="<?php echo $row['id_cstm']; ?>"><?php echo $row['cstm_bahan']; ?></option>
                    <?php endwhile; ?>
                </select>
                <span id="stokcstm" class="info-text"></span>
            </div>
                <div class="form-group">
                    <label for="cstm_ukuran">Custom Ukuran:</label>
                    <input type="text" id="cstm_ukuran" name="cstm_ukuran">
                </div>
            </div>

            
            <div class="form-group" id="kategori_group"><br>
                <label for="kategori">Kategori:</label><br>
                <select id="kategori" name="kategori" onchange="getProduk()">
                    <option value="">-- Pilih Kategori --</option>
                    <?php while($row = mysqli_fetch_assoc($kategori)): ?>
                        <option value="<?php echo $row['id_kategori']; ?>"><?php echo $row['nama_kategori']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="form-group" id="produk_group">
                <label for="produk">Produk:</label><br>
                <select id="produk" name="produk" onchange="getHarga()">
                    <option value="">-- Pilih Produk --</option>
                    <?php 
                    mysqli_data_seek($produk, 0);
                    while($row = mysqli_fetch_assoc($produk)): ?>
                        <option value="<?php echo $row['id_produk']; ?>"><?php echo $row['nama_produk']; ?></option>
                    <?php endwhile; ?>
                </select>
                <span id="stok" class="info-text"></span>
            </div>
            
            <div class="form-group">
                <label for="harga">Harga:</label>
                <input type="number" id="harga" name="harga" readonly>
            </div>
            
            <div class="form-group">
                <label for="jumlah">Jumlah:</label>
                <input type="number" id="jumlah" name="jumlah" min="1" value="1" onchange="calculateTotal()">
            </div>
            
            <div class="form-group" id="bahan_group">
                <label for="bahan">Bahan Kain:</label><br>
                <select id="bahan" name="bahan">
                    <option value="">-- Pilih Bahan --</option>
                    <?php 
                    mysqli_data_seek($bahan, 0);
                    while($row = mysqli_fetch_assoc($bahan)): ?>
                        <option value="<?php echo $row['id_bahan']; ?>"><?php echo $row['bahan_kain']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="form-group" id="uk_baju_group">
                <label for="uk_baju">Ukuran Baju:</label>
                <select id="uk_baju" name="uk_baju">
                    <option value="">-- Pilih Ukuran Baju --</option>
                    <?php 
                    mysqli_data_seek($uk_baju, 0);
                    while($row = mysqli_fetch_assoc($uk_baju)): ?>
                        <option value="<?php echo $row['id_ukbaju']; ?>"><?php echo $row['ukuran_bj']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="form-group" id="uk_celana_group">
                <label for="uk_celana">Ukuran Celana:</label>
                <select id="uk_celana" name="uk_celana">
                    <option value="">-- Pilih Ukuran Celana --</option>
                    <?php 
                    mysqli_data_seek($uk_celana, 0);
                    while($row = mysqli_fetch_assoc($uk_celana)): ?>
                        <option value="<?php echo $row['id_ukcelana']; ?>"><?php echo $row['ukuran_cln']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
    <label for="status_pengiriman">Status Pengiriman:</label>
    <select id="status_pengiriman" name="status_pengiriman" required onchange="togglePengirimanFields()">
        <option value="">-- Pilih Status --</option>
        <option value="ambil di tempat">Ambil di Tempat</option>
        <option value="kirim">Kirim</option>
    </select>
</div>

<div class="form-group">
    <label for="pembayaran">Metode Pembayaran:</label>
    <select id="pembayaran" name="pembayaran" required onchange="toggleBayar()">
        <option value="">-- Pilih Pembayaran --</option>
        <option value="cash">Cash</option>
        <option value="transfer">Transfer</option>
        <option value="qris">QRIS</option>
    </select>
</div>


<div id="pengiriman_fields" style="display:none;">
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email">
    </div>
    
    <div class="form-group">
    <label for="alamat">Alamat:</label>
    <textarea id="alamat" name="alamat" rows="3" cols="30"></textarea>
</div>


    <div class="form-group">
        <label for="nohp">No. HP:</label>
        <input type="text" id="nohp" name="nohp">
    </div>
    
    <div class="form-group">
        <label for="resi">No. Resi:</label>
        <input type="text" id="resi" name="resi" value="<?php echo $new_resi; ?>" readonly>
    </div>
</div>
            
            <div class="payment-section">
                <h3>Informasi Pembayaran</h3>
                
                <div class="form-group">
                    <label for="subtotal">Total:</label>
                    <input type="number" id="subtotal" name="subtotal" readonly>
                </div>
                
                <div class="form-group">
                    <label for="tax">Pajak (12%):</label>
                    <input type="number" id="tax" name="tax" readonly>
                </div>
                
                <div class="form-group">
                    <label for="diskon">Diskon:</label>
                    <input type="number" id="diskon" name="diskon" readonly>
                    <span id="diskon_info" class="info-text"></span>
                </div>
                
                <div class="form-group">
                    <label for="total">SubTotal:</label>
                    <input type="number" id="total" name="total" readonly>
                </div>
                
                <div class="form-group">
                    <label for="metode_pembayaran">Metode Pembayaran:</label>
                    <select id="metode_pembayaran" name="metode_pembayaran" required onchange="togglePembayaran()">
                        <option value="lunas">Lunas</option>
                        <option value="dp">DP 50%</option>
                    </select>
                </div>
                
                <div id="dp_fields" style="display:none;">
                    <div class="form-group">
                        <label for="dp_amount">Jumlah DP:</label>
                        <input type="number" id="dp_amount" name="dp_amount" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label for="remaining_amount">Sisa Pembayaran:</label>
                        <input type="number" id="remaining_amount" name="remaining_amount" readonly>
                    </div>
                </div>
                
                <div class="form-group">
                <label for="bukti_transaksi" id="label_bukti">Bukti Pembayaran:</label>
                <input type="file" id="bukti_transaksi" name="bukti_transaksi">
                </div>
            
            <div class="form-group">
                <button type="submit" class="btn-submit"
                <?php if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'Admin' && $_SESSION['level'] !== 'Kasir') echo 'disabled style="opacity: 0.6; cursor: not-allowed;" title="Hanya admin dan kasir yang bisa melakukan transaksi"'; ?>>Simpan Transaksi</button>
                <a href="./index.php?page=pelunasan" class="btn-submit" style="text-decoration: none; display: inline-block;">
                    Pelunasan Dp
                </a>
            </div>
        </form>
        
    </div>
    <script src="./js/transaksi.js"></script>
    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
<script>
    Swal.fire({
        title: 'Berhasil!',
        text: 'Transaksi berhasil disimpan.\nKode: <?= htmlspecialchars($_GET['kode']) ?>',
        icon: 'success',
        confirmButtonText: 'Oke'
    });
</script>
<?php endif; ?>

