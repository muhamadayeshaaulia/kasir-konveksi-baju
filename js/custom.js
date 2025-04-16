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
        const namaproduk = row.cells[1].textContent.toLowerCase();
        
        if (namaproduk.includes(searchValue)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

function confirmDelete(id_cstm) {
    if (confirm("Apakah Anda yakin ingin menghapus produk ini?")) {
        window.location.href = './delete/delete_custom.php?id=' + id_cstm;
    }
}
document.addEventListener('DOMContentLoaded', function() {
    const dropdownBtn = document.querySelector('.dropbtn');
    const dropdownContent = document.querySelector('.dropdown-content');
    
    if (dropdownBtn && dropdownContent) {
        dropdownBtn.addEventListener('click', function() {
            if (dropdownContent.style.display === 'block') {
                dropdownContent.style.display = 'none';
            } else {
                dropdownContent.style.display = 'block';
            }
        });
        document.addEventListener('click', function(event) {
            if (!event.target.matches('.dropbtn') && !event.target.closest('.dropdown-content')) {
                dropdownContent.style.display = 'none';
            }
        });
    }
    function checkScreenSize() {
        const desktopView = document.querySelector('.desktop-view');
        const dropdown = document.querySelector('.dropdown');
        
        if (window.innerWidth <= 768) {
            if (desktopView) desktopView.style.display = 'none';
            if (dropdown) dropdown.style.display = 'inline-block';
        } else {
            if (desktopView) desktopView.style.display = 'flex';
            if (dropdown) dropdown.style.display = 'none';
        }
    }
    checkScreenSize();
    window.addEventListener('resize', checkScreenSize);
});

setTimeout(function(){
    document.getElementById('notification').style.display = 'none';
}, 3000);