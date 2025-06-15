@extends('layouts.app')

@section('title', 'Manajemen Stok Barang')

@section('content')
  <header class="mb-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
    <div class="flex-1">
   <h1 class="flex items-center text-3xl sm:text-4xl font-extrabold tracking-tight text-gray-800 dark:text-gray-100 leading-tight">
  Manajemen Barang
  <span class="material-icons ml-3 text-blue-600" style="font-size: 36px;">
    inventory
  </span>
</h1>
    <p class="mt-2 text-base sm:text-lg text-gray-600 dark:text-gray-300 max-w-lg">
      Kelola data stok barang dan inventaris produk dengan mudah dan efisien.
    </p>
    </div>
  </header>


  <section id="stock" class="mb-8">
    <div class="bg-white dark:bg-gray-800 p-4 sm:p-6 rounded-lg shadow-lg">
    <div class="mb-4 flex flex-row flex-wrap justify-between items-center gap-2">
      <button id="openImportButton"
      class="flex-1 sm:flex-none bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 transition text-sm flex items-center justify-center gap-2">
      <i class="fas fa-file-import"></i>
      Import Excel
      </button>
      <button id="resetAllDataButton"
      class="flex-1 sm:flex-none bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700 transition text-sm flex items-center justify-center gap-2">
      <span class="material-icons">restore</span>
      Reset Data
      </button>
    </div>

    <div class="overflow-x-auto">
      <table id="stockTable"
      class="min-w-full bg-white dark:bg-gray-800 shadow rounded-lg border border-gray-200 dark:border-gray-700 text-xs sm:text-sm">
      <thead class="bg-blue-600 text-white sticky top-0 z-10">
        <tr>
        <th class="px-4 py-2 text-left">No</th>
        <th class="px-4 py-2 text-left">Kode</th>
        <th class="px-4 py-2 text-left">Kategori</th>
        <th class="px-4 py-2 text-left">Merek</th>
        <th class="px-4 py-2 text-left">Nama Produk</th>
        <th class="px-4 py-2 text-right">Harga Beli</th>
        <th class="px-4 py-2 text-right">Harga Jual</th>
        <th class="px-4 py-2 text-center">Satuan</th>
        <th class="px-4 py-2 text-center">Stok</th>
        <th class="px-4 py-2 text-center">Tanggal Buat</th>
        <th class="px-4 py-2 text-center">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
        @foreach ($barangs as $barang)
      <tr data-id="{{ $barang->id }}" class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
      <td class="px-4 py-2"></td>
      <td class="px-4 py-2">{{ $barang->kode_barang }}</td>
      <td class="px-4 py-2">{{ $barang->category ? $barang->category->name : '-' }}</td>
      <td class="px-4 py-2">{{ $barang->merek }}</td>
      <td class="px-4 py-2">{{ $barang->nama_produk }}</td>
      <td class="px-4 py-2 text-right">Rp {{ number_format($barang->harga_beli, 0, ',', '.') }}</td>
      <td class="px-4 py-2 text-right">Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</td>
      <td class="px-4 py-2 text-center">{{ $barang->satuanRelation ? $barang->satuanRelation->name : '-' }}</td>
      <td class="px-4 py-2 text-center">{{ $barang->stok }}</td>
      <td class="px-4 py-2 text-center">
        @if($barang->created_at)
      {{ $barang->created_at->format('d/m/Y H:i') }}
      @else
      -
      @endif
      </td>
      <td class="px-4 py-2 text-center flex justify-center space-x-1">
        <a href="#" class="text-indigo-600 hover:text-indigo-800 font-medium transition duration-150 edit-btn"
        data-id="{{ $barang->id }}" data-kategori-id="{{ $barang->kategori }}" data-merek="{{ $barang->merek }}"
        data-nama_produk="{{ $barang->nama_produk }}" data-harga_beli="{{ (int) $barang->harga_beli }}"
        data-harga_jual="{{ (int) $barang->harga_jual }}" data-satuan-id="{{ $barang->satuan }}"
        data-stok="{{ $barang->stok }}">
        Edit
        </a>
        <form action="{{ route('admin.stokbarang.destroy', $barang->id) }}" method="POST"
        class="inline delete-form">
        @csrf
        @method('DELETE')
        <a href="#" class="text-red-600 hover:text-red-800 font-medium transition duration-150 delete-btn">
        Hapus
        </a>
        </form>
      </td>
      </tr>
      @endforeach
      </tbody>
      </table>
    </div>

    <a href="javascript:void(0);" id="openFormButton"
      class="floating-button bg-blue-600 text-white rounded-full shadow-lg hover:bg-blue-700 transition">
      <span class="material-icons text-lg">add</span>
    </a>

    <div id="popupForm"
      class="fixed inset-0 flex justify-center items-center bg-black bg-opacity-50 modal-overlay modal-hidden">
      <div
      class="relative bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-lg shadow-xl w-full sm:w-11/12 md:w-1/2 lg:w-1/3 mx-auto transform scale-95 transition-all duration-300 ease-in-out max-h-[90vh] overflow-y-auto">
      <!-- Tombol Close -->
      <button type="button" id="closeFormButton"
        class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 dark:text-gray-300 dark:hover:text-white text-xl">
        &times;
      </button>
      <h3 class="text-lg sm:text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4 text-center">Tambah Barang
      </h3>
      <form id="ajaxForm" method="POST" action="{{ route('admin.stokbarang.store') }}" class="space-y-4">
        @csrf
        <div>
        <label for="kode_barang" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kode
          Barang</label>
        <input type="text" id="kode_barang" name="kode_barang" required
          class="w-full p-3 border border-gray-200 dark:border-gray-700 rounded focus:outline-none focus:ring-2 focus:ring-blue-600 text-sm bg-white dark:bg-gray-700"
          placeholder="Masukkan kode barang" />
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label for="kategori" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kategori</label>
          <select id="kategori" name="kategori" required
          class="w-full p-3 border border-gray-200 dark:border-gray-700 rounded focus:outline-none focus:ring-2 focus:ring-blue-600 text-sm bg-white dark:bg-gray-700">
          <option value="">Pilih Kategori</option>
          @foreach($categories as $category)
        <option value="{{ $category->id }}">{{ $category->name }}</option>
      @endforeach
          </select>
        </div>
        <div>
          <label for="merek" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Merek</label>
          <input type="text" id="merek" name="merek" required
          class="w-full p-3 border border-gray-200 dark:border-gray-700 rounded focus:outline-none focus:ring-2 focus:ring-blue-600 text-sm bg-white dark:bg-gray-700"
          placeholder="Masukkan merek" />
        </div>
        </div>
        <div>
        <label for="nama_produk" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama
          Produk</label>
        <input type="text" id="nama_produk" name="nama_produk" required
          class="w-full p-3 border border-gray-200 dark:border-gray-700 rounded focus:outline-none focus:ring-2 focus:ring-blue-600 text-sm bg-white dark:bg-gray-700"
          placeholder="Masukkan nama produk" />
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label for="harga_beli" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Harga
          Beli</label>
          <input type="text" id="harga_beli" name="harga_beli" required
          class="w-full p-3 border border-gray-200 dark:border-gray-700 rounded focus:outline-none focus:ring-2 focus:ring-blue-600 text-sm bg-white dark:bg-gray-700"
          placeholder="Masukkan harga beli" />
        </div>
        <div>
          <label for="harga_jual" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Harga
          Jual</label>
          <input type="text" id="harga_jual" name="harga_jual" required
          class="w-full p-3 border border-gray-200 dark:border-gray-700 rounded focus:outline-none focus:ring-2 focus:ring-blue-600 text-sm bg-white dark:bg-gray-700"
          placeholder="Masukkan harga jual" />
        </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label for="satuan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Satuan</label>
          <select id="satuan" name="satuan" required
          class="w-full p-3 border border-gray-200 dark:border-gray-700 rounded focus:outline-none focus:ring-2 focus:ring-blue-600 text-sm bg-white dark:bg-gray-700">
          <option value="">Pilih Satuan Barang</option>
          @foreach($satuans as $sat)
        <option value="{{ $sat->id }}">{{ $sat->name }}</option>
      @endforeach
          </select>
        </div>
        <div>
          <label for="stok" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Stok</label>
          <input type="number" id="stok" name="stok" required
          class="w-full p-3 border border-gray-200 dark:border-gray-700 rounded focus:outline-none focus:ring-2 focus:ring-blue-600 text-sm bg-white dark:bg-gray-700"
          placeholder="Masukkan stok" />
        </div>
        </div>
        <div class="flex justify-end mt-4">
        <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition text-sm">
          Simpan
        </button>
        </div>
      </form>
      </div>
    </div>

    <div id="popupEditForm"
      class="fixed inset-0 flex justify-center items-center bg-black bg-opacity-50 modal-overlay modal-hidden">
      <div
      class="relative bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-lg shadow-xl w-full sm:w-11/12 md:w-1/2 lg:w-1/3 mx-auto transform scale-95 transition-all duration-300 ease-in-out max-h-[90vh] overflow-y-auto">
      <!-- Tombol Close -->
      <button type="button" id="closeEditFormButton"
        class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 dark:text-gray-300 dark:hover:text-white text-xl">
        &times;
      </button>
      <h3 class="text-lg sm:text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4 text-center">Edit Barang</h3>
      <form id="ajaxEditForm" method="POST" action="">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label for="edit_kategori"
          class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kategori</label>
          <select id="edit_kategori" name="kategori" data-selected=""
          class="w-full p-3 border border-gray-200 dark:border-gray-700 rounded focus:outline-none focus:ring-2 focus:ring-blue-600 text-sm bg-white dark:bg-gray-700">
          <option value="">Pilih Kategori</option>
          @foreach($categories as $category)
        <option value="{{ $category->id }}">{{ $category->name }}</option>
      @endforeach
          </select>
        </div>
        <div>
          <label for="edit_merek" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Merek</label>
          <input type="text" id="edit_merek" name="merek" placeholder="Masukkan merek"
          class="w-full p-3 border border-gray-200 dark:border-gray-700 rounded focus:outline-none focus:ring-2 focus:ring-blue-600 text-sm bg-white dark:bg-gray-700">
        </div>
        </div>
        <div>
        <label for="edit_nama_produk" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama
          Produk</label>
        <input type="text" id="edit_nama_produk" name="nama_produk" placeholder="Masukkan nama produk"
          class="w-full p-3 border border-gray-200 dark:border-gray-700 rounded focus:outline-none focus:ring-2 focus:ring-blue-600 text-sm bg-white dark:bg-gray-700">
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label for="edit_harga_beli" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Harga
          Beli</label>
          <input type="text" id="edit_harga_beli" name="harga_beli" placeholder="Masukkan harga beli"
          class="w-full p-3 border border-gray-200 dark:border-gray-700 rounded focus:outline-none focus:ring-2 focus:ring-blue-600 text-sm bg-white dark:bg-gray-700">
        </div>
        <div>
          <label for="edit_harga_jual" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Harga
          Jual</label>
          <input type="text" id="edit_harga_jual" name="harga_jual" placeholder="Masukkan harga jual"
          class="w-full p-3 border border-gray-200 dark:border-gray-700 rounded focus:outline-none focus:ring-2 focus:ring-blue-600 text-sm bg-white dark:bg-gray-700">
        </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label for="edit_satuan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Satuan</label>
          <select id="edit_satuan" name="satuan" data-selected=""
          class="w-full p-3 border border-gray-200 dark:border-gray-700 rounded focus:outline-none focus:ring-2 focus:ring-blue-600 text-sm bg-white dark:bg-gray-700">
          <option value="">Pilih Satuan Barang</option>
          @foreach($satuans as $satuan)
        <option value="{{ $satuan->id }}">{{ $satuan->name }}</option>
      @endforeach
          </select>
        </div>
        <div>
          <label for="edit_stok" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Stok</label>
          <input type="number" id="edit_stok" name="stok" placeholder="Masukkan stok"
          class="w-full p-3 border border-gray-200 dark:border-gray-700 rounded focus:outline-none focus:ring-2 focus:ring-blue-600 text-sm bg-white dark:bg-gray-700">
        </div>
        </div>
        <div class="flex justify-end mt-4">
        <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition text-sm">
          Update
        </button>
        </div>
      </form>
      </div>
    </div>

    <div id="popupImportForm"
      class="fixed inset-0 flex justify-center items-center bg-black bg-opacity-50 modal-overlay modal-hidden">
      <div
      class="relative bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-lg shadow-xl w-full sm:w-11/12 md:w-1/2 lg:w-1/3 mx-auto transform scale-95 transition-all duration-300 ease-in-out max-h-[90vh] overflow-y-auto">
      <!-- Tombol Close silang di pojok kanan atas -->
      <button type="button" id="closeImportButton"
        class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 dark:text-gray-300 dark:hover:text-white text-xl">
        &times;
      </button>
      <h3 class="text-lg sm:text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4 text-center">Import Data
        Barang</h3>
      <form action="{{ route('admin.stokbarang.import') }}" method="POST" enctype="multipart/form-data"
        class="space-y-4">
        @csrf
        <div>
        <label for="file" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Upload File
          Excel:</label>
        <input type="file" name="file" id="file" required
          class="w-full p-3 border border-gray-200 dark:border-gray-700 rounded bg-white dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-green-600 text-sm" />
        </div>
        <div class="flex justify-end gap-2">
        <button type="submit"
          class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 transition text-sm">
          Import
        </button>
        </div>
      </form>
      </div>
    </div>

  </section>

  <audio id="successSound" src="{{ asset('sounds/success.mp3') }}" preload="auto"></audio>

@endsection

@push('scripts')
  <script>
    $(document).ready(function () {
    // Pasang CSRF token untuk setiap AJAX request
    $.ajaxSetup({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // Inisialisasi DataTable tanpa render nomor otomatis
    var dt = $('#stockTable').DataTable({
      dom: '<"flex flex-col sm:flex-row items-center justify-between"lf>rt<"flex flex-col sm:flex-row items-center justify-between"ip>',
      language: {
      search: "",
      searchPlaceholder: "Cari data..."
      },
      pageLength: 5,
      lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
      order: []
    });

    // Fungsi untuk memainkan suara sukses
    function playSuccessSound() {
      var sound = document.getElementById('successSound');
      if (sound) {
      sound.currentTime = 0; // Rewind to the start
      sound.play().catch(function (error) {
        console.log("Error playing sound:", error); // Optional: Log any playback errors
      });
      }
    }

    // Fungsi update nomor secara manual (sesuai urutan baris saat ini)
    function updateRowNumbers() {
      dt.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
      cell.innerHTML = i + 1;
      });
    }

    // Panggil fungsi updateRowNumbers saat DataTable melakukan redraw (termasuk saat load pertama)
    dt.on('draw', updateRowNumbers);

    // Jika perlu, panggil updateRowNumbers juga setelah inisialisasi
    updateRowNumbers();

    // Tambahkan icon search ke filter DataTable
    const $dataTableFilter = $('.dataTables_filter');
    $dataTableFilter.find('input').addClass('pl-10');
    $dataTableFilter.prepend('<span class="material-icons text-blue-600">search</span>');

    // Fungsi format angka dengan titik sebagai pemisah ribuan
    function formatNumberWithDot(value) {
      var num = String(value).replace(/\D/g, ''); // Pastikan value adalah string sebelum replace
      return num.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    // Event listener format input harga (create & edit)
    $('#harga_beli, #harga_jual, #edit_harga_beli, #edit_harga_jual').on('keyup', function () {
      $(this).val(formatNumberWithDot($(this).val()));
    });

    // Modal Tambah Barang
    $('#openFormButton').click(function () {
      $('#popupForm').removeClass('modal-hidden').addClass('modal-visible');
    });
    $('#closeFormButton').click(function () {
      $('#popupForm').removeClass('modal-visible').addClass('modal-hidden');
    });

    // Modal Import Excel
    $('#openImportButton').click(function () {
      $('#popupImportForm').removeClass('modal-hidden').addClass('modal-visible');
    });
    $('#closeImportButton').click(function () {
      $('#popupImportForm').removeClass('modal-visible').addClass('modal-hidden');
    });

    // Import Excel via AJAX
    $('#popupImportForm form').submit(function (e) {
      e.preventDefault();
      var formData = new FormData(this);
      $.ajax({
      url: $(this).attr('action'),
      type: 'POST',
      data: formData,
      contentType: false,
      processData: false,
      success: function (response) {
        if (response.warning) {
        Swal.fire({
          icon: 'warning',
          title: 'Peringatan!',
          text: 'Kode barang sudah ada!',
          confirmButtonColor: '#2563eb'
        });
        return;
        }
        Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: response.message,
        timer: 3000,
        showConfirmButton: false
        });
        playSuccessSound(); // Play sound on success
        $('#popupImportForm').removeClass('modal-visible').addClass('modal-hidden');

        if (response.barangs) {
        var tableBody = $('#stockTable tbody');
        tableBody.empty(); // Clear existing rows before adding new ones from import
        dt.clear(); // Clear DataTable's internal data

        $.each(response.barangs, function (index, barang) {
          var formattedDate = barang.created_at ? barang.created_at : '-';
          var newRow =
          `<tr data-id="${barang.id}">
          <td></td>
          <td>${barang.kode_barang}</td>
          <td>${barang.kategori}</td>
          <td>${barang.merek}</td>
          <td>${barang.nama_produk}</td>
          <td class="text-right">Rp ${barang.harga_beli_formatted}</td>
          <td class="text-right">Rp ${barang.harga_jual_formatted}</td>
          <td class="text-center">${barang.satuan}</td>
          <td class="text-center">${barang.stok}</td>
          <td class="text-center">${formattedDate}</td>
          <td class="text-center flex justify-center space-x-1">
            <a href="#" class="text-indigo-600 hover:text-indigo-800 font-medium transition duration-150 edit-btn"
             data-id="${barang.id}"
             data-kategori-id="${barang.kategori_id}"
             data-merek="${barang.merek}"
             data-nama_produk="${barang.nama_produk}"
             data-harga_beli="${parseInt(barang.harga_beli)}"
             data-harga_jual="${parseInt(barang.harga_jual)}"
             data-satuan-id="${barang.satuan_id}"
             data-stok="${barang.stok}">
            Edit
            </a>
            <form action="{{ route('admin.stokbarang.destroy', '') }}/${barang.id}" method="POST" class="inline delete-form">
            @csrf
            @method('DELETE')
            <a href="#" class="text-red-600 hover:text-red-800 font-medium transition duration-150 delete-btn">
              Hapus
            </a>
            </form>
          </td>
          </tr>`;
          dt.row.add($(newRow)); // Add row to DataTable
        });
        dt.draw(false); // Redraw DataTable after adding all rows
        }


        if (response.categories && response.satuans) {
        let kategoriSelect = $('#kategori');
        let satuanSelect = $('#satuan');
        kategoriSelect.empty().append('<option value="">Pilih Kategori</option>');
        satuanSelect.empty().append('<option value="">Pilih Satuan Barang</option>');
        $.each(response.categories, function (index, cat) {
          kategoriSelect.append(`<option value="${cat.id}">${cat.name}</option>`);
        });
        $.each(response.satuans, function (index, sat) {
          satuanSelect.append(`<option value="${sat.id}">${sat.name}</option>`);
        });

        let kategoriEditSelect = $('#edit_kategori');
        let satuanEditSelect = $('#edit_satuan');
        let selectedKategori = kategoriEditSelect.attr('data-selected') || '';
        let selectedSatuan = satuanEditSelect.attr('data-selected') || '';
        kategoriEditSelect.empty().append('<option value="">Pilih Kategori</option>');
        satuanEditSelect.empty().append('<option value="">Pilih Satuan Barang</option>');
        $.each(response.categories, function (index, cat) {
          let selected = (cat.id == selectedKategori) ? 'selected' : '';
          kategoriEditSelect.append(`<option value="${cat.id}" ${selected}>${cat.name}</option>`);
        });
        $.each(response.satuans, function (index, sat) {
          let selected = (sat.id == selectedSatuan) ? 'selected' : '';
          satuanEditSelect.append(`<option value="${sat.id}" ${selected}>${sat.name}</option>`);
        });
        }
      },
      error: function (xhr) {
        Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: xhr.responseJSON.error || 'Terjadi kesalahan saat mengimport data.'
        });
      }
      });
    });

    // Reset Semua Data
    $('#resetAllDataButton').click(function () {
      Swal.fire({
      title: '<i class="material-icons" style="color: #d33; font-size: 50px;">restore</i><br>Yakin ingin mereset semua data?',
      html: "<b>Semua data barang akan dihapus dan tidak bisa dikembalikan!</b>",
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Ya, reset!',
      cancelButtonText: 'Batal',
      customClass: {
        popup: 'rounded-xl shadow-lg',
        confirmButton: 'bg-red-600 hover:bg-red-700 text-white font-medium px-4 py-2 rounded',
        cancelButton: 'bg-gray-300 hover:bg-gray-400 text-black font-medium px-4 py-2 rounded'
      }
      }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
        url: "{{ route('admin.stokbarang.resetAll') }}",
        type: "POST",
        dataType: "json",
        data: { _token: $('meta[name="csrf-token"]').attr('content') },
        success: function (response) {
          if (response.success) {
          Swal.fire({
            title: 'Berhasil!',
            text: response.success,
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
          });
          playSuccessSound(); // Play sound on success
          dt.clear().draw(); // akan memanggil updateRowNumbers() karena event draw
          } else if (response.error) {
          Swal.fire({
            title: 'Tidak Ada Data!',
            text: response.error,
            icon: 'info',
            confirmButtonText: 'OK',
            customClass: {
            confirmButton: 'bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded'
            }
          });
          }
        },
        error: function (xhr) {
          Swal.fire({
          title: 'Error!',
          text: xhr.responseJSON.error || 'Terjadi kesalahan saat mereset data.',
          icon: 'error',
          confirmButtonText: 'OK',
          customClass: {
            confirmButton: 'bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded'
          }
          });
        }
        });
      }
      });
    });

    // AJAX Create (Tambah Barang)
    $('#ajaxForm').submit(function (e) {
      e.preventDefault();
      var form = $(this);
      var hargaBeliRaw = $('#harga_beli').val().replace(/\./g, '');
      var hargaJualRaw = $('#harga_jual').val().replace(/\./g, '');
      $('#harga_beli').val(hargaBeliRaw); // Set raw value for submission
      $('#harga_jual').val(hargaJualRaw); // Set raw value for submission

      $.ajax({
      url: form.attr('action'),
      method: form.attr('method'),
      data: form.serialize(),
      success: function (response) {
        Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: response.success,
        timer: 3000,
        showConfirmButton: false
        });
        playSuccessSound(); // Play sound on success
        var b = response.barang;
        var newRow =
        `<tr data-id="${b.id}">
        <td></td>
        <td>${b.kode_barang}</td>
        <td>${b.kategori}</td>
        <td>${b.merek}</td>
        <td>${b.nama_produk}</td>
        <td class="text-right">Rp ${b.harga_beli_formatted}</td>
        <td class="text-right">Rp ${b.harga_jual_formatted}</td>
        <td class="text-center">${b.satuan}</td>
        <td class="text-center">${b.stok}</td>
        <td class="text-center">${b.created_at ?? '-'}</td>
        <td class="text-center flex justify-center space-x-1">
          <a href="#" class="text-indigo-600 hover:text-indigo-800 font-medium transition duration-150 edit-btn"
           data-id="${b.id}"
           data-kategori-id="${b.kategori_id}"
           data-merek="${b.merek}"
           data-nama_produk="${b.nama_produk}"
           data-harga_beli="${parseInt(b.harga_beli)}"
           data-harga_jual="${parseInt(b.harga_jual)}"
           data-satuan-id="${b.satuan_id}"
           data-stok="${b.stok}">
          Edit
          </a>
          <form action="{{ route('admin.stokbarang.destroy', '') }}/${b.id}" method="POST" class="inline delete-form">
          @csrf
          @method('DELETE')
          <a href="#" class="text-red-600 hover:text-red-800 font-medium transition duration-150 delete-btn">
            Hapus
          </a>
          </form>
        </td>
        </tr>`;

        dt.row.add($(newRow)).draw(false);

        form[0].reset();
        $('#harga_beli, #harga_jual').val(''); // Clear formatted values as well
        $('#popupForm').removeClass('modal-visible').addClass('modal-hidden');
      },
      error: function (xhr) {
        // Re-format if error
        $('#harga_beli').val(formatNumberWithDot(hargaBeliRaw));
        $('#harga_jual').val(formatNumberWithDot(hargaJualRaw));
        let errorMessage = (xhr.responseJSON && xhr.responseJSON.error) ? xhr.responseJSON.error : 'Gagal menambahkan barang.';
        Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: errorMessage
        });
      }
      });
    });

    // Klik Edit Barang
    $(document).on('click', '.edit-btn', function (e) {
      e.preventDefault();
      var id = $(this).data('id');
      var kategoriId = $(this).data('kategori-id');
      var merek = $(this).data('merek');
      var namaProduk = $(this).data('nama_produk');
      var hargaBeli = parseInt($(this).data('harga_beli')) || 0;
      var hargaJual = parseInt($(this).data('harga_jual')) || 0;
      var satuanId = $(this).data('satuan-id');
      var stok = $(this).data('stok');

      $('#edit_kategori').val(kategoriId).attr('data-selected', kategoriId);
      $('#edit_satuan').val(satuanId).attr('data-selected', satuanId);
      $('#edit_merek').val(merek);
      $('#edit_nama_produk').val(namaProduk);
      $('#edit_stok').val(stok);

      var hargaBeliFormatted = formatNumberWithDot(hargaBeli);
      var hargaJualFormatted = formatNumberWithDot(hargaJual);
      $('#edit_harga_beli').val(hargaBeliFormatted);
      $('#edit_harga_jual').val(hargaJualFormatted);

      var updateUrl = "{{ route('admin.stokbarang.update', ':id') }}".replace(':id', id);
      $('#ajaxEditForm').attr('action', updateUrl);

      $('#popupEditForm').removeClass('modal-hidden').addClass('modal-visible');
    });

    $('#closeEditFormButton').click(function () {
      $('#popupEditForm').removeClass('modal-visible').addClass('modal-hidden');
    });

    // AJAX Update Barang
    $('#ajaxEditForm').submit(function (e) {
      e.preventDefault();
      var form = $(this);
      var hargaBeliRaw = $('#edit_harga_beli').val().replace(/\./g, '');
      var hargaJualRaw = $('#edit_harga_jual').val().replace(/\./g, '');
      $('#edit_harga_beli').val(hargaBeliRaw); // Set raw value for submission
      $('#edit_harga_jual').val(hargaJualRaw); // Set raw value for submission

      $.ajax({
      url: form.attr('action'),
      method: form.attr('method'),
      data: form.serialize(),
      success: function (response) {
        Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: response.success,
        timer: 3000,
        showConfirmButton: false
        });
        playSuccessSound(); // Play sound on success
        var b = response.barang;
        var rowNode = dt.row($('tr[data-id="' + b.id + '"]')).node(); // Get the DOM element for the row

        if (rowNode) {
        var rowData = dt.row(rowNode).data(); // Get current data array for the row
        // Update the data array directly for better performance with DataTables
        rowData[1] = b.kode_barang;
        rowData[2] = b.kategori;
        rowData[3] = b.merek;
        rowData[4] = b.nama_produk;
        rowData[5] = 'Rp ' + b.harga_beli_formatted;
        rowData[6] = 'Rp ' + b.harga_jual_formatted;
        rowData[7] = b.satuan;
        rowData[8] = b.stok;
        // Note: The 'No' column (index 0) and 'Tanggal Buat' (index 9) might need specific handling if they are part of rowData and need update.
        // For now, assuming they are handled by draw() or are not primary targets of this update.
        dt.row(rowNode).data(rowData).draw(false); // Update row data and redraw
        }


        $('#popupEditForm').removeClass('modal-visible').addClass('modal-hidden');
      },
      error: function (xhr) {
        // Re-format if error
        $('#edit_harga_beli').val(formatNumberWithDot(hargaBeliRaw));
        $('#edit_harga_jual').val(formatNumberWithDot(hargaJualRaw));
        Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: xhr.responseJSON.error || 'Terjadi kesalahan, silakan coba lagi.'
        });
      }
      });
    });

    // Hapus Barang
    $(document).on('click', '.delete-btn', function (e) {
      e.preventDefault();
      var form = $(this).closest('.delete-form');
      var row = $(this).closest('tr');

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
        url: form.attr('action'),
        method: form.attr('method'),
        data: form.serialize(),
        success: function (response) {
          Swal.fire({
          icon: 'success',
          title: 'Berhasil!',
          text: response.success,
          timer: 2000,
          showConfirmButton: false
          });
          playSuccessSound(); // Play sound on success
          dt.row(row).remove().draw(false); // redraw untuk memicu updateRowNumbers()
        },
        error: function () {
          Swal.fire({
          icon: 'error',
          title: 'Error!',
          text: 'Terjadi kesalahan saat menghapus data.'
          });
        }
        });
      }
      });
    });
    });
  </script>
@endpush