const express = require('express');
const session = require('express-session');
const path = require('path');
const apiRoutes = require('./routes/api');

const app = express();
const PORT = 3000;

// Middleware untuk parsing JSON body
app.use(express.json());

// Konfigurasi Session
// 'secret' adalah kunci untuk mengenkripsi cookie sesi. Ganti dengan string acak yang kuat.
app.use(session({
    secret: 'ini-adalah-secret-key-yang-sangat-rahasia',
    resave: false, // Jangan simpan sesi jika tidak ada perubahan
    saveUninitialized: false, // Jangan buat sesi sampai ada sesuatu yang disimpan
    cookie: {
        secure: false, // Set ke `true` jika menggunakan HTTPS
        httpOnly: true, // Mencegah akses cookie dari JavaScript client-side
        maxAge: 1000 * 60 * 15 // Sesi berlaku selama 15 menit
    }
}));

// Menyajikan file statis dari folder 'client'
// Ini membuat file seperti index.html, dashboard.html, dan file JS/CSS bisa diakses dari browser.
const clientPath = path.join(__dirname, '../client');
app.use(express.static(clientPath));

// Gunakan rute API yang telah kita definisikan
app.use('/api', apiRoutes);

// Route fallback untuk halaman utama (login)
app.get('/', (req, res) => {
    res.sendFile(path.join(clientPath, 'index.html'));
});

// Route untuk halaman dashboard, akan diproteksi di dalam api.js
app.get('/dashboard.html', (req, res) => {
    // Middleware di api.js akan mengecek sesi sebelum file ini disajikan
    // Namun, kita tetap perlu rute ini agar express tahu file mana yang harus dicari.
    // Proteksi sebenarnya terjadi di client-side JS yang memanggil /api/session-data
    if (!req.session.user) {
        return res.redirect('/');
    }
    res.sendFile(path.join(clientPath, 'dashboard.html'));
});

app.listen(PORT, () => {
    console.log(`Server berjalan di http://localhost:${PORT}`);
});
