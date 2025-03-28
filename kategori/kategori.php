<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kasir_konveksi";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$delete_status = isset($_SESSION['delete_status']) ? $_SESSION['delete_status'] : '';

$delete_message = isset($_SESSION['delete_message']) ? $_SESSION['delete_message'] : '';

unset($_SESSION['delete_status']);
unset($_SESSION['delete_message']);

// Get all categories
$sql = "SELECT id_kategori, nama_kategori FROM kategori";
$result = $conn->query($sql);
$all_categories = [];
if ($result->num_rows > 0) {
    $all_categories = $result->fetch_all(MYSQLI_ASSOC);
}

$show_all = isset($_GET['show_all']) && $_GET['show_all'] == '1';
$categories_to_display = $show_all ? $all_categories : array_slice($all_categories, 0, 3);
$total_categories = count($all_categories);
?>

<?php if ($delete_status): ?>
<div id="notification" style="position:fixed; top:20px; left:50%; transform:translateX(-50%); background-color:<?= $delete_status === 'success' ? '#4CAF50' : '#f44336' ?>; color:white; padding:15px; border-radius:5px; box-shadow:0 4px 8px rgba(0,0,0,0.1); z-index:1000; display:flex; justify-content:space-between; align-items:center; min-width:300px;">
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
        <h2>Table Kategori</h2>
        <div style="display: flex; align-items: center;">
            <input type="text" id="liveSearch" placeholder="Cari Kategori..." 
                   style="padding: 8px 12px; width: 120px; border: 1px solid #ddd; border-radius: 4px; margin-right: 10px;">
            
                   <a href="index.php?page=tkategori" style="margin-right: 5px; background-color: #9C27B0; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block;">
                     Kategori
                    </a>
                    <a href="index.php?page=uk_baju" class="<?php 
                            $currentPage = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
                            echo ($currentPage == 'uk_baju') ? 'active' : '';
                            ?>" style="margin-right: 5px; background-color: #4CAF50; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block;">
                        <i class="fas fa-tshirt"></i>Ukuran Baju
                    </a>
                    <a href="index.php?page=uk_celana" class="<?php
                            $currentPage = isset($_GET['page'])? $_GET['page'] : 'dashboard';
                            echo ($currentPage == 'uk_celana') ? 'active' : '';
                            ?>" style="background-color: #2196F3; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block;">
                        <i class="fas fa-vest"></i> Ukuran Celana
                    </a>
                </div>
        </div>
    <div id="categoryTableContainer">
        <table style="width:100%; border-collapse:collapse; text-align:left;">
            <thead>
                <tr style="background-color:var(--color-background);">
                    <th style="padding:12px; border:1px solid #ddd;">ID</th>
                    <th style="padding:12px; border:1px solid #ddd;">NAMA KATEGORI</th>
                    <th style="padding:12px; border:1px solid #ddd;">AKSI</th>
                </tr>
            </thead>
            <tbody id="categoryTableBody">
                <?php if (!empty($categories_to_display)): ?>
                    <?php foreach($categories_to_display as $row): ?>
                        <tr>
                            <td style="padding:12px; border:1px solid #ddd;"><?= htmlspecialchars($row["id_kategori"]) ?></td>
                            <td style="padding:12px; border:1px solid #ddd;"><?= htmlspecialchars($row["nama_kategori"]) ?></td>
                            <td style="padding:12px; border:1px solid #ddd;">
                                <button onclick="window.location.href='index.php?page=ekategori&id=<?= $row["id_kategori"] ?>'" 
                                        style="background-color:#FFD700; padding:5px 10px; border:none; border-radius:3px; cursor:pointer; margin-right:5px;">
                                    Edit
                                </button>
                                <button onclick="confirmDelete(<?= $row['id_kategori'] ?>)" 
                                        style="background-color:#f44336; color:white; padding:5px 10px; border:none; border-radius:3px; cursor:pointer;">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" style="padding:12px; text-align:center;">Tidak ada data kategori</td>
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

function confirmDelete(categoryId) {
    if (confirm("Apakah Anda yakin ingin menghapus kategori ini?")) {
        window.location.href = './delete/delete_kategori.php?id=' + categoryId;
    }
}
</script>