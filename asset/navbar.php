<?php
include './app/koneksi.php';

$username = $_SESSION['username'] ?? 'Guest';
$level = $_SESSION['level'] ?? 'User';

$query = "SELECT image FROM user WHERE username = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($image);
$stmt->fetch();
$stmt->close();

$profilePhoto = !empty($image) ? './uploads/user/' . $image : 'img/default.jpg';

$transaksiQuery = "SELECT id_trx, kode_transaksi, tanggal_transaksi FROM transaksi ORDER BY kode_transaksi DESC LIMIT 2";
$result = mysqli_query($koneksi, $transaksiQuery);
?>

<style>
    #profileModal span:hover {
        color: darkred;
        transform: scale(1.2);
        transition: 0.2s ease;
    }
    #closeBtn {
        transition: color 0.2s ease;
    }
    #detailModal {
        display: none;
        position: fixed;
        top: 0; left: 0;
        width: 100vw; height: 100vh;
        background: rgba(0,0,0,0.6);
        justify-content: center;
        align-items: center;
        z-index: 1100;
        color: #6C9BCF;
    }
    #detailModal .modal-content {
        position: relative;
        background: white;
        padding: 20px;
        border-radius: 10px;
        max-width: 350px;
        width: 90%;
        box-shadow: 0 0 10px rgba(0,0,0,0.3);
    }
    #detailCloseBtn {
        position: absolute;
        top: 8px;
        right: 12px;
        cursor: pointer;
        font-size: 24px;
        user-select: none;
        transition: color 0.2s ease;
    }
    #detailCloseBtn:hover {
        color: darkred;
    }
    .notification .more-vert-btn {
        cursor: pointer;
        margin-left: 10px;
        user-select: none;
    }
</style>

<div class="right-section">
    <div class="nav">
        <button id="menu-btn">
            <span class="material-icons-sharp">menu</span>
        </button>
        <div class="dark-mode">
            <span class="material-icons-sharp active">light_mode</span>
            <span class="material-icons-sharp">dark_mode</span>
        </div>

        <div class="profile">
            <div class="info">
                <p>Hey, <b><?php echo htmlspecialchars($username); ?></b></p>
                <small class="text-muted"><?php echo htmlspecialchars($level); ?></small>
            </div>
            <div class="profile-photo">
                <img src="<?php echo htmlspecialchars($profilePhoto) . '?v=' . time(); ?>" alt="Foto Profil" onclick="openProfileModal(this.src + '?v=' + new Date().getTime())" style="cursor:pointer;">
            </div>
        </div>
    </div>

    <div class="user-profile">
        <div class="logo">
            <img src="./img/erasebg-transformed.png" alt="Logo Toko">
            <h2>Toko |<span class="danger"> Yesha</span></h2>
            <p>Toko Konveksi Baju Ternama</p>
        </div>
    </div>

    <div class="reminders">
        <div class="header">
            <h2>New Transaction</h2>
            <span class="material-icons-sharp">notifications_none</span>
        </div>

        <?php 
        $isFirst = true;
        while($row = mysqli_fetch_assoc($result)): 

            $kode = $row['kode_transaksi'];
            $pagePrint = 'print';

            if (strpos($kode, 'PC') === 0) {
                $pagePrint = 'pcustom';
            } elseif (strpos($kode, 'PR') === 0) {
                $pagePrint = 'print';
            } elseif (strpos($kode, 'PX') === 0) {
                $pagePrint = 'page3';
            } elseif (strpos($kode, 'PY') === 0) {
                $pagePrint = 'page4';
            }

            $tanggal = date("d M Y", strtotime($row['tanggal_transaksi']));
        ?>
        <div class="notification <?php echo !$isFirst ? 'deactive' : ''; ?>">
            <div class="icon">
                <span class="material-icons-sharp">receipt_long</span>
            </div>
            <div class="content">
                <div class="info">
                    <h3>Kode: <?php echo htmlspecialchars($kode); ?></h3>
                    <small class="text_muted"><?php echo $tanggal; ?></small>
                </div>

                <button onclick="window.location.href='index.php?page=<?= $pagePrint ?>&id=<?= $row['id_trx'] ?>'" 
                        style="background-color:#FFD700; padding:5px 10px; border:none; border-radius:3px; cursor:pointer; margin-left:10px;">
                    Print
                </button>

                <span class="material-icons-sharp more-vert-btn" 
                    data-kode="<?= htmlspecialchars($kode) ?>"
                    data-tanggal="<?= htmlspecialchars($tanggal) ?>"
                    data-id="<?= $row['id_trx'] ?>"
                    onclick="openDetailModal(this)">
                    more_vert
                </span>
            </div>
        </div>
        <?php 
        $isFirst = false;
        endwhile;
        ?>

        <div class="notification add-reminder">
            <a href="./index.php?page=lunas">
                <div>
                    <span class="material-icons-sharp">print</span>
                    <h3>Kuitansi</h3>
                </div>
            </a>
        </div>
    </div>
</div>
<div id="profileModal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.6); justify-content:center; align-items:center; z-index:1000;">
    <div style="position:relative; background:white; padding:20px; border-radius:10px; box-shadow:0 0 10px rgba(0,0,0,0.3); max-width:300px;">
        <span id="closeBtn" onclick="closeProfileModal()" style="position:absolute; top:5px; right:10px; cursor:pointer; font-size:20px;">&times;</span>
        <img id="modalProfileImg" src="" alt="Foto Profil Besar" style="width:100%; border-radius:10px;">
    </div>
</div>
<div id="detailModal">
    <div class="modal-content">
        <span id="detailCloseBtn">&times;</span>
        <h2>Detail Transaksi</h2>
        <p><strong>Kode Transaksi:</strong> <span id="modalKode"></span></p>
        <p><strong>Tanggal Transaksi:</strong> <span id="modalTanggal"></span></p>
    </div>
</div>

<script>
function openProfileModal(src) {
    document.getElementById('modalProfileImg').src = src;
    document.getElementById('profileModal').style.display = 'flex';
    document.getElementById('closeBtn').style.color = '';
}

function closeProfileModal() {
    const closeBtn = document.getElementById('closeBtn');
    closeBtn.style.color = 'red';
    setTimeout(() => {
        document.getElementById('profileModal').style.display = 'none';
        closeBtn.style.color = '';
    }, 200);
}

function openDetailModal(elem) {
    const kode = elem.getAttribute('data-kode');
    const tanggal = elem.getAttribute('data-tanggal');
    document.getElementById('modalKode').textContent = kode;
    document.getElementById('modalTanggal').textContent = tanggal;

    document.getElementById('detailModal').style.display = 'flex';
}

document.getElementById('detailCloseBtn').onclick = function() {
    document.getElementById('detailModal').style.display = 'none';
};
window.onclick = function(event) {
    const detailModal = document.getElementById('detailModal');
    if (event.target === detailModal) {
        detailModal.style.display = 'none';
    }

    const profileModal = document.getElementById('profileModal');
    if (event.target === profileModal) {
        profileModal.style.display = 'none';
    }
};
</script>