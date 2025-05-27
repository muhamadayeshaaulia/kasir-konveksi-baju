<?php
include './app/koneksi.php';
$username = $_SESSION['username'];

$query = "SELECT image FROM user WHERE username = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($image);
$stmt->fetch();
$stmt->close();

$profilePhoto = !empty($image) ? './uploads/user/' . $image : 'img/default.jpg';

$transaksiQuery = "SELECT kode_transaksi, tanggal_transaksi FROM transaksi ORDER BY kode_transaksi DESC LIMIT 2";
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
</style>
<div class="right-section">
            <div class="nav">
                <button id="menu-btn">
                    <span class="material-icons-sharp">
                        menu
                    </span>
                </button>
                <div class="dark-mode">
                    <span class="material-icons-sharp active">
                        light_mode
                    </span>
                    <span class="material-icons-sharp ">
                        dark_mode
                    </span>
                </div>

                <div class="profile">
                    <div class="info">
                    <p>Hey, <b><?php echo htmlspecialchars($_SESSION['username']); ?></b></p>
                    <small class="text-muted"><?php echo htmlspecialchars($level); ?></small>
                    </div>
                    <div class="profile-photo">
                        <img src="<?php echo htmlspecialchars($profilePhoto) . '?v=' . time(); ?>" alt="Foto Profil" onclick="openProfileModal(this.src + '?v=' + new Date().getTime())" style="cursor:pointer;">
                    </div>
                </div>

            </div>

            <div class="user-profile">
                <div class="logo">
                    <img src="./img/erasebg-transformed.png">
                    <h2>Toko |<span class="danger"> Yesha</span></h2>
                    <p>Toko Konveksi Baju Ternama</p>
                </div>
            </div>

            <div class="reminders">
                <div class="header">
                    <h2>New Transaction</h2>
                    <span class="material-icons-sharp">
                        notifications_none
                    </span>
                </div>
                <?php 
                $isFirst = true;
                while($row = mysqli_fetch_assoc($result)): 
                ?>
                <div class="notification <?php echo !$isFirst ? 'deactive' : ''; ?>">
                    <div class="icon">
                        <span class="material-icons-sharp">
                            receipt_long
                        </span>
                    </div>
                    <div class="content">
                        <div class="info">
                            <h3>Kode: <?php echo $row['kode_transaksi']; ?></h3>
                            <small class="text_muted">
                                <?php echo date("d M Y", strtotime($row['tanggal_transaksi'])); ?>
                            </small>
                        </div>
                        <span class="material-icons-sharp">
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
                        <span class="material-icons-sharp">
                        print
                        </span>
                        <h3>Kuitansi</h3>
                    </div>
                    </a>
                </div>
            </div>
<div id="profileModal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.6); justify-content:center; align-items:center; z-index:1000;">
    <div style="position:relative; background:white; padding:20px; border-radius:10px; box-shadow:0 0 10px rgba(0,0,0,0.3); max-width:300px;">
    <span id="closeBtn" onclick="closeProfileModal()" style="position:absolute; top:5px; right:10px; cursor:pointer; font-size:20px;">&times;</span>
        <img id="modalProfileImg" src="" alt="Foto Profil Besar" style="width:100%; border-radius:10px;">
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
</script>

