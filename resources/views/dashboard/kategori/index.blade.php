<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Kategori Produk') }}
        </h2>
        <!-- DataTables CSS -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
        <!-- SweetAlert2 CSS (optional, but good) -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: '{{ session('success') }}',
                        timer: 3000,
                        showConfirmButton: false
                    });
                </script>
            @endif

            @if(session('error'))
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: '{{ session('error') }}',
                    });
                </script>
            @endif

            @if ($errors->any())
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Ada Kesalahan',
                        html: `
                            <ul class="text-left list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        `,
                    });
                </script>
            @endif

            <div class="mb-4 flex justify-end">
                <button type="button" onclick="openCreateAlert()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    + Tambah Kategori
                </button>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    <table id="categoryTable" class="w-full whitespace-no-wrap display">
                        <thead>
                            <tr class="text-left font-bold border-b border-gray-200">
                                <th class="px-6 py-3">ID</th>
                                <th class="px-6 py-3">Nama Kategori</th>
                                <th class="px-6 py-3">Slug</th>
                                <th class="px-6 py-3">Jumlah Produk</th>
                                <th class="px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $kategori)
                                <tr class="border-b border-gray-200 hover:bg-gray-100">
                                    <td class="px-6 py-4">{{ $kategori->id }}</td>
                                    <td class="px-6 py-4">{{ $kategori->name }}</td>
                                    <td class="px-6 py-4">{{ $kategori->slug }}</td>
                                    <td class="px-6 py-4">{{ $kategori->products_count ?? 0 }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center space-x-4">
                                            <button type="button" onclick="openEditAlert({{ $kategori->id }}, '{{ addslashes($kategori->name) }}')" class="text-blue-500 hover:text-blue-700 font-semibold">Edit</button>
                                            <button type="button" onclick="confirmDelete({{ $kategori->id }})" class="text-red-500 hover:text-red-700 font-semibold">Hapus</button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada data kategori.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden Forms for SweetAlert2 Submission -->
    <form id="createForm" action="{{ route('dashboard.kategori.store') }}" method="POST" class="hidden">
        @csrf
        <input type="hidden" name="name" id="create_name">
    </form>

    <form id="editForm" method="POST" class="hidden">
        @csrf
        @method('PUT')
        <input type="hidden" name="name" id="edit_name">
    </form>
    
    <form id="deleteForm" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <script>
        //input validation for add category
        document.getElementById('add-category-from').addEventListener('submit', function(event) {
            const nameInput = document.getElementById('create_name');
            if (nameInput.value.trim() === '') {
                event.preventDefault();
                alert('Nama kategori tidak boleh kosong!');
            }

            if(nameInput.value.trim().length > 255){
                event.preventDefault();
                alert('Nama kategori maksimal 255 karakter!');
            }

            if(nameInput.value.trim().length < 3){
                event.preventDefault();
                alert('Nama kategori minimal 3 karakter!');
            }
        });

    </script>
    <!-- jQuery and DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        const baseUrl = "{{ url('dasboard/kategori') }}";

        function openCreateAlert() {
            Swal.fire({
                title: 'Tambah Kategori',
                input: 'text',
                inputPlaceholder: 'Masukkan nama kategori',
                showCancelButton: true,
                confirmButtonText: 'Simpan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#6b7280',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Nama kategori tidak boleh kosong!'
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('create_name').value = result.value;
                    document.getElementById('createForm').submit();
                }
            });
        }

        function openEditAlert(id, name) {
            Swal.fire({
                title: 'Edit Kategori',
                input: 'text',
                inputValue: name,
                inputPlaceholder: 'Masukkan nama kategori',
                showCancelButton: true,
                confirmButtonText: 'Update',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#6b7280',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Nama kategori tidak boleh kosong!'
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('edit_name').value = result.value;
                    document.getElementById('editForm').action = baseUrl + '/' + id;
                    document.getElementById('editForm').submit();
                }
            });
        }

        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Kategori yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteForm');
                    form.action = baseUrl + '/' + id;
                    form.submit();
                }
            })
        }

        $(document).ready(function() {
            $('#categoryTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
                }
            });
        });
    </script>
</x-app-layout>
