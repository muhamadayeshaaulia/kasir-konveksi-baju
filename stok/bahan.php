<?php
require './app/koneksi.php';
$delete_status = isset($_SESSION['delete_status']) ? $_SESSION['delete_status'] : '';

$delete_message = isset($_SESSION['delete_message']) ? $_SESSION['delete_message'] : '';

unset($_SESSION['delete_status']);
unset($_SESSION['delete_message']);

$sql = "SELECT id_bahan, bahan_kain FROM bahan";
$result = $koneksi->query($sql);
$all_users = [];
if ($result->num_rows > 0) {
    $all_users = $result->fetch_all(MYSQLI_ASSOC);
}

$show_all = isset($_GET['show_all']) && $_GET['show_all'] == '1';
$users_to_display = $show_all ? $all_users : array_slice($all_users, 0, 3);
$total_users = count($all_users);
?>

<?php if ($delete_status): ?>
<div id="notification" style="position:fixed; top:20px; left:50%; transform:translateX(-50%); background-color:<?= $delete_status === 'success' ? '#4CAF50' : '#f44336' ?>; color:white; padding:15px; border-radius:5px; box-shadow:0 4px 8px rgba(0,0,0,0.1); z-index:1000; display:flex; justify-content:space-between; align-items:center; min-width:300px;">
    <span><?= htmlspecialchars($delete_message) ?></span>
    <button onclick="document.getElementById('notification').style.display='none'" style="background:none; border:none; color:white; font-weight:bold; cursor:pointer; margin-left:15px;">×</button>
</div>

<?php endif; ?>
<link rel="stylesheet" href="./style/bahan.css">
<div class="recent-orders">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1>Bahan </h1>
        
        <div style="display: flex; align-items: center; flex-wrap: wrap;">
            <div style="width: 100%; margin-bottom: 10px; order: 1;">
                <input type="text" id="liveSearch" placeholder="Cari Bahan." 
                       style="padding: 8px 12px; width: 100%; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            <?php
                    require_once './app/koneksi.php';
                    $query_user = "SELECT COUNT(id_produk) as total_produk FROM produk";
                    $result_user = mysqli_query($koneksi, $query_user);
                    $data_produk = mysqli_fetch_assoc($result_user);
                    $total_produk = $data_produk['total_produk'];
                    ?>
            <div class="desktop-view" style="display: flex; align-items: center; order: 2;">
                <a href="index.php?page=stok" style="background-color: #9C27B0; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; text-decoration: none; margin-right: 10px;">
                    <i class="fas fa-plus" style="margin-right: 8px;"></i> Produk (<?php echo $total_produk?>)
                </a>
                
                <a href="index.php?page=tbahan" class="<?php 
                        $currentPage = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
                        echo ($currentPage == 'tbahan') ? 'active' : '';
                        ?>" style="background-color: #2196F3; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; text-decoration: none; margin-right: 10px;">
                        <i class="fas fa-tshirt" style="margin-right: 8px;"></i> Tambah Bahan
                </a>
            </div>
            
            <div class="dropdown" style="position: relative; display: none; order: 2;">
                <button class="dropbtn" style="background-color: #9C27B0; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">
                    All Menu ▼
                </button>
                <div class="dropdown-content" style="display: none; position: absolute; right: 0; background-color: #f9f9f9; min-width: 200px; box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2); z-index: 1;">
                    <a href="index.php?page=stok" style="color: black; padding: 12px 16px; text-decoration: none; display: block; border-bottom: 1px solid #ddd;">
                        <i class="fas fa-plus" style="margin-right: 8px;"></i>Produk (<?php echo $total_produk?>)
                    </a>
                    <a href="index.php?page=tbahan" class="<?php 
                            $currentPage = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
                            echo ($currentPage == 'tbahan') ? 'active' : '';
                            ?>" style="color: black; padding: 12px 16px; text-decoration: none; display: block; border-bottom: 1px solid #ddd;">
                            <i class="fas fa-tshirt" style="margin-right: 8px;"></i> Tambah Bahan
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div id="userTableContainer">
        <table style="width:100%; border-collapse:collapse; text-align:left;">
            <thead>
                <tr style="background-color:var(--color-background);">
                    <th style="padding:12px; border:1px solid #ddd;">ID</th>
                    <th style="padding:12px; border:1px solid #ddd;">NAMA BAHAN</th>
                    <th style="padding:12px; border:1px solid #ddd;">AKSI</th>
                </tr>
            </thead>
            <tbody id="userTableBody">
                <?php if (!empty($users_to_display)): ?>
                    <?php foreach($users_to_display as $row): ?>
                        <tr>
                            <td style="padding:12px; border:1px solid #ddd;"><?= htmlspecialchars($row["id_bahan"]) ?></td>
                            <td style="padding:12px; border:1px solid #ddd;"><?= htmlspecialchars($row["bahan_kain"]) ?></td>
                            <td style="padding:12px; border:1px solid #ddd;">
                                <button onclick="window.location.href='index.php?page=ebahan&id=<?= $row["id_bahan"] ?>'" 
                                        style="background-color:#FFD700; padding:5px 10px; border:none; border-radius:3px; cursor:pointer; margin-right:5px;">
                                    Edit
                                </button>
                                <?php if ($_SESSION['level'] === 'Admin'): ?>
                                    <button onclick="confirmDelete(<?= $row['id_bahan'] ?>)" 
                                            style="background-color:#f44336; color:white; padding:5px 10px; border:none; border-radius:3px; cursor:pointer;">
                                        Hapus
                                    </button>
                                <?php else: ?>
                                    <button onclick="showAdminOnlyMessage()" 
                                            style="background-color:#f44336; color:white; padding:5px 10px; border:none; border-radius:3px; cursor:pointer; opacity:0.6;" 
                                            title="Hanya admin yang bisa menghapus">
                                        Hapus
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="padding:12px; text-align:center;">Tidak ada data user</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <?php if ($total_users > 3): ?>
        <div style="text-align: center; margin-top: 20px;">
            <?php if (!$show_all): ?>
                <button onclick="window.location.href='?page=<?= $_GET['page'] ?? '' ?>&show_all=1'"
                        style="background-color: #6C9BCF; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer;">
                    Tampilkan Semua (<?= $total_users ?>)
                </button>
            <?php else: ?>
                <button onclick="window.location.href='?page=<?= $_GET['page'] ?? '' ?>&show_all=0'"
                        style="background-color: #f44336; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer;">
                    Tampilkan Sedikit
                </button>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<script src="./js/bahan.js">
</script>
