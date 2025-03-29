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

<script>
    setTimeout(function(){
        document.getElementById('notification').style.display = 'none';
    }, 3000);
</script>
<?php endif; ?>

<div class="recent-orders">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1>Ukuran Baju</h1>
        <div style="display: flex; align-items: center;">
            <input type="text" id="liveSearch" placeholder="Cari Ukuran..." 
                   style="padding: 8px 12px; width: 120px; border: 1px solid #ddd; border-radius: 4px; margin-right: 10px;">

                   <?php
                    require_once './app/koneksi.php';
                    $query_user = "SELECT COUNT(id_kategori) as total_kategori FROM kategori";
                    $result_user = mysqli_query($koneksi, $query_user);
                    $data_kategori = mysqli_fetch_assoc($result_user);
                    $total_kategori = $data_kategori['total_kategori'];
                    ?>
                   <a href="index.php?page=kategori" style="margin-right: 5px; background-color: #9C27B0; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block;">
                     Kategori | <?php echo $total_kategori; ?>
                    </a>
                    <a href="index.php?page=tbaju" class="<?php 
                            $currentPage = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
                            echo ($currentPage == 'uk_baju') ? 'active' : '';
                            ?>" style="margin-right: 5px; background-color: #4CAF50; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block;">
                            Tambah Ukuran
                    </a>
                    <?php
                    require_once './app/koneksi.php';
                    $query_user = "SELECT COUNT(id_ukcelana) as total_celana FROM uk_celana";
                    $result_user = mysqli_query($koneksi, $query_user);
                    $data_celana = mysqli_fetch_assoc($result_user);
                    $total_celana = $data_celana['total_celana'];
                    ?>
                    <a href="index.php?page=uk_celana" class="<?php
                            $currentPage = isset($_GET['page'])? $_GET['page'] : 'dashboard';
                            echo ($currentPage == 'uk_celana') ? 'active' : '';
                            ?>" style="background-color: #2196F3; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block;">
                            Ukuran Celana | <?php echo $total_celana; ?>
                    </a>
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

<script>
document.getElementById('liveSearch').addEventListener('input', function() {
    const searchValue = this.value.toLowerCase();
    const rows = document.querySelectorAll('#categoryTableBody tr');
    
    rows.forEach(row => {
        const categoryName = row.cells[1].textContent.toLowerCase();
        
        if (categoryName.includes(searchValue)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

function confirmDelete(id_ukbaju) {
    if (confirm("Apakah Anda yakin ingin menghapus ukuran ini?")) {
        window.location.href = './delete/delete_baju.php?id=' + id_ukbaju;
    }
}
</script>