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
        <span>Hanya admin yang dapat menghapus user</span>
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
        window.location.href = './delete/delete_user.php?id=' + userId;
    }
}

setTimeout(function(){
    document.getElementById('notification').style.display = 'none';
}, 3000);

function openProfileModal(src, username, level) {
    document.getElementById('modalProfileImg').src = src;
    document.getElementById('modalUsername').textContent = username;
    document.getElementById('modalLevel').textContent = "Role: " + level;
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