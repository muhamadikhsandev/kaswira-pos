@extends('layouts.app')

@section('title', 'Halaman Kasir - Admin')

@section('content')
  <audio id="audioPreview" src="{{ asset('sounds/previewstruk.mp3') }}" preload="auto"></audio>
  <audio id="audioPrint" src="{{ asset('sounds/cetakstruk.mp3') }}" preload="auto"></audio>
  <audio id="audioSendWhatsApp" src="{{ asset('sounds/cetakstruk.mp3') }}" preload="auto"></audio>
  <audio id="audioSuccessSavePrint" src="{{ asset('sounds/success.mp3') }}" preload="auto"></audio>
  <audio id="audioPrintSuccess" src="{{ asset('sounds/success.mp3') }}" preload="auto"></audio>
  {{-- New audio elements --}}
  <audio id="audioAddToCart" src="{{ asset('sounds/addtocart.mp3') }}" preload="auto"></audio>
  <audio id="audioRemoveFromCart" src="{{ asset('sounds/removecart.mp3') }}" preload="auto"></audio>
  <audio id="audioResetCartSuccess" src="{{ asset('sounds/success.mp3') }}" preload="auto"></audio>

  <header class="mb-6 md:mb-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div class="mb-6">
      <h1 class="text-3xl sm:text-4xl font-bold text-gray-800 dark:text-gray-100 mb-2 sm:mb-3">
      Transaksi Cerdas
      </h1>
      <p class="text-gray-600 dark:text-gray-300 text-base sm:text-lg">
      Pembayaran cepat, mudah, dan tanpa repot.
      </p>
    </div>

    <div
      class="w-full md:w-auto text-left md:text-right p-4 bg-white dark:bg-[#2C3442] shadow-md rounded-lg border border-blue-600 overflow-x-auto">
      <table class="table-auto border-collapse min-w-full">
      <tr class="bg-blue-600 text-white">
        <td class="px-4 py-2 font-bold border border-blue-600">Hari</td>
        <td class="px-4 py-2 border border-blue-600" id="currentDay"></td>
      </tr>
      <tr>
        <td class="px-4 py-2 font-bold border border-blue-600 text-blue-600">Tanggal</td>
        <td class="px-4 py-2 border border-blue-600" id="currentDate"></td>
      </tr>
      <tr>
        <td class="px-4 py-2 font-bold border border-blue-600 text-blue-600">Waktu</td>
        <td class="px-4 py-2 border border-blue-600" id="currentTime"></td>
      </tr>
      </table>
    </div>
    </div>
  </header>

  <section id="kasir">
    <h2 class="text-lg sm:text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4">Transaksi Kasir</h2>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 mb-4">
      <h3 class="text-base sm:text-lg font-semibold text-gray-800 dark:text-gray-100">Daftar Barang</h3>
      </div>
      <div class="overflow-x-auto max-w-full">
      <table id="barangTable" class="modern-table min-w-full border border-gray-200 dark:border-[#39455A] text-sm">
        <thead>
        <tr>
          {{-- Kode Barang column is hidden but data is still accessible for scanning --}}
          <th class="px-2 py-1 text-left hidden">Kode Barang</th>
          <th class="px-2 py-1 text-left">Nama Produk</th>
          <th class="px-2 py-1 text-left">Harga Beli</th>
          <th class="px-2 py-1 text-left">Harga Jual</th>
          <th class="px-2 py-1 text-left">Aksi</th>
        </tr>
        </thead>
        <tbody id="barangTableBody">
        @foreach ($barangs as $barang)
      <tr class="border-b border-gray-200 dark:border-[#39455A] hover:bg-gray-50 dark:hover:bg-[#313A4C]">
        {{-- Kode Barang data is still present with data-code attribute but hidden from UI --}}
        <td class="px-2 py-1 hidden" data-code="{{ $barang->kode_barang }}">{{ $barang->kode_barang }}</td>
        <td class="px-2 py-1" data-name="{{ $barang->nama_produk }}">{{ $barang->nama_produk }}</td>
        <td class="px-2 py-1">Rp {{ number_format($barang->harga_beli, 0, ',', '.') }}</td>
        <td class="px-2 py-1" data-price="{{ $barang->harga_jual }}">Rp
        {{ number_format($barang->harga_jual, 0, ',', '.') }}
        </td>
        <td class="px-2 py-1">
        <button
        class="text-blue-500 dark:text-blue-400 hover:text-blue-700 transition duration-300 add-to-cart-btn"
        data-code="{{ $barang->kode_barang }}" data-name="{{ $barang->nama_produk }}"
        data-price="{{ $barang->harga_jual }}">
        <span class="material-icons text-base">add_shopping_cart</span>
        </button>
        </td>
      </tr>
      @endforeach
        </tbody>
      </table>
      </div>
    </div>

    <div class="space-y-6">
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
      <div class="flex justify-between items-center mb-3">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Keranjang Belanja</h3>
        <button onclick="confirmClearCart()"
        class="bg-red-500 text-white py-1 px-3 rounded-md hover:bg-red-600 text-sm flex items-center">
        <span class="material-icons mr-1">restore</span>Reset Keranjang
        </button>
      </div>
      <div class="overflow-x-auto max-w-full">
        <table id="cartTable" class="min-w-full border border-gray-200 dark:border-[#39455A] text-sm">
        <thead>
          <tr class="bg-gray-50 dark:bg-[#39455A] text-gray-700 dark:text-gray-200">
          <th class="px-2 py-1 text-left">No</th>
          <th class="px-2 py-1 text-left">Nama Produk</th>
          <th class="px-2 py-1 text-left">Jumlah</th>
          <th class="px-2 py-1 text-left">Total</th>
          <th class="px-2 py-1 text-left">Pemilik</th>
          <th class="px-2 py-1 text-left">Aksi</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
        </table>
      </div>
      </div>

      <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 space-y-4">
      <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Form Pembayaran</h3>

      <div>
        <label for="transactionDate" class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Tanggal
        Transaksi</label>
        <input type="text" id="transactionDate"
        class="w-full p-2 border rounded-md bg-gray-50 dark:bg-[#39455A] text-sm text-gray-800 dark:text-gray-200"
        readonly />
      </div>

      <div>
        <label for="total" class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Total Pembayaran</label>
        <input type="text" id="total" value="Rp 0"
        class="w-full p-2 border rounded-md bg-gray-50 dark:bg-[#39455A] text-sm text-gray-800 dark:text-gray-200"
        readonly />
      </div>

      <div>
        <label for="payment" class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Jumlah Bayar</label>
        <input type="text" id="payment" inputmode="numeric" pattern="[0-9]*"
        class="w-full p-2 border rounded-md text-sm bg-white dark:bg-[#39455A] text-gray-800 dark:text-gray-200"
        placeholder="Masukkan jumlah pembayaran" />
      </div>
      <div>
        <label for="change" class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Kembalian</label>
        <input type="text" id="change"
        class="w-full p-2 border rounded-md bg-gray-50 dark:bg-[#39455A] text-sm text-gray-800 dark:text-gray-200"
        readonly />
      </div>

      <div class="flex flex-col sm:flex-row justify-between gap-2">
        <button type="button" id="previewReceiptBtn"
        class="bg-indigo-500 hover:bg-indigo-600 text-white py-2 px-4 rounded-md flex items-center text-sm w-full sm:w-auto"
        onclick="previewReceipt()">
        <span class="material-icons mr-1">receipt_long</span>Preview Struk
        </button>
        <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
        <button onclick="printAndSaveTransaction()"
          class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md flex items-center text-sm w-full sm:w-auto">
          <span class="material-icons mr-1">print</span>Cetak Struk
        </button>
        </div>
      </div>
      </div>
    </div>
    </div>
  </section>

  <form id="previewForm" action="{{ route('admin.preview.struk') }}" method="POST" style="display:none;">
    @csrf
    <input type="hidden" name="tanggal" id="previewDate">
    <input type="hidden" name="waktu" id="previewTime">
    <input type="hidden" name="total" id="previewTotal">
    <input type="hidden" name="bayar" id="previewPayment">
    <input type="hidden" name="kembalian" id="previewChange">
    <input type="hidden" name="nama_kasir" value="{{ auth()->user()->profile->name ?? auth()->user()->name }}">
    <input type="hidden" name="items" id="previewItems">
  </form>
@endsection

@push('scripts')
  <script>
    $(document).ready(function () {
    // Audio elements
    const audioPreview = document.getElementById('audioPreview');
    const audioPrint = document.getElementById('audioPrint');
    const audioSendWhatsApp = document.getElementById('audioSendWhatsApp');
    const audioSuccessSavePrint = document.getElementById('audioSuccessSavePrint');
    const audioPrintSuccess = document.getElementById('audioPrintSuccess');
    const audioAddToCart = document.getElementById('audioAddToCart'); // New audio element
    const audioRemoveFromCart = document.getElementById('audioRemoveFromCart'); // New audio element
    const audioResetCartSuccess = document.getElementById('audioResetCartSuccess'); // New audio element


    // Function to play audio safely
    function playAudio(audioElement) {
      if (audioElement) {
      audioElement.currentTime = 0; // Rewind to start
      audioElement.play().catch(error => console.warn("Audio play failed:", error));
      }
    }

    // Mengambil nama pemilik dari pengaturan, default ke 'Nama Pemilik' jika kosong
    var kasirName = "{{ $pengaturan->store_owner ?? 'Nama Pemilik' }}";

    // Ambil nilai lastTransactionNumber dari server (misalnya, nilai terakhir dari hari ini)
    var lastTransactionNumber = parseInt("{{ $lastTransactionNumber ?? 0 }}");
    let cartItems = [];

    // Fungsi untuk menghasilkan nomor faktur dengan format:
    // KSW-YYYYMMDD-HHmmss-XXX
    // Misalnya: KSW-20250507-025547-002 (jika lastTransactionNumber sudah 1 sebelumnya)
    function generateNomorFaktur() {
      var now = new Date();
      var year = now.getFullYear();
      var month = String(now.getMonth() + 1).padStart(2, "0");
      var day = String(now.getDate()).padStart(2, "0");
      var hours = String(now.getHours()).padStart(2, "0");
      var minutes = String(now.getMinutes()).padStart(2, "0");
      var seconds = String(now.getSeconds()).padStart(2, "0");
      // Counter invoice adalah lastTransactionNumber + 1
      var currentCount = lastTransactionNumber + 1;
      return "KSW-" + year + month + day + "-" + hours + minutes + seconds + "-" + String(currentCount).padStart(3, '0');
    }
    // Set nomor faktur saat halaman dimuat
    let nomorFaktur = generateNomorFaktur();

    function formatRupiah(value) {
      if (!value) return "";
      let number = parseInt(value);
      if (isNaN(number)) return "";
      return "Rp " + number.toLocaleString("id-ID");
    }

    function formatNumber(value) {
      if (!value) return "";
      let number = parseInt(value);
      if (isNaN(number)) return "";
      return number.toLocaleString("id-ID");
    }

    // Update header tanggal & waktu
function updateHeaderDateTime() {
  const now = new Date();

  const day = String(now.getDate()).padStart(2, "0");
  const month = String(now.getMonth() + 1).padStart(2, "0");
  const year = String(now.getFullYear()).slice(-2);
  const formattedDate = `${day}/${month}/${year}`;

  const hours = String(now.getHours()).padStart(2, "0");
  const minutes = String(now.getMinutes()).padStart(2, "0");
  const seconds = String(now.getSeconds()).padStart(2, "0");
  const formattedTime = `${hours}:${minutes}:${seconds}`;

  const formattedDay = now.toLocaleDateString("id-ID", { weekday: "long" });

  document.getElementById("currentDate").innerText = formattedDate;
  document.getElementById("currentDay").innerText = formattedDay;
  document.getElementById("currentTime").innerText = formattedTime;
}

// Panggil saat load + update tiap detik
updateHeaderDateTime();
setInterval(updateHeaderDateTime, 1000);

    // Update field tanggal transaksi
    function updateTransactionDateField() {
      const now = new Date();
      const day = String(now.getDate()).padStart(2, "0");
      const month = String(now.getMonth() + 1).padStart(2, "0");
      const year = String(now.getFullYear()).slice(-2);
      document.getElementById("transactionDate").value = `${day}/${month}/${year}`;
    }
    updateTransactionDateField();
    setInterval(updateTransactionDateField, 1000);

    // Inisialisasi DataTable untuk keranjang
    var cartTable = $('#cartTable').DataTable({
      pageLength: 5,
      // Added '-1' for "All" entries in lengthMenu
      lengthMenu: [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "All"]],
      searching: true,
      info: true,
      ordering: false,
      autoWidth: false,
      fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
      $('td:eq(0)', nRow).html(iDisplayIndexFull + 1);
      }
    });

    // Fungsi Simpan & Muat Cart (localStorage)
    function saveCart() {
      const itemsToSave = cartItems.map(item => ({
      code: item.code,
      name: item.name,
      quantity: item.quantity,
      unitPrice: item.unitPrice,
      total: item.total
      }));
      localStorage.setItem("cartItems", JSON.stringify(itemsToSave));
    }

    function loadCart() {
      const saved = localStorage.getItem("cartItems");
      if (saved) {
      const items = JSON.parse(saved);
      cartItems = [];
      cartTable.clear().draw();
      items.forEach(item => {
        addToCart(item.name, item.unitPrice, item.quantity, item.code, true, true); // Added isLoadOperation flag
      });
      updateTotal();
      }
    }

    // Fungsi Menambah, Update, Hapus Item di Keranjang
    function addToCart(name, price, quantity = 1, code = null, fromStorage = false, isLoadOperation = false) {
      let existingIndex = cartItems.findIndex(item => item.code === code);
      if (existingIndex !== -1) {
      cartItems[existingIndex].quantity += quantity;
      cartItems[existingIndex].total = cartItems[existingIndex].unitPrice * cartItems[existingIndex].quantity;
      let row = cartItems[existingIndex].row;
      $(row).find("input.quantity-input").val(cartItems[existingIndex].quantity);
      $(row).find("[data-total]").text(formatRupiah(cartItems[existingIndex].total));
      } else {
      const totalPrice = price * quantity;
      var rowNode = cartTable.row.add([
        '',
        name,
        `<input type="number" value="${quantity}" min="1" class="quantity-input w-16 p-1 border border-gray-300 dark:border-[#39455A] rounded-md text-center bg-white text-black dark:bg-[#39455A] dark:text-gray-200" onchange="updateQuantity(this)" />`,
        `<span data-total>${formatRupiah(totalPrice)}</span>`,
        kasirName, // Menampilkan nama pemilik di keranjang
        `<button class="text-red-500 hover:text-red-700 transition duration-300" onclick="removeRow(this)">
       <span class="material-icons text-sm">remove_shopping_cart</span>
       </button>`
      ]).draw().node();

      cartItems.push({
        code: code,
        name: name,
        quantity: quantity,
        unitPrice: price,
        total: totalPrice,
        row: rowNode,
        pemilik: kasirName // Menambahkan pemilik ke item keranjang
      });
      }

      if (!isLoadOperation) { // Only play sound if it's not a cart load operation
      playAudio(audioAddToCart);
      }
      updateTotal();
      if (!fromStorage) saveCart();
    }


    window.updateQuantity = function (input) {
      const newQuantity = parseInt(input.value);
      if (isNaN(newQuantity) || newQuantity < 1) {
      input.value = 1; // Reset to 1 if invalid
      // Optionally, show a message or just silently correct
      }
      const row = input.closest("tr");
      const index = cartItems.findIndex(item => item.row === row);
      if (index !== -1) {
      cartItems[index].quantity = isNaN(newQuantity) || newQuantity < 1 ? 1 : newQuantity;
      cartItems[index].total = cartItems[index].unitPrice * cartItems[index].quantity;
      $(row).find('[data-total]').text(formatRupiah(cartItems[index].total));
      updateTotal();
      saveCart();
      }
    };

    window.removeRow = function (btn) {
      const row = btn.closest("tr");
      const index = cartItems.findIndex(item => item.row === row);
      if (index !== -1) {
      playAudio(audioRemoveFromCart); // Play remove from cart sound
      cartTable.row(row).remove().draw();
      cartItems.splice(index, 1);
      updateTotal();
      saveCart();
      }
    };

    // Fungsi Reset Keranjang
    window.confirmClearCart = function () {
      if (cartItems.length === 0) {
      Swal.fire({
        iconHtml: '<span class="material-icons" style="font-size:48px; color:#D32F2F;">remove_shopping_cart</span>',
        title: 'Keranjang Kosong!',
        text: 'Tidak ada data untuk direset.',
        confirmButtonColor: '#D32F2F'
      });
      return;
      }
      Swal.fire({
      title: 'Apakah kamu yakin?',
      text: "Reset keranjang belanja?",
      iconHtml: '<span class="material-icons" style="color: #d33; font-size: 50px;">restore</span>',
      showCancelButton: true,
      confirmButtonText: 'Ya, reset!',
      cancelButtonText: 'Batal',
      confirmButtonColor: '#2563eb',
      cancelButtonColor: '#d33'
      }).then((result) => {
      if (result.isConfirmed) {
        clearCart();
        playAudio(audioResetCartSuccess); // Play reset cart success sound
        Swal.fire({
        title: 'Berhasil!',
        text: 'Keranjang berhasil direset.',
        icon: 'success',
        confirmButtonColor: '#2563eb',
        timer: 1500,
        showConfirmButton: false
        });
      }
      });
    };

    function clearCart() {
      cartItems = [];
      cartTable.clear().draw();
      localStorage.removeItem("cartItems");
      updateTotal();
      // Clear payment and change as well
      document.getElementById("payment").value = '';
      localStorage.removeItem("paymentValue");
      updateChange(); // This will set change to Rp 0
    }

    // Fungsi Menghitung Total & Kembalian
    function updateTotal() {
      const total = cartItems.reduce((sum, item) => sum + item.total, 0);
      document.getElementById("total").value = formatRupiah(total);
      updateChange();
    }

    function updateChange() {
      const total = cartItems.reduce((sum, item) => sum + item.total, 0);
      const payment = parseInt(document.getElementById("payment").value.replace(/\D/g, "")) || 0;
      const change = payment - total;
      document.getElementById("change").value = change >= 0 ? formatRupiah(change) : formatRupiah(0);
    }

    // Event Input Payment dengan format otomatis saat mengetik
    document.getElementById("payment").addEventListener("input", function (e) {
      var input = this;
      var caretPos = input.selectionStart;
      var originalValue = input.value;
      var rawValue = originalValue.replace(/[^0-9]/g, "");

      if (rawValue === "") {
      input.value = "";
      updateChange();
      localStorage.removeItem("paymentValue");
      return;
      }

      var formatted = formatNumber(rawValue);
      input.value = formatted;

      // Posisi kursor tetap sesuai saat ngetik
      var diff = formatted.length - originalValue.length;
      try { // Add try-catch for older browsers or edge cases
      input.selectionStart = input.selectionEnd = caretPos + diff;
      } catch (ex) {
      // Silently ignore if setting selectionStart fails
      }


      updateChange();
      localStorage.setItem("paymentValue", formatted);
    });

    // Tambahkan Barang ke Keranjang (klik tombol add-to-cart-btn)
    $(document).on('click', '.add-to-cart-btn', function () {
      var row = $(this).closest('tr');
      var name = row.find('[data-name]').attr('data-name');
      var price = parseInt(row.find('[data-price]').attr('data-price'));
      // Get the code from the hidden <td> element
      var code = row.find('[data-code]').attr('data-code');
      addToCart(name, price, 1, code, false, false); // last false indicates not a load operation
    });

    // Fitur Barcode Scanner
    let barcodeBuffer = "";
    let barcodeTimer = null;
    document.addEventListener("keypress", function (e) {
      if (e.target.tagName.toLowerCase() === 'input' && e.target.id !== 'payment' && e.target.type !== 'search') return; // Allow typing in payment and search
      if (e.target.id === 'payment' || (e.target.type === 'search' && $(e.target).closest('#barangTable_filter').length > 0)) { // Specifically allow payment and datatable search
      return;
      }
      // If the event target is any other input, textarea, or select, ignore barcode scanning
      if (['input', 'textarea', 'select'].includes(e.target.tagName.toLowerCase()) && e.target.id !== 'payment' && e.target.type !== 'search') {
      return;
      }


      barcodeBuffer += e.key;
      if (barcodeTimer) clearTimeout(barcodeTimer);
      barcodeTimer = setTimeout(function () {
      if (barcodeBuffer.endsWith("\r") || barcodeBuffer.endsWith("\n") || barcodeBuffer.length > 3) { // Consider typical barcode length or terminator
        processBarcode(barcodeBuffer);
        barcodeBuffer = ""; // Clear buffer after processing attempt
      } else {
        barcodeBuffer = ""; // Clear buffer if not a typical barcode scan end
      }
      }, 100); // Adjusted timing slightly
    });

    function processBarcode(scannedCode) {
      const code = scannedCode.trim();
      if (code) {
      // Search for the row using the data-code attribute, which is still present on the hidden <td>
      var row = $("#barangTableBody").find("tr").filter(function () {
        return $(this).find("[data-code]").attr("data-code") === code;
      }).first();
      if (row.length) {
        // playAudio(audioAddToCart); // Moved to addToCart
        row.find(".add-to-cart-btn").click(); // This will call addToCart which plays the sound
      } else {
        Swal.fire({
        icon: 'error',
        title: 'Produk tidak ditemukan',
        text: 'Barcode tidak cocok dengan produk yang tersedia.',
        confirmButtonColor: '#2563eb'
        });
      }
      }
    }

    // Inisialisasi DataTable untuk Daftar Barang
    var table = $('#barangTable').DataTable({
      deferRender: true,
      pageLength: 5,
      // Added '-1' for "All" entries in lengthMenu
      lengthMenu: [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "All"]],
      // Adjust column visibility for the hidden 'Kode Barang' column
      columnDefs: [
      { targets: 0, visible: false } // Hide the first column (index 0) which is 'Kode Barang'
      ]
    });

    $('#barangTable_filter input[type="search"]').on('keypress', function (e) {
      if (e.which === 13) { // Enter key
      e.preventDefault();
      let filteredRows = table.rows({ search: 'applied' }).nodes(); // Corrected API for filtered rows
      if (filteredRows.length > 0) {
        let firstRow = $(filteredRows[0]);
        firstRow.find('.add-to-cart-btn').click(); // This will call addToCart which plays the sound
        $(this).val(''); // Clear search input
        table.search('').draw(); // Clear DataTable search
      } else {
        Swal.fire({
        icon: 'error',
        title: 'Produk tidak ditemukan',
        text: 'Tidak ada produk yang cocok dengan pencarian Anda.',
        confirmButtonColor: '#2563eb'
        });
      }
      }
    });

    // Load Cart saat halaman dimuat & ambil nilai payment dari localStorage
    loadCart();
    const savedPayment = localStorage.getItem("paymentValue");
    if (savedPayment) {
      document.getElementById("payment").value = savedPayment;
      updateChange();
    }

    // Print and save transaction
    window.printAndSaveTransaction = function () {
      if (cartItems.length === 0) {
      Swal.fire({
        iconHtml: '<span class="material-icons" style="font-size:48px; color:#D32F2F;">remove_shopping_cart</span>',
        title: 'Keranjang Kosong!',
        text: 'Tambahkan barang terlebih dahulu sebelum melakukan transaksi.',
        confirmButtonColor: '#D32F2F'
      });
      return;
      }

      const total = cartItems.reduce((sum, item) => sum + item.total, 0);
      const payment = parseInt(document.getElementById("payment").value.replace(/\D/g, "")) || 0;

      if (payment < total) {
      Swal.fire({
        icon: 'error',
        title: 'Pembayaran Kurang',
        text: 'Jumlah pembayaran kurang dari total belanja.',
        confirmButtonColor: '#D32F2F'
      });
      return;
      }
      playAudio(audioPrint); // Play audio for "Cetak Struk" button click (before validation)

      // Prepare data before clearing cart
      const itemsForTransaction = cartItems.map(item => ({
      code: item.code,
      name: item.name,
      quantity: item.quantity,
      unitPrice: item.unitPrice,
      total: item.total,
      pemilik: item.pemilik // Pastikan properti 'pemilik' disertakan
      }));

      // Store items for printing, as cart will be cleared
      localStorage.setItem("lastTransactionItemsForPrint", JSON.stringify(itemsForTransaction));


      const data = {
      items: itemsForTransaction,
      total: total,
      payment: payment,
      nomor_faktur: nomorFaktur, // Add nomor_faktur to the data being sent
      pemilik: kasirName // Menambahkan nama pemilik ke data transaksi utama
      };

      // Simpan transaksi ke backend tanpa loading dialog
      fetch("{{ route('kasir.simpan') }}", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": "{{ csrf_token() }}"
      },
      body: JSON.stringify(data)
      })
      .then(response => response.json())
      .then(result => {
        if (result.success) {
        playAudio(audioSuccessSavePrint); // Play audio for successful save and print
        const savedNomorFaktur = result.nomor_faktur || nomorFaktur; // Use returned NF if available
        // Update lastTransactionNumber and generate new nomorFaktur for next transaction
        lastTransactionNumber++; // Increment for the next invoice
        nomorFaktur = generateNomorFaktur(); // Generate new invoice number

        clearCart(); // Clears cart, payment, and change

        // Show success message tanpa loading
        Swal.fire({
          iconHtml: '<span class="material-icons" style="font-size:48px; color:#2E7D32;">shopping_cart_checkout</span>',
          title: 'Transaksi Berhasil',
          text: `Data transaksi telah disimpan. No Faktur: ${savedNomorFaktur}`,
          confirmButtonColor: '#2563eb'
        }).then(() => {
          // Lakukan pencetakan struk setelah transaksi disimpan
          cetakStruk(savedNomorFaktur, total, payment, itemsForTransaction); // Pass itemsForTransaction
        });
        } else {
        Swal.fire({
          iconHtml: '<span class="material-icons" style="font-size:48px; color:#F44336;">cancel</span>',
          title: 'Transaksi Gagal!',
          text: 'Gagal menyimpan transaksi: ' + (result.message || 'Unknown error'),
          confirmButtonColor: '#F44336'
        });
        localStorage.removeItem("lastTransactionItemsForPrint"); // Clean up if save failed
        }
      })
      .catch(error => {
        console.error("Error:", error);
        Swal.fire({
        iconHtml: '<span class="material-icons" style="font-size:48px; color:#F44336;">cancel</span>',
        title: 'Error!',
        text: 'Terjadi kesalahan saat menyimpan transaksi.',
        confirmButtonColor: '#F44336'
        });
        localStorage.removeItem("lastTransactionItemsForPrint"); // Clean up on error
      });
    };

    function cetakStruk(nomorFakturUntukCetak, totalUntukCetak, pembayaranUntukCetak, itemsToPrint) {
      let now = new Date();
      let printDate = now.toLocaleDateString("en-CA"); //YYYY-MM-DD
      let printTime = now.toLocaleTimeString("en-GB", { hour: "2-digit", minute: "2-digit" }); // HH:MM

      // Use itemsToPrint passed to the function
      const itemsPayload = itemsToPrint.map(item => ({
      name: item.name,
      quantity: item.quantity,
      total: item.total,
      pemilik: item.pemilik // Memastikan pemilik juga ada di payload cetak struk
      }));

      fetchWithTimeout("{{ route('admin.print.struk') }}", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": "{{ csrf_token() }}",
      },
      body: JSON.stringify({
        nomor_faktur: nomorFakturUntukCetak,
        tanggal: printDate,
        waktu: printTime,
        total: totalUntukCetak,
        bayar: pembayaranUntukCetak,
        kembalian: pembayaranUntukCetak - totalUntukCetak,
        nama_kasir: kasirName,
        items: itemsPayload
      })
      }, 2000) // Timeout 2 detik
      .then(response => response.json())
      .then(data => {
        if (data.status === 'success') {
        playAudio(audioPrintSuccess); // Play audio for successful print
        Swal.fire({
          iconHtml: '<span class="material-icons" style="font-size:48px; color:#2E7D32;">print</span>',
          title: 'Struk Berhasil Dicetak',
          text: 'Struk telah dikirim ke printer thermal.',
          confirmButtonColor: '#2563eb'
        });
        } else {
        Swal.fire({
          iconHtml: '<span class="material-icons" style="font-size:48px; color:#FFA000;">print_disabled</span>',
          title: 'Transaksi Tersimpan',
          text: data.message || 'Printer tidak terdeteksi atau gagal mencetak. Struk tidak dicetak.',
          confirmButtonColor: '#FFA000'
        });
        }
      })
      .catch(error => {
        console.warn("Gagal cetak struk:", error);
        Swal.fire({
        iconHtml: '<span class="material-icons" style="font-size:48px; color:#FFA000;">print_disabled</span>',
        title: 'Printer Tidak Merespons',
        text: error.message === "Timeout"
          ? 'Printer tidak merespons dalam 2 detik. Pastikan printer menyala dan terhubung.'
          : 'Terjadi kesalahan saat proses cetak. Periksa koneksi printer.',
        confirmButtonColor: '#FFA000'
        });
      })
      .finally(() => {
        localStorage.removeItem("lastTransactionItemsForPrint"); // Clean up the temporary item storage
      });
    }

    // Fetch with timeout function
    function fetchWithTimeout(resource, options = {}, timeout = 5000) {
      const controller = new AbortController();
      const id = setTimeout(() => controller.abort(), timeout);

      return fetch(resource, {
      ...options,
      signal: controller.signal
      })
      .then(response => {
        clearTimeout(id);
        return response;
      })
      .catch(error => {
        clearTimeout(id);
        if (error.name === 'AbortError') {
        throw new Error('Timeout');
        }
        throw error;
      });
    }


    // Preview receipt
    // Preview receipt
    window.previewReceipt = function () {
      playAudio(audioPreview); // Play audio for "Preview Struk" button click

      if (cartItems.length === 0) {
      Swal.fire({
        icon: 'error',
        title: 'Keranjang Kosong',
        text: 'Tidak ada item untuk dipreview',
        confirmButtonColor: '#D32F2F'
      });
      return;
      }

      const total = cartItems.reduce((sum, item) => sum + item.total, 0);
      const payment = parseInt(document.getElementById("payment").value.replace(/\D/g, "")) || 0;
      const change = payment - total;

      const now = new Date();
      const printDate = now.toLocaleDateString("en-CA");
      const printTime = now.toLocaleTimeString("en-GB", { hour: "2-digit", minute: "2-digit" });

      const items = cartItems.map(item => ({
      name: item.name,
      quantity: item.quantity,
      total: item.total,
      modal: item.modal || 0, // Assuming modal and kode_barang might not always be present in cartItems
      kode_barang: item.code || "N/A", // Use item.code as it's defined when adding to cart
      pemilik: item.pemilik // Memastikan pemilik juga ada di payload preview struk
      }));

      const formData = new FormData();
      if (nomorFaktur) formData.append("nomor_faktur", nomorFaktur);
      formData.append("tanggal", printDate);
      formData.append("waktu", printTime);
      formData.append("total", total);
      formData.append("bayar", payment);
      formData.append("kembalian", change > 0 ? change : 0);
      formData.append("items", JSON.stringify(items));
      formData.append("_token", "{{ csrf_token() }}");
      formData.append("pemilik", kasirName); // Menambahkan nama pemilik ke formData untuk preview

      fetch("{{ route('admin.preview.struk') }}", {
      method: "POST",
      body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.html) {
        Swal.fire({
          title: "Preview Struk",
          html: `<div id="preview-content" style="font-family: monospace; font-size: 13px;">${data.html}</div>
        <label style="margin-top: 15px; display: block; font-weight: bold;">Kirim ke WhatsApp</label>
        <div style="display: flex; align-items: center; margin-top: 5px;">
        <span style="padding: 10px; background-color: #f0f0f0; border: 1px solid #ccc; border-radius: 5px 0 0 5px; font-weight: bold;">+62</span>
        <input id="wa-number" placeholder="81234567890" style="flex: 1; padding: 10px; border: 1px solid #ccc; border-left: none; border-radius: 0 5px 5px 0;" />
        </div>
     <button id="send-wa-btn" style="margin-top: 15px; background-color: #25D366; color: white; border: none; padding: 10px 20px; border-radius: 5px; font-weight: bold; cursor: pointer; width: 100%; display: flex; align-items: center; justify-content: center;">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="20" height="20" fill="white" style="margin-right: 8px;">
      <path d="M16 0C7.163 0 0 7.163 0 16c0 2.82.738 5.527 2.143 7.931L0 32l8.309-2.16A15.897 15.897 0 0016 32c8.837 0 16-7.163 16-16S24.837 0 16 0zm0 29.565a13.5 13.5 0 01-6.872-1.864l-.492-.287-4.934 1.282 1.31-4.816-.319-.498A13.474 13.474 0 012.435 16c0-7.463 6.069-13.531 13.531-13.531S29.497 8.537 29.497 16 23.429 29.565 16 29.565zm7.594-10.99c-.403-.201-2.38-1.173-2.748-1.307-.368-.135-.636-.201-.904.202-.269.403-1.037 1.306-1.27 1.574-.233.269-.468.303-.87.101-.403-.202-1.704-.627-3.246-2.001-1.2-1.07-2.008-2.387-2.242-2.79-.233-.403-.025-.621.177-.822.182-.181.403-.47.604-.705.201-.234.268-.403.403-.67.135-.269.067-.505-.034-.706-.101-.202-.904-2.185-1.238-2.991-.326-.785-.657-.678-.904-.678h-.775c-.234 0-.603.086-.918.403-.315.316-1.203 1.177-1.203 2.872 0 1.694 1.233 3.33 1.404 3.561.168.233 2.43 3.71 5.89 5.201 3.46 1.49 3.46.993 4.082.933.623-.059 2.006-.817 2.29-1.606.284-.789.284-1.467.201-1.606-.084-.135-.302-.217-.705-.403z"/>
    </svg>
    Kirim Struk ke WhatsApp
  </button>
  `,
          width: 500,
          showConfirmButton: false,
          showCloseButton: true,
          didOpen: () => {
          document.getElementById("send-wa-btn").addEventListener("click", () => {
            playAudio(audioSendWhatsApp); // Play audio for "Kirim ke WhatsApp" button click

            const numberInput = document.getElementById("wa-number").value.trim();
            const number = "62" + numberInput.replace(/^0+/, "");

            if (!/^62\d{9,}$/.test(number)) {
            Swal.showValidationMessage("Masukkan nomor WhatsApp yang valid (ex: 8123xxxxxx)");
            return;
            }

            const previewEl = document.getElementById("preview-content");
            const wrapper = document.createElement("div");
            wrapper.style.border = "2px solid black";
            wrapper.style.padding = "10px";
            wrapper.style.display = "inline-block";
            wrapper.style.backgroundColor = "white";
            wrapper.appendChild(previewEl.cloneNode(true));
            document.body.appendChild(wrapper);

            html2canvas(wrapper, { scale: 3, useCORS: true }).then(canvas => {
            document.body.removeChild(wrapper);

            canvas.toBlob(blob => {
              if (!blob) {
              Swal.fire("Gagal", "Gagal membuat gambar dari canvas.", "error");
              return;
              }

              const formDataImg = new FormData();
              formDataImg.append("image", blob, "struk.png");
              formDataImg.append("nomor", number);
              formDataImg.append("tanggal", printDate);
              formDataImg.append("waktu", printTime);
              formDataImg.append("items", JSON.stringify(items)); // items from the previewReceipt scope
              if (nomorFaktur) formDataImg.append("nomor_faktur", nomorFaktur);
              formDataImg.append("_token", "{{ csrf_token() }}");
              formDataImg.append("pemilik", kasirName); // Menambahkan nama pemilik ke formData untuk upload

              fetch("{{ route('admin.struk.upload') }}", {
              method: "POST",
              body: formDataImg
              })
              .then(res => res.json())
              .then(response => {
                console.log("Response dari upload:", response);

                if (response.image_url) {
                const waMessage = `Berikut struk pembelian Anda:\n\n${response.image_url}`;
                const waLink = `https://wa.me/${number}?text=${encodeURIComponent(waMessage)}`;

                console.log("WA Message:", waMessage);
                window.location.href = waLink;

                Swal.fire("Berhasil!", "Struk berhasil dikirim ke WhatsApp.", "success");
                } else {
                Swal.fire("Gagal", "Struk gagal dikirim (URL kosong).", "error");
                }
              })
              .catch(err => {
                console.error("Upload error:", err);
                Swal.fire("Error", "Terjadi kesalahan saat upload gambar.", "error");
              });
            }, "image/png");
            });
          });
          }
        });
        } else {
        Swal.fire({
          icon: 'warning',
          title: 'Gagal!',
          text: 'Isi terlebih dahulu pengaturan baru bisa tampilkan struk.',
          confirmButtonText: 'OK',
          confirmButtonColor: '#2563EB'
        });
        }
      })
      .catch(err => {
        console.error("Preview error:", err);
        Swal.fire("Error!", "Terjadi kesalahan saat memuat struk.", "error");
      });
    };

    });
  </script>
@endpush