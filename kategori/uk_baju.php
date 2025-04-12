<?php
require './app/koneksi.php';

$delete_status = isset($_SESSION['delete_status']) ? $_SESSION['delete_status'] : '';

$delete_message = isset($_SESSION['delete_message']) ? $_SESSION['delete_message'] : '';

unset($_SESSION['delete_status']);
unset($_SESSION['delete_message']);

// Get all categories
$sql = "SELECT id_ukbaju, ukuran_bj FROM uk_baju";
$result = $koneksi->query($sql);
$all_categories = [];
if ($result->num_rows > 0) {
    $all_categories = $result->fetch_all(MYSQLI_ASSOC);
}

$show_all = isset($_GET['show_all']) && $_GET['show_all'] == '1';
$categories_to_display = $show_all ? $all_categories : array_slice($all_categories, 0, 3);
$total_categories = count($all_categories);
?>

<?php if ($delete_status): ?>
<div id="notification" style="position:fixed; top:20px; left:50%; transform:translateX(-50%); background-color:<?= $delete_status === 'success' ? '#4CAF50' : '#f44336' ?>; color:white; padding:15px;
 border-radius:5px; box-shadow:0 4px 8px rgba(0,0,0,0.1); z-index:1000; display:flex; justify-content:space-between; align-items:center; min-width:300px;">
    <span><?= htmlspecialchars($delete_message) ?></span>
    <button onclick="document.getElementById('notification').style.display='none'" style="background:none; border:none; color:white; font-weight:bold; cursor:pointer; margin-left:15px;">×</button>
</div>

<?php endif; ?>
<link rel="stylesheet" href="./style/baju.css">
<div class="recent-orders">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1>Ukuran Baju</h1>
        <div style="display: flex; align-items: center; flex-wrap: wrap;">
            <div style="width: 100%; margin-bottom: 10px; order: 1;">
                <input type="text" id="liveSearch" placeholder="Cari Ukuran Baju." 
                       style="padding: 8px 12px; width: 100%; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            <div class="desktop-view" style="display: flex; align-items: center; order: 2;">
                <?php
                    require_once './app/koneksi.php';
                    $query_user = "SELECT COUNT(id_kategori) as total_kategori FROM kategori";
                    $result_user = mysqli_query($koneksi, $query_user);
                    $data_kategori = mysqli_fetch_assoc($result_user);
                    $total_kategori = $data_kategori['total_kategori'];
                    ?>
                <a href="index.php?page=kategori" style="background-color: #9C27B0; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; text-decoration: none; margin-right: 10px;">
                    <i class="fas fa-plus" style="margin-right: 8px;"></i>Kategori (<?php echo $total_kategori; ?>)
                </a>

                <a href="index.php?page=tbaju" class="<?php 
                        $currentPage = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
                        echo ($currentPage == 'tbaju') ? 'active' : '';
                        ?>" style="background-color: #2196F3; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; text-decoration: none; margin-right: 10px;">
                        <i class="fas fa-tshirt" style="margin-right: 8px;"></i> Tambah Ukuran
                </a>
                <?php
                $query_celana = "SELECT COUNT(id_ukcelana) as total_celana FROM uk_celana";
                $result_celana = mysqli_query($koneksi, $query_celana);
                $data_celana = mysqli_fetch_assoc($result_celana);
                $total_celana = $data_celana['total_celana'];
                ?>
                <a href="index.php?page=uk_celana" class="<?php
                        $currentPage = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
                        echo ($currentPage == 'uk_celana') ? 'active' : '';
                        ?>" style="background-color: #4CAF50; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; text-decoration: none;">
                        <i class="fas fa-pants" style="margin-right: 8px;"></i> Ukuran Celana (<?php echo $total_celana; ?>)
                </a>
            </div>
            <div class="dropdown" style="position: relative; display: none; order: 2;">
                <button class="dropbtn" style="background-color: #9C27B0; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">
                    All Menu ▼
                </button>
                <div class="dropdown-content" style="display: none; position: absolute; right: 0; background-color: #f9f9f9; min-width: 200px; box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2); z-index: 1;">
                    <a href="index.php?page=kategori" style="color: black; padding: 12px 16px; text-decoration: none; display: block; border-bottom: 1px solid #ddd;">
                        <i class="fas fa-plus" style="margin-right: 8px;"></i> Kategori (<?php echo $total_kategori; ?>)
                    </a>
                    
                    <a href="index.php?page=tbaju" class="<?php 
                            $currentPage = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
                            echo ($currentPage == 'tbaju') ? 'active' : '';
                            ?>" style="color: black; padding: 12px 16px; text-decoration: none; display: block; border-bottom: 1px solid #ddd;">
                            <i class="fas fa-tshirt" style="margin-right: 8px;"></i> Tambah Ukuran
                    </a>
                    
                    <a href="index.php?page=uk_celana" class="<?php
                            $currentPage = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
                            echo ($currentPage == 'uk_celana') ? 'active' : '';
                            ?>" style="color: black; padding: 12px 16px; text-decoration: none; display: block;">
                            <i class="fas fa-pants" style="margin-right: 8px;"></i> Ukuran Celana (<?php echo $total_celana; ?>)
                    </a>
                </div>
            </div>
        </div>
        </div>
    <div id="categoryTableContainer">
        <table style="width:100%; border-collapse:collapse; text-align:left;">
            <thead>
                <tr style="background-color:var(--color-background);">
                    <th style="padding:12px; border:1px solid #ddd;">ID</th>
                    <th style="padding:12px; border:1px solid #ddd;">UKURAN BAJU</th>
                    <th style="padding:12px; border:1px solid #ddd;">AKSI</th>
                </tr>
            </thead>
            <tbody id="categoryTableBody">
                <?php if (!empty($categories_to_display)): ?>
                    <?php foreach($categories_to_display as $row): ?>
                        <tr>
                            <td style="padding:12px; border:1px solid #ddd;"><?= htmlspecialchars($row["id_ukbaju"]) ?></td>
                            <td style="padding:12px; border:1px solid #ddd;"><?= htmlspecialchars($row["ukuran_bj"]) ?></td>
                            <td style="padding:12px; border:1px solid #ddd;">
                                <button onclick="window.location.href='index.php?page=ebaju&id=<?= $row["id_ukbaju"] ?>'" 
                                        style="background-color:#FFD700; padding:5px 10px; border:none; border-radius:3px; cursor:pointer; margin-right:5px;">
                                    Edit
                                </button>
                                <button onclick="confirmDelete(<?= $row['id_ukbaju'] ?>)" 
                                        style="background-color:#f44336; color:white; padding:5px 10px; border:none; border-radius:3px; cursor:pointer;">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" style="padding:12px; text-align:center;">Tidak ada data ukuran</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <?php if ($total_categories > 3): ?>
        <div style="text-align: center; margin-top: 20px;">
            <?php if (!$show_all): ?>
                <button onclick="window.location.href='?page=<?= $_GET['page'] ?? '' ?>&show_all=1'"
                        style="background-color: #6C9BCF; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer;">
                    Tampilkan Semua (<?= $total_categories ?>)
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

<script src="./js/baju.js"></script>
