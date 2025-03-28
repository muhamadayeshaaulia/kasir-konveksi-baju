const sideMenu = document.querySelector('aside');
const menuBtn = document.getElementById('menu-btn');
const closeBtn = document.getElementById('close-btn');
const darkMode = document.querySelector('.dark-mode');


menuBtn.addEventListener('click', () => {
    sideMenu.style.display = 'block';
});

closeBtn.addEventListener('click', () => {
    sideMenu.style.display = 'none';
});

darkMode.addEventListener('click', () => {
    document.body.classList.toggle('dark-mode-variables');
    darkMode.querySelector('span:nth-child(1)').classList.toggle('active');
    darkMode.querySelector('span:nth-child(2)').classList.toggle('active');

    const isDarkMode = document.body.classList.contains('dark-mode-variables');
    localStorage.setItem('darkMode', isDarkMode);
});

window.addEventListener('load', () => {
    const savedDarkMode = localStorage.getItem('darkMode') === 'true';
    if (savedDarkMode) {
        document.body.classList.add('dark-mode-variables');
        darkMode.querySelector('span:nth-child(1)').classList.add('active');
        darkMode.querySelector('span:nth-child(2)').classList.remove('active');
    } else {
        document.body.classList.remove('dark-mode-variables');
        darkMode.querySelector('span:nth-child(1)').classList.remove('active');
        darkMode.querySelector('span:nth-child(2)').classList.add('active');
    }
});

const Orders = [
    { productName: 'Product 1', productNumber: '12345', paymentStatus: 'Paid', status: 'Delivered' },
    { productName: 'Product 2', productNumber: '67890', paymentStatus: 'Due', status: 'Pending' },
    { productName: 'Product 3', productNumber: '11223', paymentStatus: 'Declined', status: 'Declined' }
];

Orders.forEach(order => {
    const tr = document.createElement('tr');
    const trContent = `
        <td data-label="Customer :">${order.productName}</td>
        <td data-label="Order ID :">${order.productNumber}</td>
        <td data-label="Order :">${order.paymentStatus}</td>
        <td class="${order.status === 'Declined' ? 'danger' : order.status === 'Pending' ? 'warning' : 'primary'}"data-label="Status :">${order.status}</td>
        <td class="primary">Details</td>
    `;
    tr.innerHTML = trContent;
    document.querySelector('.order').appendChild(tr);
});
