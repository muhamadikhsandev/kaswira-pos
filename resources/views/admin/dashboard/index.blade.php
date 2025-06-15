@extends('layouts.app')

@section('title', 'Halaman Dashboard')

@section('content')
<!-- Pembungkus PJAX -->
<div id="pjax-container">
    <section id="dashboard" class="mb-12">
        <h2 class="text-3xl font-semibold text-gray-800 dark:text-gray-100 mb-8">Dashboard</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @php
                // Menyusun data card. Untuk data uang, simpan nilai numeriknya,
                // lalu tandai dengan is_money true agar prefix "Rp" ditambahkan di tampilan.
                $cards = [
                    ['icon' => 'shopping_cart', 'url' => 'admin/dashboard/stokbarang',       'label' => 'Barang Terdaftar',    'value' => $data['namaBarang'],       'is_money' => false],
                    ['icon' => 'bar_chart',     'url' => 'admin/dashboard/laporanpenjualan', 'label' => 'Total Laporan',       'value' => $data['totalLaporan'],     'is_money' => false],
                    ['icon' => 'inventory',     'url' => 'admin/dashboard/stokbarang',       'label' => 'Total Stok',          'value' => $data['stokBarang'],       'is_money' => false],
                    ['icon' => 'shopping_bag',  'url' => 'admin/dashboard/laporanpenjualan', 'label' => 'Total Terjual',       'value' => $data['barangterjual'],    'is_money' => false],
                    ['icon' => 'category',      'url' => 'admin/dashboard/kategori',         'label' => 'Kategori Terdaftar',  'value' => $data['categoryBarang'],   'is_money' => false],
                    ['icon' => 'straighten',    'url' => 'admin/dashboard/satuan',           'label' => 'Satuan Terdaftar',    'value' => $data['totalSatuan'],      'is_money' => false],
                    ['icon' => 'attach_money',  'url' => 'admin/dashboard/laporanpenjualan', 'label' => 'Total Transaksi',     'value' => $data['laporanhasil'],     'is_money' => true],
                    ['icon' => 'trending_up',   'url' => 'admin/dashboard/laporanpenjualan', 'label' => 'Keuntungan',          'value' => $data['totalKeuntungan'],  'is_money' => true],
                ];
            @endphp

            @foreach($cards as $card)
                <div class="card bg-white dark:bg-gray-800 shadow rounded-lg">
                    <div class="p-6 flex flex-col items-start">
                        <span class="material-icons card-icon text-blue-600 dark:text-blue-400">{{ $card['icon'] }}</span>
                        <a href="{{ url($card['url']) }}" data-pjax class="mt-4 text-blue-600 dark:text-white text-lg font-semibold">
                            {{ $card['label'] }}
                        </a>
                        <p class="mt-2 text-2xl font-bold text-black dark:text-white">
                            @if(is_numeric($card['value']))
                                {!! $card['is_money'] ? 'Rp ' . number_format($card['value'], 0, ',', '.') : number_format($card['value'], 0, ',', '.') !!}
                            @else
                                {{ $card['value'] }}
                            @endif
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section id="produk-favorit" class="mb-12">
        <div class="card bg-white dark:bg-gray-800 shadow rounded-lg p-6 w-full">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100">Produk Favorit Terlaris</h3>
            </div>
            <div class="space-y-4">
                @foreach($produkFavorit as $produk)
                    @php
                        $persentase = round(($produk->total_terjual / $maxJumlah) * 100);
                    @endphp
                    <div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700 dark:text-gray-300 font-medium">{{ $produk->nama_barang }}</span>
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ $produk->total_terjual }} terjual</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 h-2 rounded mt-1">
                            <div class="h-2 bg-blue-500 rounded" style="width: {{ $persentase }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="chart-section" class="mt-12">
        <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100 mb-4">Diagram Penjualan</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Per Hari -->
            <div class="card p-4 bg-white dark:bg-gray-800 shadow rounded">
                <h3 class="text-lg font-semibold mb-2">Penjualan Per Hari</h3>
                <canvas id="salesPerDayChart"></canvas>
            </div>
            <!-- Per Minggu -->
            <div class="card p-4 bg-white dark:bg-gray-800 shadow rounded">
                <h3 class="text-lg font-semibold mb-2">Penjualan Per Minggu</h3>
                <canvas id="salesPerWeekChart"></canvas>
            </div>
            <!-- Per Bulan -->
            <div class="card p-4 bg-white dark:bg-gray-800 shadow rounded">
                <h3 class="text-lg font-semibold mb-2">Penjualan Per Bulan</h3>
                <canvas id="salesPerMonthChart"></canvas>
            </div>
        </div>
    </section>

   @if(session('status'))
    <script>
        // Buat audio object
        const audio = new Audio('/sounds/success.mp3'); // Pastikan file ini ada di public/sounds/

        // Tunggu sampai DOM siap, lalu play audio + tampilkan SweetAlert
        window.onload = () => {
            audio.play();

            Swal.fire({
                title: 'Berhasil!',
                text: '{{ session("status") }}',
                icon: 'success',
                confirmButtonText: 'OK',
                customClass: {
                    confirmButton: 'bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded'
                }
            });
        };
    </script>
@endif

</div>
@endsection

@push('scripts')

<script>
    // Inisialisasi PJAX untuk semua tautan dengan atribut data-pjax
    $(document).pjax('a[data-pjax]', '#pjax-container');

    document.addEventListener("DOMContentLoaded", function() {
        const chartOpts = { responsive: true, scales: { y: { beginAtZero: true } } };

        // Grafik Penjualan Per Hari
        new Chart(document.getElementById('salesPerDayChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: {!! json_encode($salesPerDay->pluck('date')) !!},
                datasets: [{
                    label: 'Penjualan Harian',
                    data: {!! json_encode($salesPerDay->pluck('total')) !!},
                    borderWidth: 2,
                    fill: true
                }]
            },
            options: chartOpts
        });

        // Grafik Penjualan Per Minggu
        new Chart(document.getElementById('salesPerWeekChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($salesPerWeek->pluck('label')) !!},
                datasets: [{
                    label: 'Penjualan Mingguan',
                    data: {!! json_encode($salesPerWeek->pluck('total')) !!},
                    borderWidth: 1
                }]
            },
            options: chartOpts
        });

        // Grafik Penjualan Per Bulan
        new Chart(document.getElementById('salesPerMonthChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: {!! json_encode($salesPerMonth->pluck('month')) !!},
                datasets: [{
                    label: 'Penjualan Bulanan',
                    data: {!! json_encode($salesPerMonth->pluck('total')) !!},
                    borderWidth: 2,
                    fill: false
                }]
            },
            options: chartOpts
        });

        let lastShown = null;

        function checkLowStock() {
            const now = Date.now();

            if (!lastShown || now - lastShown > 300000) { // Cek setiap 5 menit
                $.ajax({
                    url: "{{ route('admin.dashboard.checkLowStock') }}",
                    type: "POST",
                    dataType: "json",
                    data: { _token: "{{ csrf_token() }}" },
                    success(response) {
                        if (response.length) {
                            let msg = response.map((item, idx) =>
                                `<div style="
                                    font-size: 12px;
                                    color: #4B5563;
                                    text-align: left;
                                    min-width: 130px;
                                    flex: 1 1 45%;
                                    max-width: 48%;
                                ">
                                    ${idx + 1}. <span style="font-size: 14px; font-weight: 600; color: #111827;">${item.nama_produk}</span><br>
                                    <span style="color:#6B7280;">Stok: ${item.stok}</span>
                                </div>`
                            ).join('');

                            Swal.fire({
                                iconHtml: '<span class="material-icons" style="font-size:36px;color:#2563EB;">notifications_active</span>',
                                title: '<span style="color:#111827;font-size:26px;font-weight:700;">Peringatan Stok Rendah!</span>',
                                html: `
                                    <div style="
                                        display: flex;
                                        flex-wrap: wrap;
                                        gap: 12px;
                                        max-height: 220px;
                                        overflow-y: auto;
                                        padding-top: 10px;
                                    ">
                                        ${msg}
                                    </div>
                                    <div style="margin-top: 12px; font-size: 13px; color: #6B7280;">
                                        <p><strong>Peringatan:</strong> Peringatan akan muncul tiap 5 menit. Cek stok ya, jangan sampai habis!</p>
                                    </div>
                                `,
                                showCancelButton: true,
                                confirmButtonText: 'Restok',
                                cancelButtonText: 'Nanti',
                                customClass: {
                                    confirmButton: 'swal-confirm-custom',
                                    cancelButton: 'swal-cancel-custom'
                                },
                                background: '#F9FAFB',
                                width: 600,
                                padding: '1.2rem'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Navigasi menggunakan PJAX ke halaman stok barang
                                    $.pjax({
                                        url: "{{ url('admin/dashboard/stokbarang') }}",
                                        container: '#pjax-container'
                                    });
                                }
                            });

                            // Styling tombol konfirmasi & batal dengan delay agar elemen Swal sudah dirender
                            setTimeout(() => {
                                const confirmBtn = document.querySelector('.swal2-confirm.swal-confirm-custom');
                                const cancelBtn = document.querySelector('.swal2-cancel.swal-cancel-custom');

                                if (confirmBtn) {
                                    confirmBtn.style.backgroundColor = '#2563EB';
                                    confirmBtn.style.color = '#FFFFFF';
                                    confirmBtn.style.border = 'none';
                                    confirmBtn.style.padding = '8px 16px';
                                    confirmBtn.style.fontSize = '14px';
                                    confirmBtn.style.boxShadow = 'none';
                                }

                                if (cancelBtn) {
                                    cancelBtn.style.backgroundColor = '#DC2626';
                                    cancelBtn.style.color = '#FFFFFF';
                                    cancelBtn.style.border = 'none';
                                    cancelBtn.style.padding = '8px 16px';
                                    cancelBtn.style.fontSize = '14px';
                                    cancelBtn.style.boxShadow = 'none';
                                }
                            }, 100);

                            lastShown = now;
                        }
                    },
                    error(err) {
                        console.error('Error:', err);
                    }
                });
            }
        }

        setInterval(checkLowStock, 300000); // Pengecekan tiap 5 menit
    });
</script>
@endpush

