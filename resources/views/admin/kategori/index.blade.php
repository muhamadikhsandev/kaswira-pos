@extends('layouts.app')

@section('title', 'Manajemen Kategori')

@section('content')
<header class="mb-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
  <div class="flex-1">
    <h1 class="flex items-center whitespace-nowrap text-3xl sm:text-4xl font-bold text-gray-800 dark:text-gray-100">
      Manajemen Kategori
      <span
        class="material-icons text-blue-600 ml-2 sm:ml-3"
        style="font-size: 28px; sm:font-size:36px;"
      >
        category
      </span>
    </h1>
    <p class="mt-2 text-base sm:text-lg text-gray-600 dark:text-gray-300 max-w-lg">
      Kelola kategori barang dengan mudah
    </p>
  </div>
</header>

  <section id="kategori" class="mb-10">
    <div class="bg-white dark:bg-gray-800 p-4 sm:p-6 rounded-lg shadow-lg">
      <div class="mb-4 flex flex-col sm:flex-row justify-between items-center space-y-2 sm:space-y-0 sm:space-x-4">
        <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100 mb-4 md:mb-0">
          Daftar Kategori
        </h2>
        {{-- Tombol "Tambah Kategori" yang akan membuka modal sudah diganti ke floating button di bawah --}}
      </div>

      {{-- Menghapus "card tambahan" dan langsung menempatkan tabel di dalam div utama --}}
      <div class="overflow-x-auto mt-4"> {{-- Mengganti mt-6 dengan mt-4 untuk konsistensi --}}
        <table id="kategoriTable" class="min-w-full bg-white dark:bg-gray-800 shadow rounded-lg border border-gray-200 dark:border-gray-700 text-xs sm:text-sm">
          <thead class="bg-blue-600 text-white sticky top-0 z-10">
            <tr>
              <th class="px-4 py-2 text-left">No</th>
              <th class="px-4 py-2 text-left">Nama Kategori</th>
              <th class="px-4 py-2 text-left">Tanggal Dibuat</th>
              <th class="px-4 py-2 text-left">Tanggal Di Update</th>
              <th class="px-4 py-2 text-left">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
            @foreach($categories as $index => $category)
              <tr data-id="{{ $category->id }}" class="hover:bg-gray-50 dark:hover:bg-gray-600 transition duration-150">
                <td class="px-4 py-2 text-gray-700 dark:text-gray-200">
                  {{ $index + 1 }}
                </td>
                <td class="px-4 py-2 text-gray-700 dark:text-gray-200 category-name">
                  {{ $category->name }}
                </td>
                <td class="px-4 py-2 text-gray-700 dark:text-gray-200">
                  {{ $category->created_at->format('Y-m-d') }}
                </td>
                <td class="px-4 py-2 text-gray-700 dark:text-gray-200">
                  {{ $category->updated_at->format('Y-m-d') }}
                </td>
                <td class="px-4 py-2">
                  <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4">
                    <a
                      href="#"
                      class="editCategoryButton block text-center text-blue-600 hover:text-blue-800 font-medium transition duration-150"
                      data-id="{{ $category->id }}"
                      data-name="{{ $category->name }}"
                    >
                      Edit
                    </a>
                    <a
                      href="#"
                      class="deleteCategoryButton block text-center text-red-600 hover:text-red-800 font-medium transition duration-150"
                      data-id="{{ $category->id }}"
                    >
                      Hapus
                    </a>
                  </div>
                </td>
              </tr>
            @endforeach
            </tbody>
        </table>
      </div>

      {{-- Floating action button untuk menambah kategori, disamakan dengan stokbarang.blade.php --}}
      <a href="javascript:void(0);" id="openFormButton" class="floating-button bg-blue-600 text-white rounded-full shadow-lg hover:bg-blue-700 transition">
        <span class="material-icons text-lg">add</span>
      </a>
    </div>

    {{-- Modal Tambah Kategori (Popup Form) --}}
    <div id="popupForm" class="fixed inset-0 flex justify-center items-center bg-black bg-opacity-50 modal-overlay modal-hidden">
      <div class="relative bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-lg shadow-xl w-full sm:w-11/12 md:w-1/2 lg:w-1/3 mx-auto transform scale-95 transition-all duration-300 ease-in-out">
        <h3 class="text-lg sm:text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4 text-center">Tambah Kategori Baru</h3>
        <form id="ajaxForm" method="POST" action="{{ route('admin.kategori.store') }}" class="space-y-4">
          @csrf
          <div>
            <label for="kategoriInput" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Kategori</label>
            <input type="text" id="kategoriInput" name="name" required
                   class="w-full p-3 border border-gray-200 dark:border-gray-700 rounded focus:outline-none focus:ring-2 focus:ring-blue-600 text-sm bg-white dark:bg-gray-700"
                   placeholder="Masukkan nama kategori" />
          </div>
          <div class="flex flex-col sm:flex-row justify-end gap-2 mt-4">
            <button type="button" id="closeFormButton"
                    class="bg-gray-200 text-gray-800 dark:bg-gray-600 dark:text-gray-200 py-2 px-4 rounded hover:bg-gray-300 transition text-sm">
              Batal
            </button>
            <button type="submit" id="simpanKategoriButton"
                    class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition text-sm">
              Simpan
            </button>
          </div>
        </form>
      </div>
    </div>
  </section>
@endsection


@push('scripts')
<script defer>
  document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const baseUrl = "{{ url('admin/dashboard/kategori') }}";
    const successAudio = new Audio('/sounds/success.mp3'); // MODIFIED: Path to your success sound in public/sounds/

    function playSuccessSound() {
      successAudio.currentTime = 0; // Rewind to the start
      successAudio.play().catch(error => console.error("Error playing sound:", error));
    }

    // Inisialisasi DataTable tanpa ordering agar urutan tetap sesuai dengan penambahan
    const table = $('#kategoriTable').DataTable({
      dom: '<"flex flex-col sm:flex-row items-center justify-between p-4"lf>rt<"flex flex-col sm:flex-row items-center justify-between p-4"ip>',
      ordering: false,
      language: {
        search: "",
        searchPlaceholder: "Cari kategori..."
      },
      pageLength: 5,
      lengthMenu: [ [5, 10, 25, 50, -1], [5, 10, 25, 50, "All"] ]
    });

    // Tambahkan icon search di DataTable filter
    const $dataTableFilter = $('.dataTables_filter');
    $dataTableFilter.find('input').addClass('pl-10');
    $dataTableFilter.prepend('<span class="material-icons text-blue-600">search</span>');

    function updateRowNumbers() {
      // Update nomor untuk semua baris berdasarkan urutan tampil di tabel
      table.column(0, { search: 'applied', order: 'applied' }).nodes().each(function(cell, i) {
        cell.innerHTML = i + 1;
      });
    }
    // Panggil updateRowNumbers() setelah inisialisasi agar baris awal tertata
    updateRowNumbers();


    // --- Modal Tambah Kategori ---
    $('#openFormButton').on('click', function () {
      $('#popupForm').removeClass('modal-hidden').addClass('modal-visible');
      $('#kategoriInput').focus(); // Fokus pada input saat modal dibuka
    });

    $('#closeFormButton').on('click', function () {
      $('#popupForm').removeClass('modal-visible').addClass('modal-hidden');
      $('#ajaxForm')[0].reset(); // Reset form saat ditutup
    });


    // --- AJAX Create Kategori ---
    $('#ajaxForm').submit(function(e) {
      e.preventDefault();
      const form = $(this);
      const kategoriName = $('#kategoriInput').val().trim();

      if (!kategoriName) {
        Swal.fire({
          icon: 'warning',
          title: 'Oops...',
          text: 'Nama kategori tidak boleh kosong!',
          confirmButtonColor: '#2563eb'
        });
        return;
      }

      $.ajax({
        url: form.attr('action'),
        method: form.attr('method'),
        headers: { 'Accept': 'application/json' },
        data: form.serialize(),
        success: function(response) {
          playSuccessSound(); // Play sound on success
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: response.success,
            timer: 2000,
            showConfirmButton: false
          });
          form[0].reset(); // Reset form
          $('#popupForm').removeClass('modal-visible').addClass('modal-hidden'); // Tutup modal

          // Gunakan tanggal dari response atau generate tanggal sekarang
          const currentDate = response.category.created_at
                              ? response.category.created_at.substr(0,10)
                              : new Date().toISOString().split('T')[0];

          // Tambahkan baris baru ke DataTable
          const newRowNode = table.row.add([
            '', // Kolom No; akan di-update melalui updateRowNumbers()
            response.category.name,
            currentDate,
            currentDate,
            '<div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4">' +
              '<a href="#" class="editCategoryButton block text-center text-blue-600 hover:text-blue-800 font-medium transition duration-150" data-id="'+ response.category.id +'" data-name="'+ response.category.name +'">Edit</a>' +
              '<a href="#" class="deleteCategoryButton block text-center text-red-600 hover:text-red-800 font-medium transition duration-150" data-id="'+ response.category.id +'">Hapus</a>' +
            '</div>'
          ]).draw(false).node();
          $(newRowNode).attr('data-id', response.category.id);
          $(newRowNode).find('td').eq(1).addClass('category-name');

          // Ubah halaman ke halaman terakhir agar baris baru tampil di bagian bawah
          var info = table.page.info();
          table.page(info.pages - 1).draw(false);

          // Update nomor baris setelah mengganti halaman
          updateRowNumbers();
        },
        error: function(xhr) {
          console.error("Create error: ", xhr.responseText);
          let errorMsg = 'Gagal menambahkan kategori!';
          if (xhr.responseJSON && xhr.responseJSON.error) {
            errorMsg = xhr.responseJSON.error;
          }
          Swal.fire({
            icon: 'warning',
            title: 'Oops...',
            text: errorMsg,
            confirmButtonColor: '#2563eb'
          });
        }
      });
    });

    // --- AJAX Edit Kategori ---
    $('#kategoriTable').on('click', '.editCategoryButton', function (e) {
      e.preventDefault();
      const $this = $(this);
      const id = $this.data('id');
      const currentName = $this.data('name');

      Swal.fire({
        title: 'Edit Kategori',
        input: 'text',
        inputValue: currentName,
        inputPlaceholder: 'Masukkan nama kategori...',
        showCancelButton: true,
        confirmButtonText: 'Update',
        cancelButtonText: 'Batal',
        customClass: {
          input: 'border border-blue-600 rounded focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent p-2',
          confirmButton: 'bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-md',
          cancelButton: 'bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded shadow-md'
        },
        inputValidator: (value) => {
          if (!value) {
            return 'Nama kategori tidak boleh kosong!';
          }
          return null;
        }
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: baseUrl + '/' + id,
            method: 'POST',
            headers: { 'Accept': 'application/json' },
            data: { name: result.value, _token: csrfToken, _method: 'PUT' },
            success: function (response) {
              playSuccessSound(); // Play sound on success
              Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: response.success,
                timer: 2000,
                showConfirmButton: false
              });
              const $row = $this.closest('tr');
              $row.find('.category-name').text(response.category.name);
              // Update tanggal diupdate jika ada di response, jika tidak, gunakan tanggal sekarang
              const updatedDate = response.category.updated_at
                                  ? response.category.updated_at.substr(0,10)
                                  : new Date().toISOString().split('T')[0];
              $row.find('td').eq(3).text(updatedDate); // Asumsi kolom ke-4 (index 3) adalah 'Tanggal Di Update'
              $this.attr('data-name', response.category.name);
            },
            error: function (xhr) {
              console.error("Update error: ", xhr.responseText);
              let errorMsg = 'Gagal memperbarui kategori!';
              if (xhr.responseJSON && xhr.responseJSON.error) {
                errorMsg = xhr.responseJSON.error;
              }
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: errorMsg
              });
            }
          });
        }
      });
    });

    // --- AJAX Delete Kategori ---
    $('#kategoriTable').on('click', '.deleteCategoryButton', function (e) {
      e.preventDefault();
      const $this = $(this);
      const id = $this.data('id');
      const $row = $this.closest('tr');

      Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: "Data yang dihapus tidak bisa dikembalikan!",
        imageUrl: 'https://cdn-icons-png.flaticon.com/512/1214/1214428.png',
        imageWidth: 80,
        imageHeight: 80,
        imageAlt: 'Icon Tong Sampah',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal',
        customClass: {
          popup: 'rounded-xl shadow-lg',
          confirmButton: 'bg-red-600 hover:bg-red-700 text-white font-medium px-4 py-2 rounded',
          cancelButton: 'bg-gray-300 hover:bg-gray-400 text-black font-medium px-4 py-2 rounded'
        }
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: baseUrl + '/' + id,
            method: 'POST',
            headers: { 'Accept': 'application/json' },
            data: { _token: csrfToken, _method: 'DELETE' },
            success: function (response) {
              playSuccessSound(); // Play sound on success
              Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: response.success,
                timer: 2000,
                showConfirmButton: false
              });
              // Hapus baris dari DataTable lalu draw untuk trigger event draw.dt yang memperbarui nomor urut
              table.row($row).remove().draw(false);
              updateRowNumbers();
            },
            error: function (xhr) {
              console.error("Delete error: ", xhr.responseText);
              let errorMsg = 'Gagal menghapus kategori!';
              if (xhr.responseJSON && xhr.responseJSON.error) {
                errorMsg = xhr.responseJSON.error;
              }
              Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: errorMsg
              });
            }
          });
        }
      });
    });
  });
</script>
@endpush
