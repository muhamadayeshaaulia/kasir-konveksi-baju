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