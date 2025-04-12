document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("liveSearch");
    const tableRows = document.querySelectorAll("#userTableBody tr");

    searchInput.addEventListener("keyup", function () {
        const query = searchInput.value.toLowerCase();

        tableRows.forEach(function (row) {
            const kode = row.children[0].textContent.toLowerCase();
            if (kode.includes(query)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });
});

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
function openModal(src) {
    document.getElementById("modalImage").src = src;
    document.getElementById("imageModal").style.display = "flex";
  }
  
  function closeModal() {
    document.getElementById("imageModal").style.display = "none";
  }