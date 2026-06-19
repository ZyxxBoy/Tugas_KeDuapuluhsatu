<!-- Memasukkan Cropper.js -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<style>
    /* Menyesuaikan area cropper di dalam modal SweetAlert */
    .swal2-html-container {
        overflow: hidden !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const imageInput = document.getElementById('image');
        let cropper;

        // --- FITUR CROP GAMBAR OTOMATIS SAAT MEMILIH FILE ---
        if (imageInput) {
            imageInput.addEventListener('change', function(e) {
                const files = e.target.files;
                if (files && files.length > 0) {
                    const file = files[0];
                    
                    // Validasi tipe file sebelum di-crop
                    const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                    if (!validTypes.includes(file.type)) {
                        Swal.fire('Error', 'Format gambar harus JPG, JPEG, PNG, atau GIF.', 'error');
                        imageInput.value = '';
                        return;
                    }

                    // Tampilkan gambar di modal SweetAlert untuk proses Crop
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        Swal.fire({
                            title: 'Sesuaikan Posisi Gambar',
                            html: `
                                <div style="max-height: 400px; width: 100%; display: flex; justify-content: center; overflow: hidden;">
                                    <img id="image-preview" src="${event.target.result}" style="max-width: 100%; display: block;">
                                </div>
                            `,
                            width: 600,
                            showCancelButton: true,
                            confirmButtonText: 'Crop & Simpan',
                            cancelButtonText: 'Batal',
                            allowOutsideClick: false, // Wajib konfirmasi
                            didOpen: () => {
                                const image = document.getElementById('image-preview');
                                // Inisialisasi Cropper.js
                                cropper = new Cropper(image, {
                                    aspectRatio: 1, // Mengunci rasio 1:1 (Bujur sangkar)
                                    viewMode: 2,    // Agar area crop tidak bisa keluar dari gambar
                                    autoCropArea: 1,
                                });
                            },
                            preConfirm: () => {
                                return new Promise((resolve) => {
                                    // Hasil crop gambar di-set ukurannya persis 800x800
                                    cropper.getCroppedCanvas({
                                        width: 800,
                                        height: 800,
                                    }).toBlob((blob) => {
                                        resolve(blob);
                                    }, 'image/jpeg', 0.85); // Kualitas gambar 85% untuk menghemat memori
                                });
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Ambil hasil blob crop dan jadikan objek File baru
                                const fileData = new File([result.value], "cropped_image.jpg", {
                                    type: "image/jpeg",
                                    lastModified: new Date().getTime()
                                });

                                // Memasukkan ulang file hasil crop ke dalam input="file"
                                // Agar ketika tombol submit form ditekan, file hasil crop ini yang terkirim ke Laravel
                                const container = new DataTransfer();
                                container.items.add(fileData);
                                imageInput.files = container.files;
                                
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: 'Gambar sudah disesuaikan dan siap di-upload.',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            } else {
                                // Jika dibatalkan, reset kolom input file
                                imageInput.value = '';
                            }
                            
                            // Bersihkan instance cropper
                            if (cropper) {
                                cropper.destroy();
                                cropper = null;
                            }
                        });
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        // --- VALIDASI SAAT TOMBOL SUBMIT DITEKAN ---
        if (form) {
            form.addEventListener('submit', function(e) {
                const name = document.getElementById('name') ? document.getElementById('name').value.trim() : '';
                const category = document.getElementById('product_category_id') ? document.getElementById('product_category_id').value : '';
                const price = document.getElementById('price') ? document.getElementById('price').value : '';
                const stock = document.getElementById('stock') ? document.getElementById('stock').value : '';
                
                let errors = [];

                if (document.getElementById('name') && !name) {
                    errors.push('Nama Produk harus diisi.');
                }
                
                if (document.getElementById('product_category_id') && !category) {
                    errors.push('Kategori Produk harus dipilih.');
                }
                
                if (document.getElementById('price') && (!price || price < 0)) {
                    errors.push('Harga harus diisi dan tidak boleh kurang dari 0.');
                }
                
                if (document.getElementById('stock') && (!stock || stock < 0)) {
                    errors.push('Stok harus diisi dan tidak boleh kurang dari 0.');
                }
                
                // Validasi Ulang Ukuran File (jika file hasil crop atau file lain lebih dari 2MB)
                if (imageInput && imageInput.files.length > 0) {
                    const file = imageInput.files[0];
                    const fileSizeMB = file.size / 1024 / 1024;
                    if (fileSizeMB > 2) {
                        errors.push('Ukuran gambar tidak boleh lebih dari 2MB.');
                    }
                }
                
                if (errors.length > 0) {
                    e.preventDefault(); // Mencegah form untuk disubmit
                    
                    let errorHtml = '<ul class="text-left list-disc list-inside">';
                    errors.forEach(function(error) {
                        errorHtml += `<li>${error}</li>`;
                    });
                    errorHtml += '</ul>';

                    Swal.fire({
                        icon: 'warning',
                        title: 'Validasi Gagal',
                        html: errorHtml,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Tutup'
                    });
                }
            });
        }
    });
</script>
