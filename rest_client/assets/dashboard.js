$(document).ready(function() {
    console.log('Dashboard script loaded.');

    // URL API di rest_server Anda untuk mengambil data kandidat
    // Ganti 'api/kandidat' dengan endpoint Anda yang sebenarnya
    const apiUrl = 'https://votinghimsi.infinityfreeapp.com/rest_server/api/kandidat';

    // Menggunakan AJAX untuk mengambil data dari server
    $.ajax({
        url: apiUrl,
        method: 'GET',
        // Jika Anda menggunakan API Key, tambahkan di headers
        // headers: { 'X-API-KEY': 'kunci_api_anda' },
        success: function(response) {
            console.log('Data diterima:', response);
            // Logika untuk menampilkan data ke div #kandidat-container
            $('#kandidat-container').html('<p>Data berhasil dimuat!</p>');
        },
        error: function(xhr, status, error) {
            console.error('Gagal mengambil data:', error);
            $('#kandidat-container').html('<p>Gagal memuat data dari server.</p>');
        }
    });
});
