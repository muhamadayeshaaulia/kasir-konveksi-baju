function togglePengirimanFields() {
    var status = document.getElementById('status_pengiriman').value;
    var alamat = document.getElementById('alamat');
    var emailField = document.getElementById('email');
    var noHpField = document.getElementById('nohp');
    
    if (status === 'kirim') {
        document.getElementById('pengiriman_fields').style.display = 'block';
        emailField.required = true;
        noHpField.required = true;
        generateResiNumber();
    } else {
        document.getElementById('pengiriman_fields').style.display = 'none';
        emailField.required = false;
        noHpField.required = false;
    }
}
function toggleBayar() {
    var status = document.getElementById('pembayaran').value;
    var buktiInput = document.getElementById('bukti_transaksi');
    var buktiLabel = document.querySelector('label[for="bukti_transaksi"]');
    
    if (status === 'transfer' || status === 'qris') {

        buktiInput.required = true;
        buktiInput.style.display = 'block';
        buktiLabel.style.display = 'block';
    } else {

        buktiInput.required = false;
        buktiInput.style.display = 'none';
        buktiLabel.style.display = 'none';
    }
}
        function getProduk() {
            var kategori_id = document.getElementById('kategori').value;
            if (kategori_id) {
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'get_produk.php?kategori_id=' + kategori_id, true);
                xhr.onload = function() {
                    if (this.status == 200) {
                        document.getElementById('produk').innerHTML = this.responseText;
                        document.getElementById('harga').value = '';
                        document.getElementById('stok').innerHTML = '';
                        calculateTotal();
                    }
                }
                xhr.send();
            }
            
            var kategori_text = document.getElementById('kategori').options[document.getElementById('kategori').selectedIndex].text.toLowerCase();
            if (kategori_text.includes('baju') || kategori_text.includes('jaket') || kategori_text.includes('topi')) {
                document.getElementById('uk_baju_group').style.display = 'flex';
                document.getElementById('uk_celana_group').style.display = 'none';
            } else if (kategori_text.includes('celana') || kategori_text.includes('rook')) {
                document.getElementById('uk_baju_group').style.display = 'none';
                document.getElementById('uk_celana_group').style.display = 'flex';
            } else {
                document.getElementById('uk_baju_group').style.display = 'none';
                document.getElementById('uk_celana_group').style.display = 'none';
            }
        }
        
        function getHarga() {
            var produk_id = document.getElementById('produk').value;
            if (produk_id) {
                var xhr = new XMLHttpRequest();
                xhr.open('GET', './transaksi/get_harga.php?produk_id=' + produk_id, true);
                xhr.onload = function() {
                    if (this.status == 200) {
                        var data = JSON.parse(this.responseText);
                        document.getElementById('harga').value = data.harga;
                        document.getElementById('stok').innerHTML = 'Stok tersedia: ' + data.stok;
                        calculateTotal();
                    }
                }
                xhr.send();
            }
        }
        
        function calculateTotal() {
            var harga = parseFloat(document.getElementById('harga').value) || 0;
            var jumlah = parseInt(document.getElementById('jumlah').value) || 0;
            var subtotal = harga * jumlah;
            var tax = subtotal * 0.12;
            var diskon = 0;
        
            var nama_customer = document.getElementById('nama_customer').value;
        
            if (nama_customer) {
                var xhr = new XMLHttpRequest();
                xhr.open('GET', './transaksi/cek_diskon.php?nama_customer=' + encodeURIComponent(nama_customer), false);
                xhr.send();
        
                if (xhr.status == 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.diskon && subtotal >= 2000000) {
                        diskon = subtotal * 0.2;
                        document.getElementById('diskon_info').innerHTML = 'Diskon 20% untuk pelanggan setia!';
                    } else if (response.diskon && subtotal < 2000000) {
                        document.getElementById('diskon_info').innerHTML = 'Diskon hanya berlaku untuk pelanggan setia dalam pembelian minimal 2 juta.';
                    } else {
                        document.getElementById('diskon_info').innerHTML = '';
                    }
                }
            }
        
            var total = subtotal + tax - diskon;
        
            document.getElementById('subtotal').value = subtotal.toFixed(2);
            document.getElementById('tax').value = tax.toFixed(2);
            document.getElementById('diskon').value = diskon.toFixed(2);
            document.getElementById('total').value = total.toFixed(2);
        
            if (document.getElementById('metode_pembayaran').value === 'dp') {
                hitungDP();
            }
        }
        
        
        function togglePembayaran() {
            var metode = document.getElementById('metode_pembayaran').value;
            if (metode === 'dp') {
                document.getElementById('dp_fields').style.display = 'block';
                hitungDP();
            } else {
                document.getElementById('dp_fields').style.display = 'none';
            }
        }
        
        function hitungDP() {
            var total = parseFloat(document.getElementById('total').value) || 0;
            var dp = total * 0.5;
            var sisa = total - dp;
            
            document.getElementById('dp_amount').value = dp.toFixed(2);
            document.getElementById('remaining_amount').value = sisa.toFixed(2);
        }

