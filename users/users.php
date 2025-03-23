<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kasir_konveksi";


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT user_id, username, email, level FROM user";
$result = $conn->query($sql);
?>

<div class="recent-orders">
    <h2>Table User</h2>
    
    <div style="margin-bottom: 10px;">
        <button onclick="showTambahUserModal()" style="margin-right: 5px; background-color: #4CAF50; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">Tambah User</button>
        <button onclick="showEditUserModal()" style="background-color: #FFD700; color: black; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">Edit User</button>
    </div>

    <table style="text-align:left">
        <thead>
            <tr>
                <th>ID</th>
                <th>User Name</th>
                <th>Email</th>
                <th>Roll</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td data-label='ID :'>" . $row["user_id"]. "</td>
                            <td data-label='User Name :'>" . $row["username"]. "</td>
                            <td data-label='Email :'>" . $row["email"]. "</td>
                            <td data-label='Roll :'>" . $row["level"]. "</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>Tidak ada data user</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <a href="#">Show All</a>
</div>

<div id="tambahUserModal" class="modal">
    <div class="question-mark">?</div>
    <p>Apakah Anda yakin ingin menambah user?</p>
    <button class="ok" onclick="redirectToTambahUser()">OK</button>
    <button class="cancel" onclick="hideTambahUserModal()">Cancel</button>
</div>

<div id="editUserModal" class="modal">
    <div class="question-mark">?</div>
    <p>Apakah Anda yakin ingin mengedit user?</p>
    <button class="ok" onclick="redirectToEditUser()">OK</button>
    <button class="cancel" onclick="hideEditUserModal()">Cancel</button>
</div>

<div id="overlay" class="overlay"></div>

<script>
    function showTambahUserModal() {
        document.getElementById("tambahUserModal").style.display = "block";
        document.getElementById("overlay").style.display = "block";
    }

    function hideTambahUserModal() {
        document.getElementById("tambahUserModal").style.display = "none";
        document.getElementById("overlay").style.display = "none";
    }

    function showEditUserModal() {
        document.getElementById("editUserModal").style.display = "block";
        document.getElementById("overlay").style.display = "block";
    }

    function hideEditUserModal() {
        document.getElementById("editUserModal").style.display = "none";
        document.getElementById("overlay").style.display = "none";
    }

    function redirectToTambahUser() {
        window.location.href = "tambah_user.html";
    }

    function redirectToEditUser() {
        window.location.href = "edit_user.html";
    }
</script>