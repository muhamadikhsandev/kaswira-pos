@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')
<header class="mb-10">
  <h1 class="text-3xl sm:text-4xl font-bold text-gray-800 dark:text-gray-100 mb-4 sm:mb-6">
    Laporan Penjualan
  </h1>
  <p class="text-gray-600 dark:text-gray-300 text-base sm:text-lg">
    Laporan penjualan harian, mingguan, dan bulanan. Gunakan tab di bawah untuk memilih jenis laporan.
  </p>
</header>

<div class="mb-10">
  <ul class="flex items-center border-b border-gray-300 dark:border-gray-600">
    <li class="mr-4">
      <a id="tab-hari"
         href="#"
         class="inline-block py-3 px-6 font-semibold text-blue-600 bg-white dark:bg-white
                border-l border-t border-r border-gray-300 dark:border-gray-600 rounded-t
                hover:bg-gray-50 dark:hover:bg-gray-100 transition">
        Per Hari
      </a>
    </li>
    <li class="mr-4">
      <a id="tab-minggu"
         href="#"
         class="inline-block py-3 px-6 font-semibold text-blue-600 bg-white dark:bg-white
                border-l border-t border-r border-gray-300 dark:border-gray-600 rounded-t
                hover:bg-gray-50 dark:hover:bg-gray-100 transition">
        Per Minggu
      </a>
    </li>
    <li>
      <a id="tab-bulan"
         href="#"
         class="inline-block py-3 px-6 font-semibold text-blue-600 bg-white dark:bg-white
                border-l border-t border-r border-gray-300 dark:border-gray-600 rounded-t
                hover:bg-gray-50 dark:hover:bg-gray-100 transition">
        Per Bulan
      </a>
    </li>
  </ul>
</div>

{{-- Form: Laporan Per Hari --}}
<section id="form-hari" class="mb-10">
  <div class="bg-white dark:bg-gray-800 p-8 rounded shadow-lg space-y-6">
    <h2 class="text-xl sm:text-2xl font-medium text-gray-800 dark:text-gray-100">Laporan Per Hari</h2>
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6">
      <div class="flex flex-col">
        <label for="hari" class="mb-2 font-medium text-base text-gray-700 dark:text-gray-200">Pilih Hari</label>
        <input type="date" id="hari"
               class="border rounded-lg p-3 bg-white dark:bg-gray-700
                      text-gray-800 dark:text-gray-100 focus:outline-none
                      focus:ring-2 focus:ring-blue-500" />
      </div>
      <div class="flex flex-col sm:items-start gap-4">
        <label class="invisible mb-1">Aksi</label>
        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
          <button id="refreshDataHari"
                  class="bg-gray-600 text-white py-3 px-6 rounded-lg hover:bg-gray-700
                         flex items-center transition focus:outline-none
                         focus:ring-2 focus:ring-gray-400">
            <span class="material-icons mr-2">refresh</span> Refresh
          </button>
          <button id="filterHari"
                  class="bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700
                         flex items-center transition focus:outline-none
                         focus:ring-2 focus:ring-blue-400">
            <span class="material-icons mr-2">filter_list</span> Filter
          </button>
          <button id="exportExcelHari"
                  class="bg-green-600 text-white py-3 px-6 rounded-lg hover:bg-green-700
                         flex items-center transition focus:outline-none
                         focus:ring-2 focus:ring-green-400">
            <span class="material-icons mr-2">download</span> Export Excel
          </button>
          <button id="exportPdfHari"
                  class="bg-red-600 text-white py-3 px-6 rounded-lg hover:bg-red-700
                         flex items-center transition focus:outline-none
                         focus:ring-2 focus:ring-red-400">
            <span class="material-icons mr-2">picture_as_pdf</span> Export PDF
          </button>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- Form: Laporan Per Minggu --}}
<section id="form-minggu" class="mb-10 hidden">
  <div class="bg-white dark:bg-gray-800 p-8 rounded shadow-lg space-y-6">
    <h2 class="text-xl sm:text-2xl font-medium text-gray-800 dark:text-gray-100">Laporan Per Minggu</h2>
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6">
      <div class="flex flex-col">
        <label for="mingguInput" class="mb-2 font-medium text-base text-gray-700 dark:text-gray-200">Pilih Minggu</label>
        <input type="week" id="mingguInput"
               class="border rounded-lg p-3 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100
                      focus:outline-none focus:ring-2 focus:ring-blue-500" />
      </div>
      <div class="flex flex-col sm:items-start gap-4">
        <label class="invisible mb-1">Aksi</label>
        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
          <button id="refreshDataMinggu"
                  class="bg-gray-600 text-white py-3 px-6 rounded-lg hover:bg-gray-700
                         flex items-center transition focus:outline-none
                         focus:ring-2 focus:ring-gray-400">
            <span class="material-icons mr-2">refresh</span> Refresh
          </button>
          <button id="filterMinggu"
                  class="bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700
                         flex items-center transition focus:outline-none
                         focus:ring-2 focus:ring-blue-400">
            <span class="material-icons mr-2">filter_list</span> Filter
          </button>
          <button id="exportExcelMinggu"
                  class="bg-green-600 text-white py-3 px-6 rounded-lg hover:bg-green-700
                         flex items-center transition focus:outline-none
                         focus:ring-2 focus:ring-green-400">
            <span class="material-icons mr-2">download</span> Export Excel
          </button>
          <button id="exportPdfMinggu"
                  class="bg-red-600 text-white py-3 px-6 rounded-lg hover:bg-red-700
                         flex items-center transition focus:outline-none
                         focus:ring-2 focus:ring-red-400">
            <span class="material-icons mr-2">picture_as_pdf</span> Export PDF
          </button>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- Form: Laporan Per Bulan --}}
<section id="form-bulan" class="mb-10 hidden">
  <div class="bg-white dark:bg-gray-800 p-8 rounded shadow-lg space-y-6">
    <h2 class="text-xl sm:text-2xl font-medium text-gray-800 dark:text-gray-100">Laporan Per Bulan</h2>
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6">
      <div class="flex flex-col">
        <label for="bulanInput" class="mb-2 font-medium text-base text-gray-700 dark:text-gray-200">Pilih Bulan</label>
        <input type="month" id="bulanInput"
               class="border rounded-lg p-3 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100
                      focus:outline-none focus:ring-2 focus:ring-blue-500" />
      </div>
      <div class="flex flex-col sm:items-start gap-4">
        <label class="invisible mb-1">Aksi</label>
        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
          <button id="refreshDataBulan"
                  class="bg-gray-600 text-white py-3 px-6 rounded-lg hover:bg-gray-700
                         flex items-center transition focus:outline-none
                         focus:ring-2 focus:ring-gray-400">
            <span class="material-icons mr-2">refresh</span> Refresh
          </button>
          <button id="filterBulan"
                  class="bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700
                         flex items-center transition focus:outline-none
                         focus:ring-2 focus:ring-blue-400">
            <span class="material-icons mr-2">filter_list</span> Filter
          </button>
          <button id="exportExcelBulan"
                  class="bg-green-600 text-white py-3 px-6 rounded-lg hover:bg-green-700
                         flex items-center transition focus:outline-none
                         focus:ring-2 focus:ring-green-400">
            <span class="material-icons mr-2">download</span> Export Excel
          </button>
          <button id="exportPdfBulan"
                  class="bg-red-600 text-white py-3 px-6 rounded-lg hover:bg-red-700
                         flex items-center transition focus:outline-none
                         focus:ring-2 focus:ring-red-400">
            <span class="material-icons mr-2">picture_as_pdf</span> Export PDF
          </button>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- Tabel Laporan --}}
<section>
  <div class="bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-lg shadow-lg">
    <div class="overflow-x-auto">
      <table id="laporanTable"
             class="min-w-full bg-white dark:bg-gray-800 shadow rounded-lg
                    border border-gray-200 dark:border-gray-700 text-sm">
        <thead class="bg-blue-600 text-white">
          <tr>
            <th class="py-3 px-4 text-left uppercase">No</th>
            <th class="py-3 px-4 text-left uppercase">Kode Barang</th>
            <th class="py-3 px-4 text-left uppercase">Nama Barang</th>
            <th class="py-3 px-4 text-right uppercase">Jumlah</th>
            <th class="py-3 px-4 text-right uppercase">Modal</th>
            <th class="py-3 px-4 text-right uppercase">Total</th>
            <th class="py-3 px-4 text-left uppercase">Pemilik</th>
            <th class="py-3 px-4 text-center uppercase">Tanggal Transaksi</th>
            <th class="py-3 px-4 text-left uppercase">Nomor Faktur</th>
          </tr>
        </thead>
        <tbody id="laporanBody" class="divide-y divide-gray-200 dark:divide-gray-700">
          @foreach($laporans as $index => $laporan)
          <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
            <td class="py-3 px-4">{{ $index + 1 }}</td>
            <td class="py-3 px-4">{{ $laporan->kode_barang }}</td>
            <td class="py-3 px-4">{{ $laporan->nama_barang }}</td>
            <td class="py-3 px-4 text-right">{{ $laporan->jumlah }}</td>
            <td class="py-3 px-4 text-right">
              Rp {{ number_format($laporan->modal * $laporan->jumlah, 0, ',', '.') }}
            </td>
            <td class="py-3 px-4 text-right">
              Rp {{ number_format($laporan->total, 0, ',', '.') }}
            </td>
            <td class="py-3 px-4">{{ $laporan->kasir }}</td>
            <td class="py-3 px-4 text-center">
              {{ \Carbon\Carbon::parse($laporan->transaction_date)->format('d/m/Y H:i') }}
            </td>
            <td class="py-3 px-4">{{ $laporan->nomor_faktur }}</td>
          </tr>
          @endforeach
        </tbody>
        <tfoot>
          <tr class="border-t border-gray-200 dark:border-gray-600">
            <td colspan="4"
                class="py-4 px-6 text-right text-sm font-medium
                       text-gray-600 dark:text-gray-300">
              Total Terjual:
            </td>
            <td id="footerTotalTerjual"
                class="py-4 px-6 text-right text-lg font-bold
                       text-gray-800 dark:text-gray-100">
              {{ number_format($totalTerjual, 0, ',', '.') }}
            </td>
            <td colspan="4"></td>
          </tr>
          <tr class="border-t border-gray-200 dark:border-gray-600">
            <td colspan="5"
                class="py-4 px-6 text-right text-sm font-semibold
                       text-gray-600 dark:text-gray-300">
              Total Transaksi:
            </td>
            <td id="footerTotalTransaksi" colspan="4"
                class="py-4 px-6 text-right text-lg font-bold
                       text-green-700 dark:text-green-400">
              Rp {{ number_format($laporans->sum('total'), 0, ',', '.') }}
            </td>
          </tr>
          @php
            $isMinus = $totalKeuntungan < 0;
            $keuntunganClass = $isMinus
                               ? 'text-red-600 dark:text-red-400'
                               : 'text-blue-700 dark:text-blue-400';
          @endphp
          <tr class="border-t border-gray-200 dark:border-gray-600">
            <td colspan="7"
                class="py-4 px-6 text-right text-sm font-semibold
                       text-gray-600 dark:text-gray-300">
              Keuntungan:
            </td>
            <td colspan="2" id="totalKeuntungan"
                class="py-4 px-6 text-right text-lg font-bold {{ $keuntunganClass }}">
              Rp {{ number_format($totalKeuntungan, 0, ',', '.') }}
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</section>

{{-- Audio Element for Success Sound --}}
<audio id="successSound" src="{{ asset('sounds/success.mp3') }}" preload="auto"></audio>

@endsection

@push('scripts')
<script>
  const csrf = '{{ csrf_token() }}';
  const initialData = {
    laporans: @json($laporans),
    totalTerjual: {{ $totalTerjual }},
    totalTransaksi: {{ $laporans->sum('total') }},
    totalKeuntungan: {{ $totalKeuntungan }},
    keuntunganClass: '{{ $keuntunganClass }}'
  };

  // Get the audio element
  const successAudio = document.getElementById('successSound');

  function playSuccessSound() {
    if (successAudio) {
      successAudio.currentTime = 0; // Rewind to the start
      successAudio.play().catch(error => console.error("Error playing success sound:", error));
    }
  }

  function showToast(icon, title) {
    Swal.fire({
      toast: true,
      icon,
      title,
      position: 'top-end',
      showConfirmButton: false,
      timer: 2000,
      timerProgressBar: true
    });
    if (icon === 'success') {
      playSuccessSound();
    }
  }

  function formatCurrency(value) {
    if (isNaN(value) || value === null || value === undefined) return 'Rp 0';
    return 'Rp ' + parseInt(value).toLocaleString('id-ID');
  }

  function calculateModal(row) {
    const hargaModal = row.harga_modal || row.modal || 0;
    return hargaModal * (row.jumlah || 1);
  }

  function calculateTotal(row) {
    const hargaJual = row.harga_jual || row.total || 0;
    return hargaJual * (row.jumlah || 1);
  }

  let dataTable;

$(document).ready(function() {
  dataTable = $('#laporanTable').DataTable({
    pageLength: 5,
    lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
    language: { url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/Indonesian.json" },
    columns: [
      {
        data: null,
        orderable: false,
        searchable: false,
        render: function(data, type, row, meta) {
          return meta.row + meta.settings._iDisplayStart + 1;
        }
      },
      { data: 'kode_barang' },
      { data: 'nama_barang' },
      {
        data: 'jumlah',
        className: 'text-right',
        render: function(data) {
          return data || 0;
        }
      },
      {
        className: 'text-right',
        render: function(data, type, row) {
          return formatCurrency(calculateModal(row));
        }
      },
      {
        className: 'text-right',
        render: function(data, type, row) {
          return formatCurrency(calculateTotal(row));
        }
      },
      { data: 'kasir' },
      {
        data: 'transaction_date',
        className: 'text-center',
        render: function(data) {
          if (!data) return '-';
          const date = new Date(data);
          if (isNaN(date.getTime())) return '-';
          const formattedDate = date.toLocaleDateString('id-ID', {
            day: '2-digit', month: '2-digit', year: 'numeric'
          });
          const formattedTime = date.toLocaleTimeString('id-ID', {
            hour: '2-digit', minute: '2-digit', hour12: false
          });
          return `${formattedDate} ${formattedTime}`;
        }
      },
      { data: 'nomor_faktur' }
    ]
  });
});

  function updateTable(data) {
    if ($.fn.DataTable.isDataTable('#laporanTable')) {
      dataTable.clear();
      dataTable.rows.add(data.laporans);
      dataTable.draw();
    } else {
      // This else block might not be strictly necessary if DataTable is always initialized,
      // but kept for robustness if there's a scenario where it's not.
      const body = $('#laporanBody').empty();
      if (!data.laporans || data.laporans.length === 0) {
        body.append(`<tr><td colspan="9" class="py-4 text-center text-gray-500">
          Tidak ada data yang ditemukan
        </td></tr>`); // colspan updated to 9
      } else {
          // Fallback rendering if DataTable fails, unlikely if initialized correctly
          data.laporans.forEach((row, index) => {
              // Manual row creation - ideally handled by DataTable
          });
      }
    }

    $('#footerTotalTerjual').text((data.totalTerjual || 0).toLocaleString('id-ID'));
    $('#footerTotalTransaksi').text(formatCurrency(data.totalTransaksi || 0));

    const isMinus = (data.totalKeuntungan || 0) < 0;
    const newClass = isMinus
                     ? 'text-red-600 dark:text-red-400'
                     : 'text-blue-700 dark:text-blue-400';
    $('#totalKeuntungan')
      .text(formatCurrency(data.totalKeuntungan || 0))
      .removeClass('text-red-600 text-blue-700 dark:text-red-400 dark:text-blue-400')
      .addClass(newClass);
  }

  function refreshToInitialData() {
    updateTable(initialData);
    showToast('success', 'Data berhasil di-refresh ke tampilan awal');
    // Sound will be played by showToast
  }

  // Tab Navigation
  const tabs = {
    hari: $('#tab-hari'),
    minggu: $('#tab-minggu'),
    bulan: $('#tab-bulan')
  };

  const forms = {
    hari: $('#form-hari'),
    minggu: $('#form-minggu'),
    bulan: $('#form-bulan')
  };

  function resetTabs() {
    Object.values(tabs).forEach($tab => {
      $tab.removeClass('border-b-2 border-blue-600 bg-gray-50 dark:bg-gray-700 text-blue-700 dark:text-blue-300 font-bold')
          .addClass('bg-white dark:bg-gray-800 text-blue-600 dark:text-blue-400 font-semibold');
    });
  }

  function setActive(key) {
    resetTabs();
    tabs[key].removeClass('bg-white dark:bg-gray-800 text-blue-600 dark:text-blue-400 font-semibold')
             .addClass('border-b-2 border-blue-600 bg-gray-50 dark:bg-gray-700 text-blue-700 dark:text-blue-300 font-bold');
    Object.entries(forms).forEach(([k, $f]) => k === key ? $f.removeClass('hidden') : $f.addClass('hidden'));
  }

  // Initialize tabs
  tabs.hari.click(e => { e.preventDefault(); setActive('hari'); });
  tabs.minggu.click(e => { e.preventDefault(); setActive('minggu'); });
  tabs.bulan.click(e => { e.preventDefault(); setActive('bulan'); });
  setActive('hari'); // Default active tab

  // Export functions
  function getFilenameFromDisposition(header) {
    const m = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/.exec(header);
    return m && m[1] ? m[1].replace(/['"]/g, '') : 'laporan_penjualan';
  }

  function setupExport(paramName, inputId, excelBtnId, pdfBtnId) {
    // Export Excel
    $(`#${excelBtnId}`).click(() => {
      const val = $(`#${inputId}`).val();
      if (!val) return showToast('error', `Silakan pilih ${paramName}!`);

      let url = `{{ route('laporan.exportExcel') }}`;
      if (paramName === 'minggu') { // Assuming 'minggu' is the correct paramName for week input
        url += `?minggu=${val}`;
      } else {
        url += `?${paramName}=${val}`;
      }

      fetch(url)
        .then(response => {
          if (!response.ok) throw new Error('Export failed');
          return response.blob().then(blob => ({
            blob,
            filename: getFilenameFromDisposition(response.headers.get('content-disposition'))
          }));
        })
        .then(({ blob, filename }) => {
          const downloadUrl = URL.createObjectURL(blob);
          const a = document.createElement('a');
          a.href = downloadUrl;
          a.download = filename;
          document.body.appendChild(a);
          a.click();
          a.remove();
          URL.revokeObjectURL(downloadUrl);
          showToast('success', 'File Excel berhasil diunduh!');
          // Sound will be played by showToast
        })
        .catch(error => {
          console.error('Export error:', error);
          showToast('error', 'Terjadi kesalahan saat export Excel');
        });
    });

    // Export PDF
    $(`#${pdfBtnId}`).click(() => {
      const val = $(`#${inputId}`).val();
      if (!val) return showToast('error', `Silakan pilih ${paramName}!`);

      let url = `{{ route('laporan.exportPdf') }}`;
      if (paramName === 'minggu') { // Assuming 'minggu' is the correct paramName for week input
        url += `?minggu=${val}`;
      } else {
        url += `?${paramName}=${val}`;
      }

      fetch(url)
        .then(response => {
          if (!response.ok) throw new Error('Export failed');
          return response.blob().then(blob => ({
            blob,
            filename: getFilenameFromDisposition(response.headers.get('content-disposition'))
          }));
        })
        .then(({ blob, filename }) => {
          const downloadUrl = URL.createObjectURL(blob);
          const a = document.createElement('a');
          a.href = downloadUrl;
          a.download = filename;
          document.body.appendChild(a);
          a.click();
          a.remove();
          URL.revokeObjectURL(downloadUrl);
          showToast('success', 'File PDF berhasil diunduh!');
          // Sound will be played by showToast
        })
        .catch(error => {
          console.error('Export error:', error);
          showToast('error', 'Terjadi kesalahan saat export PDF');
        });
    });
  }

  // Filter functions
  function setupFilter(paramName, inputId, filterBtnId) {
    $(`#${filterBtnId}`).click(() => {
      const val = $(`#${inputId}`).val();
      if (!val) return showToast('error', `Silakan pilih ${paramName}!`);

      // For week input, ensure the value is correctly formatted if needed by the backend
      let queryValue = val;
      if (paramName === 'minggu') { // Assuming 'minggu' is the correct paramName for week input
         // Potentially reformat 'val' if backend expects a different week format
         // Example: val might be "2024-W20". Adjust if backend needs "2024-20" or similar.
      }


      fetch(`{{ route('laporan.filter') }}?${paramName}=${encodeURIComponent(queryValue)}`)
        .then(async response => {
          if (!response.ok) {
            const error = await response.json().catch(() => ({}));
            throw new Error(error.message || `Gagal memuat data ${paramName}`);
          }
          return response.json();
        })
        .then(data => {
          updateTable(data);
          showToast('success', `Laporan ${paramName} berhasil difilter!`);
          // Sound will be played by showToast
        })
        .catch(error => {
          console.error('Filter error:', error);
          showToast('error', error.message || 'Terjadi kesalahan saat memfilter data');
        });
    });
  }

  // Initialize events
  $(document).ready(function() {
    // Refresh buttons
    $('#refreshDataHari, #refreshDataMinggu, #refreshDataBulan').click(function(e) {
      e.preventDefault();
      refreshToInitialData();
    });

    // Setup exports
    // Note: The paramName for 'hari' input was 'tanggal' in the original script.
    // Using 'hari' for consistency with ID if backend expects 'hari', or 'tanggal' if that's the expected query param.
    setupExport('hari', 'hari', 'exportExcelHari', 'exportPdfHari'); // Assuming paramName 'hari' for date
    setupExport('minggu', 'mingguInput', 'exportExcelMinggu', 'exportPdfMinggu'); // paramName 'minggu' for week
    setupExport('bulan', 'bulanInput', 'exportExcelBulan', 'exportPdfBulan');   // paramName 'bulan' for month

    // Setup filters
    // Using 'hari' as paramName for date input, adjust if backend expects 'tanggal'
    setupFilter('hari', 'hari', 'filterHari');
    setupFilter('minggu', 'mingguInput', 'filterMinggu');
    setupFilter('bulan', 'bulanInput', 'filterBulan');


    // Initial data load
    if (initialData && initialData.laporans) {
        updateTable(initialData);
    } else {
        console.error("Initial data is not available or in the expected format.");
        // Optionally, display a message to the user or load default empty state
        updateTable({ laporans: [], totalTerjual: 0, totalTransaksi: 0, totalKeuntungan: 0 });
    }
  });
</script>
@endpush