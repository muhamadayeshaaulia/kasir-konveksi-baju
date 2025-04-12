<?php
require './app/app.php';
include './app/koneksi.php';
$current_user_level = isset($_SESSION['level']) ? $_SESSION['level'] : '';
$stmt = $pdo->query('SELECT COUNT(*) as total_users FROM user');
$total_users = $stmt->fetch()['total_users'];

$query = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM transaksi");
$data = mysqli_fetch_assoc($query);

$sql = "SELECT SUM(stok) AS total_stok FROM produk";
$result = $koneksi->query($sql);

$total_stok = 0;
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_stok = $row["total_stok"];
}
?>
<h1>Dashboard</h1>
            <div class="analyse">
                <div class="sales">
                <div class="status">
                    <div class="info">
                        <h3>Total Transaksi</h3>
                        <h1><?php echo $data['total']; ?></h1>
                    </div>
                    <div class="progresss">
                        <svg>
                            <circle cx="38" cy="38" r="36"></circle>
                        </svg>
                        <div class="percentage">
                            <p>+81%</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="visits">
                <div class="status">
                    <div class="info">
                        <h3>Stok</h3>
                        <h1><?php echo number_format($total_stok, 0, ',', '.'); ?></h1>
                    </div>
                    <div class="progresss">
                        <svg>
                            <circle cx="38" cy="38" r="36"></circle>
                        </svg>
                        <div class="percentage">
                            <p>-2%</p>
                        </div>
                    </div>
                </div>
            </div>
                <div class="searches">
                    <div class="status">
                        <div class="info">
                            <h3>Pengguna</h3>
                            <h1><?php echo $total_users; ?></h1>
                        </div>
                        <div class="progresss">
                            <svg>
                                <circle cx="36" cy="36" r="36"></circle>
                            </svg>
                            <div class="percentage">
                                <p>100%</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Analyses -->

            <!-- New Users Section -->
<div class="new-users">
    <h2>New Users</h2>
    <div class="user-list">
        <?php
        $stmt_new_users = $pdo->query('SELECT user_id, username, level FROM user ORDER BY user_id DESC LIMIT 3');
        $new_users = $stmt_new_users->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($new_users as $user): 
        ?>
        <div class="user">
            <img src="./img/profile-<?= rand(2,4) ?>.jpg">
            <h2><?= htmlspecialchars($user['username']) ?></h2>
            <p>Roll: <?= htmlspecialchars($user['level'])?></p>
        </div>
        <?php endforeach; ?>
        
        <div class="user" onclick="window.location.href='index.php?page=tusers'" style="cursor:pointer;">
            <img src="./img/plus.png">
            <h2>More</h2>
            <p>New User</p>
        </div>
    </div>
</div>
<?php
require './app/koneksi.php';

$delete_status = isset($_SESSION['delete_status']) ? $_SESSION['delete_status'] : '';
$delete_message = isset($_SESSION['delete_message']) ? $_SESSION['delete_message'] : '';

unset($_SESSION['delete_status']);
unset($_SESSION['delete_message']);
?>

<?php if ($delete_status): ?>
<div id="notification" style="position:fixed; top:20px; left:50%; transform:translateX(-50%); background-color:<?= $delete_status === 'success' ? '#4CAF50' : '#f44336' ?>; color:white; padding:15px; border-radius:5px; box-shadow:0 4px 8px rgba(0,0,0,0.1); z-index:1000; display:flex; justify-content:space-between; align-items:center; min-width:300px;">
    <span><?= htmlspecialchars($delete_message) ?></span>
    <button onclick="document.getElementById('notification').style.display='none'" style="background:none; border:none; color:white; font-weight:bold; cursor:pointer; margin-left:15px;">×</button>
</div>

<?php endif; ?>
<div class="recent-orders">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>Table User</h2>
        
        <div style="display: flex; align-items: center;">
            <input type="text" id="liveSearch" placeholder="Cari User..." 
                   style="padding: 8px 12px; width: 120px; border: 1px solid #ddd; border-radius: 4px; margin-right: 10px;">
            
            <button onclick="window.location.href='index.php?page=tusers'" 
                    style="background-color:#4CAF50; color:white; border:none; padding:8px 16px; border-radius:4px; cursor:pointer;">
                Tambah User
            </button>
        </div>
    </div>

    <div id="userTableContainer">
        <?php
        $sql = "SELECT user_id, username, email, level FROM user";
        $result = $koneksi->query($sql);
        $all_users = [];
        if ($result->num_rows > 0) {
            $all_users = $result->fetch_all(MYSQLI_ASSOC);
        }

        $show_all = isset($_GET['show_all']) && $_GET['show_all'] == '1';
        $users_to_display = $show_all ? $all_users : array_slice($all_users, 0, 3);
        $total_users = count($all_users);
        ?>
        
        <table style="width:100%; border-collapse:collapse; text-align:left;">
            <thead>
                <tr style="background-color:var(--color-background);">
                    <th style="padding:12px; border:1px solid #ddd;">ID</th>
                    <th style="padding:12px; border:1px solid #ddd;">USER NAME</th>
                    <th style="padding:12px; border:1px solid #ddd;">EMAIL</th>
                    <th style="padding:12px; border:1px solid #ddd;">ROLE</th>
                    <th style="padding:12px; border:1px solid #ddd;">AKSI</th>
                </tr>
            </thead>
            <tbody id="userTableBody">
                <?php if (!empty($users_to_display)): ?>
                    <?php foreach($users_to_display as $row): ?>
                        <tr>
                            <td style="padding:12px; border:1px solid #ddd;"><?= htmlspecialchars($row["user_id"]) ?></td>
                            <td style="padding:12px; border:1px solid #ddd;"><?= htmlspecialchars($row["username"]) ?></td>
                            <td style="padding:12px; border:1px solid #ddd;"><?= htmlspecialchars($row["email"]) ?></td>
                            <td style="padding:12px; border:1px solid #ddd;"><?= htmlspecialchars($row["level"]) ?></td>
                            <td style="padding:12px; border:1px solid #ddd;">
                                <button onclick="window.location.href='index.php?page=eusers&id=<?= $row["user_id"] ?>'" 
                                        style="background-color:#FFD700; padding:5px 10px; border:none; border-radius:3px; cursor:pointer; margin-right:5px;">
                                    Edit
                                </button>
                                <button onclick="<?= $current_user_level === 'Admin' ? "confirmDelete({$row['user_id']})" : "showAdminOnlyMessage()" ?>" 
                                        style="background-color:#f44336; color:white; padding:5px 10px; border:none; border-radius:3px; cursor:pointer;">
                                    Hapus
                                </button>
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
                <button onclick="window.location.href='?dashboard=<?= $_GET['dashboard'] ?? '' ?>&show_all=1'"
                        style="background-color: #6C9BCF; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer;">
                    Tampilkan Semua (<?= $total_users ?>)
                </button>
            <?php else: ?>
                <button onclick="window.location.href='?dashboard=<?= $_GET['dashboard'] ?? '' ?>&show_all=0'"
                        style="background-color: #f44336; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer;">
                    Tampilkan Sedikit
                </button>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<script src="./js/dashboardu.js"></script>