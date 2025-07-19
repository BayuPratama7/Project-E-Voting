// Middleware untuk mengecek apakah pengguna sudah login
function checkAuth(req, res, next) {
    if (req.session && req.session.user) {
        // Jika ada sesi dan data user, lanjutkan ke request berikutnya
        return next();
    } else {
        // Jika tidak, kirim status 401 Unauthorized
        return res.status(401).json({ message: 'Akses ditolak. Silakan login terlebih dahulu.' });
    }
}

module.exports = { checkAuth };
