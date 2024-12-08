// Fungsi untuk mengubah format tanggal
function formatDate(date) {
    const options = { day: '2-digit', month: 'long', year: 'numeric' };
    return new Date(date).toLocaleDateString('en-GB', options); // Atau 'id-ID' untuk Indonesia
}

// Inisialisasi event listener
document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('end_date');
    const formattedDateInput = document.getElementById('formatted_date');

    if (dateInput && formattedDateInput) {
        dateInput.addEventListener('change', function() {
            const selectedDate = this.value;
            if (selectedDate) {
                formattedDateInput.value = formatDate(selectedDate);
            } else {
                formattedDateInput.value = ''; // Kosongkan jika tidak ada tanggal yang dipilih
            }
        });
    }
});
