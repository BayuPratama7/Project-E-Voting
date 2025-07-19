const express = require('express');
const router = express.Router();
const { checkAuth } = require('../middleware/auth');

// --- DATABASE SEMENTARA (Ganti dengan koneksi database asli nanti) ---
const MOCK_VOTERS = [
    { id: '12345', name: 'Budi' },
    { id: '67890', name: 'Ani' },
];

const MOCK_CANDIDATES = [
    { id: 'C1', name: 'Calon A', vision: 'Visi Misi Calon A' },
    { id: 'C2', name: 'Calon B', vision: 'Visi Misi Calon B' },
];

const VOTES = {}; // Objek untuk menyimpan suara, format: { voterId: candidateId }
// --------------------------------------------------------------------


// [POST] /api/login
// Endpoint untuk memproses login pemilih
router.post('/login', (req, res) => {
    const { voterId } = req.body;

    // Cek apakah pemilih sudah pernah memilih
    if (VOTES[voterId]) {
        return res.status(403).json({ message: 'Anda sudah memberikan suara.' });
    }

    const user = MOCK_VOTERS.find(v => v.id === voterId);

    if (user) {
        // Jika pemilih ditemukan, buat sesi
        req.session.user = { voterId: user.id, name: user.name };
        console.log(`Sesi dibuat untuk: ${user.id}`);
        res.status(200).json({ message: 'Login berhasil' });
    } else {
        res.status(401).json({ message: 'ID Pemilih tidak valid.' });
    }
});

// [GET] /api/session-data
// Endpoint terproteksi untuk mendapatkan data sesi dan kandidat
router.get('/session-data', checkAuth, (req, res) => {
    // Middleware checkAuth memastikan hanya user yang sudah login bisa akses ini
    res.json({
        user: req.session.user,
        candidates: MOCK_CANDIDATES
    });
});

// [POST] /api/vote
// Endpoint terproteksi untuk mengirimkan suara
router.post('/vote', checkAuth, (req, res) => {
    const { candidateId } = req.body;
    const voterId = req.session.user.voterId;

    // Cek sekali lagi untuk memastikan pemilih belum memilih
    if (VOTES[voterId]) {
        return res.status(403).json({ message: 'Anda sudah memberikan suara.' });
    }

    // Simpan suara
    VOTES[voterId] = candidateId;
    console.log(`Suara diterima: Pemilih ${voterId} memilih ${candidateId}`);

    // Hancurkan sesi setelah memilih agar tidak bisa vote lagi
    req.session.destroy(err => {
        if (err) {
            return res.status(500).json({ message: 'Gagal menghancurkan sesi.' });
        }
        res.status(200).json({ message: 'Terima kasih, suara Anda telah direkam.' });
    });
});


module.exports = router;
