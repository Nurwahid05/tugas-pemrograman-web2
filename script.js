let totalHargaMakanan = 0;
let cart = [];

if (!localStorage.getItem('foodData')) {
    const initialData = [
        { name: `Rawon`, stok: 20, harga: 20000, image: './assets/images/rawon.jpg' },
        { name: `Soto Betawi`, stok: 20, harga: 25000, image: './assets/images/sotobetawi.jpg' },
        { name: `Krupuk`, stok: 100, harga: 2000, image: './assets/images/kerupuk_putih.jpg' },
        { name: `Telur Asin`, stok: 40, harga: 5000, image: './assets/images/telor_asin.jpg' },
        { name: `Es Teh Manis`, stok: 150, harga: 20000, image: './assets/images/es_teh_manis.jpg' },
        { name: `Nasi Padang`, stok: 30, harga: 12000, image: 'nasi-padang.webp' },
        { name: `Mie Sop`, stok: 45, harga: 17000, image: 'miso.webp' },
        { name: `Mie Goreng`, stok: 35, harga: 18000, image: 'mie_goreng.webp' },
        { name: `Ayam Geprek`, stok: 35, harga: 25000, image: 'kulit-ayam-crispy-geprek.jpg' },
    ];
    localStorage.setItem('foodData', JSON.stringify(initialData));
}

let food = JSON.parse(localStorage.getItem('foodData'));

function checkAvailable() {
    for (let i = 0; i < cart.length; i++) {
        let menu = food.find(f => f.name === cart[i].name);
        if (menu.stok < cart[i].jumlah) {
            alert(`Stok ${menu.name} tinggal ${menu.stok}`);
            return false;
        }
    }
    return true;
}

function confirmOrder() {
    const confirmation = confirm("Apakah Anda yakin ingin memesan makanan ini?");
    return confirmation;
}

function orderFood() {
    if (!confirmOrder()) {
        return; // batal proses order jika konfirmasi dibatalkan
    }

    if (checkAvailable()) {
        for (let item of cart) {
            let menu = food.find(f => f.name === item.name);
            menu.stok -= item.jumlah;
        }

        localStorage.setItem('foodData', JSON.stringify(food));
        localStorage.setItem('pembelian', JSON.stringify(cart));
        localStorage.setItem('total', totalHargaMakanan);

        window.location.href = 'beli.php';
    }
}

function addtoCart(index) {
    let menu = food[index];
    if (menu.stok <= 0) {
        alert(`${menu.name} habis, silahkan pesan menu lainnya`);
        return;
    }

    let item = cart.find(c => c.name === menu.name);
    if (item) {
        if (menu.stok - item.jumlah <= 0) {
            alert(`${menu.name} habis, silahkan pesan menu lainnya`);
            return;
        }
        item.jumlah++;
    } else {
        cart.push({ name: menu.name, harga: menu.harga, jumlah: 1, image: menu.image });
    }

    totalHargaMakanan += menu.harga;
    generateData();
    document.getElementById('cartList').style.display = 'inline-block';
}

function removeFood(index) {
    let item = cart[index];
    totalHargaMakanan -= item.harga;
    item.jumlah--;

    if (item.jumlah === 0) {
        cart.splice(index, 1);
    }

    generateData();
    document.getElementById('cartList').style.display = cart.length ? 'inline-block' : 'none';
}

function toRupiah(harga) {
    return harga.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function resetStok() {
    if (confirm('Yakin ingin mereset stok ke default?')) {
        localStorage.removeItem('foodData');
        location.reload();
    }
}

function generateData() {
    const foodList = document.getElementById('foodList');
    const cartList = document.getElementById('cartList');
    foodList.innerHTML = '';
    cartList.innerHTML = '';

    food.forEach((m, i) => {
        const card = document.createElement('div');
        card.className = 'card';
        card.innerHTML = `
            <img src="${m.image}" />
            <p>${m.name}</p>
            <div class="action">
                <span>Rp ${toRupiah(m.harga)},00 | Stok: ${m.stok}</span>
                <button onclick="addtoCart(${i})"><i class="fas fa-cart-plus"></i> Pesan</button>
            </div>`;
        foodList.appendChild(card);
    });

    const totalDiv = document.createElement('div');
    totalDiv.className = 'total';
    totalDiv.innerHTML = `<h1>TOTAL: Rp${toRupiah(totalHargaMakanan)},00</h1><hr>`;
    cartList.appendChild(totalDiv);

    cart.forEach((c, i) => {
        const card = document.createElement('div');
        card.className = 'card-order';
        card.innerHTML = `
            <div class="detail">
                <img src="${c.image}" />
                <p>${c.name}</p>
                <span>${c.jumlah}</span>
            </div>
            <button onclick="removeFood(${i})"><i class="fas fa-trash"></i> Hapus</button>`;
        cartList.appendChild(card);
    });

    if (cart.length) {
        const btn = document.createElement('div');
        btn.className = 'card-finish';
        btn.innerHTML = `<button onclick="orderFood()">ORDER SEKARANG</button>`;
        cartList.appendChild(btn);
    }

    //const resetBtn = document.createElement('div');
    //resetBtn.className = 'admin-reset';
    //resetBtn.innerHTML = `<button onclick="resetStok()" style="margin-top:20px; background:#dc3545; color:white;">Reset Stok</button>`;
    //cartList.appendChild(resetBtn);
}

generateData();
