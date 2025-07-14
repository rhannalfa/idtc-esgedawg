// public/js/script.js

// Ini adalah file JavaScript Anda.
// Anda bisa menambahkan kode JavaScript di sini untuk interaktivitas di sisi klien.

document.addEventListener('DOMContentLoaded', function() {
    console.log('JavaScript aplikasi ITDC Native sudah dimuat!');

    // Contoh sederhana: Menambahkan event listener ke tombol
    const myButton = document.getElementById('myButton'); // Asumsi ada tombol dengan ID 'myButton'
    if (myButton) {
        myButton.addEventListener('click', function() {
            alert('Tombol diklik!');
        });
    }

    // Contoh lain: Validasi form dasar sebelum submit
    const userForm = document.querySelector('form');
    if (userForm) {
        userForm.addEventListener('submit', function(event) {
            const nameInput = document.getElementById('name');
            const emailInput = document.getElementById('email');

            if (nameInput && nameInput.value.trim() === '') {
                alert('Nama tidak boleh kosong!');
                event.preventDefault(); // Mencegah form disubmit
            }

            if (emailInput && !emailInput.value.includes('@')) {
                alert('Email tidak valid!');
                event.preventDefault();
            }
        });
    }
});