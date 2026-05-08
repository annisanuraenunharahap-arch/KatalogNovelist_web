// Konfirmasi sebelum menghapus data
function konfirmasiHapus() {
    return confirm("Apakah Anda yakin ingin menghapus data buku ini?");
}

// Validasi Form 
function validateForm() {
    const judul = document.getElementById('judul').value;
    const pengarang = document.getElementById('pengarang').value;
    const tahun = document.getElementById('tahun_terbit').value;
    const foto = document.getElementById('foto');
    
    // Validasi field kosong
    if (judul === "" || pengarang === "" || tahun === "") {
        alert("Semua kolom teks wajib diisi!");
        return false;
    }

    // Validasi File Foto 
    if (foto.files.length > 0) {
        const file = foto.files[0];
        const fileSize = file.size / 1024 / 1024; // Convert ke MB
        const allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;

        if (!allowedExtensions.exec(file.name)) {
            alert("Tipe file tidak valid! Gunakan JPG, JPEG, atau PNG.");
            return false;
        }

        if (fileSize > 2) {
            alert("Ukuran file maksimal 2 MB!");
            return false;
        }
    }

    return true;
}