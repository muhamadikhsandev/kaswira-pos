@extends('layouts.app')

@section('title', 'Manajemen Satuan')

@section('content')
  <header class="mb-10 text-left">
 <header class="mb-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
  <div class="flex-1">
    <h1 class="flex items-center whitespace-nowrap text-3xl sm:text-4xl font-extrabold tracking-tight text-gray-800 dark:text-gray-100 leading-tight">
      Manajemen Satuan
      <span class="material-icons ml-3 text-blue-600" style="font-size: 36px;">
        straighten
      </span>
    </h1>
    <p class="mt-2 text-base sm:text-lg text-gray-600 dark:text-gray-300 max-w-lg">
      Kelola satuan barang dengan mudah dan efisien.
    </p>
  </div>
</header>


  <section id="satuan" class="mb-10">
    <div class="bg-white dark:bg-gray-800 p-4 sm:p-6 rounded-lg shadow-lg">
      <div class="mb-4 flex flex-col sm:flex-row justify-between items-center space-y-2 sm:space-y-0 sm:space-x-4">
        <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100 mb-4 md:mb-0">
          Daftar Satuan
        </h2>
        {{-- Tombol "Tambah Satuan" yang akan membuka modal sudah diganti ke floating button di bawah --}}
      </div>

      {{-- Menghapus "card tambahan" dan langsung menempatkan tabel di dalam div utama --}}
      <div class="overflow-x-auto mt-4"> {{-- Mengganti mt-6 dengan mt-4 untuk konsistensi --}}
        <table id="satuanTable" class="min-w-full bg-white dark:bg-gray-800 shadow rounded-lg border border-gray-200 dark:border-gray-700 text-xs sm:text-sm">
          <thead class="bg-blue-600 text-white sticky top-0 z-10">
            <tr>
              <th class="px-4 py-2 text-left">No</th>
              <th class="px-4 py-2 text-left">Nama Satuan</th>
              <th class="px-4 py-2 text-left">Tanggal Dibuat</th>
              <th class="px-4 py-2 text-left">Tanggal Di Update</th>
              <th class="px-4 py-2 text-left">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
            @foreach($satuans as $satuan)
              <tr data-id="{{ $satuan->id }}" class="hover:bg-gray-50 dark:hover:bg-gray-600 transition duration-150">
                <td class="px-4 py-2 whitespace-nowrap text-gray-700 dark:text-gray-200"></td>
                <td class="px-4 py-2 whitespace-nowrap text-gray-700 dark:text-gray-200 satuan-name">
                  {{ $satuan->name }}
                </td>
                <td class="px-4 py-2 whitespace-nowrap text-gray-700 dark:text-gray-200">
                  {{ $satuan->created_at->format('Y-m-d') }}
                </td>
                <td class="px-4 py-2 whitespace-nowrap text-gray-700 dark:text-gray-200">
                  {{ $satuan->updated_at->format('Y-m-d') }}
                </td>
                <td class="px-4 py-2 whitespace-nowrap">
                  <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                    <a href="#"
                       class="block text-center text-blue-600 hover:text-blue-800 font-medium transition duration-150 editSatuanButton"
                       data-id="{{ $satuan->id }}" data-name="{{ $satuan->name }}">
                      Edit
                    </a>
                    <a href="#"
                       class="block text-center text-red-600 hover:text-red-800 font-medium transition duration-150 deleteSatuanButton"
                       data-id="{{ $satuan->id }}">
                      Hapus
                    </a>
                  </div>
                </td>
              </tr>
            @endforeach
            </tbody>
        </table>
      </div>

      {{-- Floating action button untuk menambah satuan, disamakan dengan stokbarang.blade.php --}}
      <a href="javascript:void(0);" id="openFormButton" class="floating-button bg-blue-600 text-white rounded-full shadow-lg hover:bg-blue-700 transition">
        <span class="material-icons text-lg">add</span>
      </a>
    </div>

    {{-- Modal Tambah Satuan (Popup Form) --}}
    <div id="popupForm" class="fixed inset-0 flex justify-center items-center bg-black bg-opacity-50 modal-overlay modal-hidden">
      <div class="relative bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-lg shadow-xl w-full sm:w-11/12 md:w-1/2 lg:w-1/3 mx-auto transform scale-95 transition-all duration-300 ease-in-out">
        <h3 class="text-lg sm:text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4 text-center">Tambah Satuan Baru</h3>
        <form id="ajaxForm" method="POST" action="{{ route('admin.satuan.store') }}" class="space-y-4">
          @csrf
          <div>
            <label for="satuanInput" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Satuan</label>
            <input type="text" id="satuanInput" name="name" required
                   class="w-full p-3 border border-gray-200 dark:border-gray-700 rounded focus:outline-none focus:ring-2 focus:ring-blue-600 text-sm bg-white dark:bg-gray-700"
                   placeholder="Masukkan nama satuan" />
          </div>
          <div class="flex flex-col sm:flex-row justify-end gap-2 mt-4">
            <button type="button" id="closeFormButton"
                    class="bg-gray-200 text-gray-800 dark:bg-gray-600 dark:text-gray-200 py-2 px-4 rounded hover:bg-gray-300 transition text-sm">
              Batal
            </button>
            <button type="submit" id="simpanSatuanButton"
                    class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition text-sm">
              Simpan
            </button>
          </div>
        </form>
      </div>
    </div>
  </section>

  <audio id="successSound" src="{{ asset('sounds/success.mp3') }}" preload="auto"></audio>
@endsection

@push('scripts')
<script defer>
  document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const baseUrl = "{{ url('admin/dashboard/satuan') }}";
    const successSound = document.getElementById('successSound'); // Get the audio element

    // Inisialisasi DataTable tanpa render custom untuk kolom No agar penomoran bisa dikelola secara manual
    const table = $('#satuanTable').DataTable({
      ordering: false,
      dom: '<"flex flex-col sm:flex-row items-center justify-between p-4"lf>rt<"flex flex-col sm:flex-row items-center justify-between p-4"ip>',
      language: {
        search: "",
        searchPlaceholder: "Cari satuan..."
      },
      pageLength: 5,
      lengthMenu: [ [5, 10, 25, 50, -1], [5, 10, 25, 50, "All"] ]
    });

    // Tambahkan icon search di DataTable filter
    const $dataTableFilter = $('.dataTables_filter');
    $dataTableFilter.find('input').addClass('pl-10');
    $dataTableFilter.prepend('<span class="material-icons text-blue-600">search</span>');

    // Fungsi untuk meng-update nomor baris secara manual
    function updateRowNumbers() {
      table.column(0, { search: 'applied', order: 'applied' }).nodes().each(function(cell, i) {
        cell.innerHTML = i + 1;
      });
    }
    // Panggil updateRowNumbers() setelah inisialisasi agar baris awal tertata
    updateRowNumbers();

    // --- Modal Tambah Satuan ---
    $('#openFormButton').on('click', function () {
      $('#popupForm').removeClass('modal-hidden').addClass('modal-visible');
      $('#satuanInput').focus(); // Fokus pada input saat modal dibuka
    });

    $('#closeFormButton').on('click', function () {
      $('#popupForm').removeClass('modal-visible').addClass('modal-hidden');
      $('#ajaxForm')[0].reset(); // Reset form saat ditutup
    });

    // --- AJAX Create Satuan ---
    $('#ajaxForm').submit(function(e) {
      e.preventDefault();
      const form = $(this);
      const satuanName = $('#satuanInput').val().trim();

      if (!satuanName) {
        Swal.fire({
          icon: 'warning',
          title: 'Oops...',
          text: 'Nama satuan tidak boleh kosong!',
          confirmButtonColor: '#2563EB'
        });
        return;
      }

      // Cek apakah nama sudah ada di tabel (client-side)
      let isExist = false;
      $('#satuanTable .satuan-name').each(function() {
        if ($(this).text().trim().toLowerCase() === satuanName.toLowerCase()) {
          isExist = true;
          return false; // Berhenti iterasi
        }
      });
      if (isExist) {
        Swal.fire({
          icon: 'warning',
          title: 'Oops...',
          text: 'Satuan sudah ada!',
          confirmButtonColor: '#2563EB',
          customClass: {
            confirmButton: 'bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 py-2 rounded shadow'
          }
        });
        return;
      }

      $.ajax({
        url: form.attr('action'),
        method: form.attr('method'),
        headers: { 'Accept': 'application/json' },
        data: form.serialize(),
        success: function(response) {
          successSound.play(); // Play success sound
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: response.success,
            timer: 2000,
            showConfirmButton: false
          });
          form[0].reset(); // Reset form
          $('#popupForm').removeClass('modal-visible').addClass('modal-hidden'); // Tutup modal

          // Gunakan tanggal dari response atau gunakan tanggal sekarang
          const currentDate = response.satuan.created_at
                              ? response.satuan.created_at.substr(0,10)
                              : new Date().toISOString().split('T')[0];

          // Tambahkan baris baru ke DataTable (kolom No dikosongkan; akan di-update oleh updateRowNumbers)
          const newRowNode = table.row.add([
            '',
            response.satuan.name,
            currentDate,
            currentDate,
            '<div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">' +
              '<a href="#" class="editSatuanButton block text-center text-blue-600 hover:text-blue-800 font-medium transition duration-150" data-id="'+ response.satuan.id +'" data-name="'+ response.satuan.name +'">Edit</a>' +
              '<a href="#" class="deleteSatuanButton block text-center text-red-600 hover:text-red-800 font-medium transition duration-150" data-id="'+ response.satuan.id +'">Hapus</a>' +
            '</div>'
          ]).draw(false).node();
          $(newRowNode).attr('data-id', response.satuan.id);

          // Ubah halaman ke halaman terakhir agar baris baru muncul di bagian bawah
          const info = table.page.info();
          table.page(info.pages - 1).draw(false);
          updateRowNumbers();
        },
        error: function(xhr) {
          console.error("Create error: ", xhr.responseText);
          let errorMsg = 'Gagal menambahkan satuan!';
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
    });

    // --- AJAX Edit Satuan ---
    $('#satuanTable').on('click', '.editSatuanButton', function (e) {
      e.preventDefault();
      const $this = $(this);
      const id = $this.data('id');
      const currentName = $this.data('name');

      Swal.fire({
        title: 'Edit Satuan',
        input: 'text',
        inputValue: currentName,
        inputPlaceholder: 'Masukkan nama satuan...',
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
            return 'Nama satuan tidak boleh kosong!';
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
              successSound.play(); // Play success sound
              Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: response.success,
                timer: 2000,
                showConfirmButton: false
              });
              const $row = $this.closest('tr');
              $row.find('.satuan-name').text(response.satuan.name);
              $this.attr('data-name', response.satuan.name);
              // Perbarui tampilan DataTable tanpa mengubah halaman saat ini
              table.draw(false);
              updateRowNumbers();
            },
            error: function (xhr) {
              console.error("Update error: ", xhr.responseText);
              let errorMsg = 'Gagal memperbarui satuan!';
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

    // --- AJAX Delete Satuan ---
    $('#satuanTable').on('click', '.deleteSatuanButton', function (e) {
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
              successSound.play(); // Play success sound
              Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: response.success,
                timer: 2000,
                showConfirmButton: false
              });
              // Hapus baris dari DataTable, draw kembali, lalu update nomor urut
              table.row($row).remove().draw(false);
              updateRowNumbers();
            },
            error: function (xhr) {
              console.error("Delete error: ", xhr.responseText);
              let errorMsg = 'Gagal menghapus satuan!';
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
