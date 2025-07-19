$(document).ready(function() {
    $('#loginForm').on('submit', function(event) {
        event.preventDefault(); // Mencegah form dikirim secara tradisional
        
        const loginButton = $(this).find('button[type="submit"]');
        const errorMessageDiv = $('#error-message');

        $.ajax({
            url: config.login_url, // Menggunakan URL dari variabel global
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            beforeSend: function() {
                loginButton.prop('disabled', true).text('Loading...');
                errorMessageDiv.text('');
            },
            success: function(response) {
                if (response.status) {
                    // Jika sukses, redirect ke dashboard
                    window.location.href = config.dashboard_url; // Menggunakan URL dari variabel global
                } else {
                    errorMessageDiv.text(response.message);
                }
            },
            error: function() {
                errorMessageDiv.text('Tidak dapat terhubung ke server.');
            },
            complete: function() {
                loginButton.prop('disabled', false).text('Login');
            }
        });
    });
});
