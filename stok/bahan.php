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

<script>
    setTimeout(function(){
        document.getElementById('notification').style.display = 'none';
    }, 3000);
</script>
<?php endif; ?>

<div class="recent-orders">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1>Table Bahan</h1>
        
        <div style="display: flex; align-items: center;">
            <input type="text" id="liveSearch" placeholder="Cari Bahan..." 
                   style="padding: 8px 12px; width: 120px; border: 1px solid #ddd; border-radius: 4px; margin-right: 10px;">
            
                   <?php
                    require_once './app/koneksi.php';
                    $query_user = "SELECT COUNT(id_produk) as total_produk FROM produk";
                    $result_user = mysqli_query($koneksi, $query_user);
                    $data_produk = mysqli_fetch_assoc($result_user);
                    $total_produk = $data_produk['total_produk'];
                    ?>
                   <a href="index.php?page=stok" style="margin-right: 5px; background-color: #9C27B0; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block;">
                     Produk | <?php echo $total_produk; ?>
                    </a>
                    <a href="index.php?page=tbahan" class="<?php
                            $currentPage = isset($_GET['page'])? $_GET['page'] : 'dashboard';
                            echo ($currentPage == 'uk_celana') ? 'active' : '';
                            ?>" style="background-color: #2196F3; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block;">
                            Tambah Bahan
                    </a>
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

<script>
function showAdminOnlyMessage() {
    const notification = document.createElement('div');
    notification.id = 'adminNotification';
    notification.style.position = 'fixed';
    notification.style.top = '20px';
    notification.style.left = '50%';
    notification.style.transform = 'translateX(-50%)';
    notification.style.backgroundColor = '#f44336';
    notification.style.color = 'white';
    notification.style.padding = '15px';
    notification.style.borderRadius = '5px';
    notification.style.boxShadow = '0 4px 8px rgba(0,0,0,0.1)';
    notification.style.zIndex = '1000';
    notification.style.display = 'flex';
    notification.style.justifyContent = 'space-between';
    notification.style.alignItems = 'center';
    notification.style.minWidth = '300px';
    
    notification.innerHTML = `
        <span>Hanya admin yang dapat menghapus user!</span>
        <button onclick="this.parentElement.style.display='none'" 
                style="background:none; border:none; color:white; font-weight:bold; cursor:pointer; margin-left:15px;">
            ×
        </button>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(function(){
        notification.style.display = 'none';
    }, 3000);
}

document.getElementById('liveSearch').addEventListener('input', function() {
    const searchValue = this.value.toLowerCase();
    const rows = document.querySelectorAll('#userTableBody tr');
    
    rows.forEach(row => {
        const username = row.cells[1].textContent.toLowerCase();
        const email = row.cells[2].textContent.toLowerCase();
        
        if (username.includes(searchValue) || email.includes(searchValue)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

function confirmDelete(userId) {
    if (confirm("Apakah Anda yakin ingin menghapus user ini?")) {
        window.location.href = './delete/delete_bahan.php?id=' + userId;
    }
}
</script>