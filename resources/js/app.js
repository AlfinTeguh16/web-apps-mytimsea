import './bootstrap';
import 'jodit/build/jodit.min.css'; // Import CSS
import { Jodit } from 'jodit'; // Import Jodit JS

// Inisialisasi editor
document.addEventListener("DOMContentLoaded", function () {
    const editors = document.querySelectorAll('.jodit-editor'); // Seleksi elemen dengan class tertentu
    editors.forEach(editor => {
        new Jodit(editor, {
            // Konfigurasi tambahan jika dibutuhkan
            height: 400,
            uploader: {
                insertImageAsBase64URI: true // Menyisipkan gambar sebagai Base64
            }
        });
    });
});
