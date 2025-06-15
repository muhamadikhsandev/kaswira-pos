<div class="max-w-xs mx-auto bg-white dark:bg-gray-800 p-4 shadow-md rounded-md font-mono text-sm dark:border dark:border-white" style="width: 280px;">
  <!-- Header Toko -->
  <div class="text-center leading-tight mb-2 text-black dark:text-white">
    <p class="font-bold uppercase">{{ strtoupper($pengaturan->store_name) }}</p>
    <p>{{ $pengaturan->store_address }}</p>
    <p>Telp: {{ $pengaturan->store_contact }}</p>
    <p>Pemilik: {{ $pengaturan->store_owner }}</p>
  </div>
  <div class="border-t border-dashed border-gray-400 my-2"></div>

  <!-- Informasi Transaksi -->
  <div class="leading-tight mb-2 text-black dark:text-white text-left">
    <p>{{ $data['no_faktur'] ?? 'Tidak tersedia' }}</p>
    <p>Tgl : {{ $data['tanggal'] }} &nbsp;&nbsp; Jam: {{ $data['waktu'] }}</p>
  </div>
  <div class="border-t border-dashed border-gray-400 my-2"></div>

  <!-- Daftar Item -->
  @if(count($data['items']) > 0)
    <div class="leading-tight mb-2 text-black dark:text-white text-left">
      @foreach($data['items'] as $index => $item)
        <p class="mb-1">
  {{ $item['number'] }}. {{ $item['name'] }}<br>
  &nbsp;&nbsp;&nbsp;&nbsp;
  Qty: {{ $item['quantity'] }}  
  <span style="margin-left: 20px;">
    Harga: Rp{{ number_format((int)$item['total'], 0, ',', '.') }}
  </span>
</p>

      @endforeach
    </div>
    <div class="border-t border-dashed border-gray-400 my-2"></div>
  @endif

  <!-- Rekap Pembayaran -->
<div class="leading-tight mb-2 text-black dark:text-white text-left">
  <div class="flex mb-[2px]">
    <span class="w-24">Total</span>
    <span>: Rp{{ number_format((int)$data['total'], 0, ',', '.') }}</span>
  </div>
  <div class="flex mb-[2px]">
    <span class="w-24">Bayar</span>
    <span>: Rp{{ number_format((int)$data['bayar'], 0, ',', '.') }}</span>
  </div>
  <div class="flex">
    <span class="w-24">Kembalian</span>
    <span>: Rp{{ number_format((int)$data['kembalian'], 0, ',', '.') }}</span>
  </div>
</div>


  <div class="border-t border-dashed border-gray-400 my-2"></div>

  <!-- Footer -->
  <div class="text-center my-2 leading-tight text-black dark:text-white">
    <p>~ {{ $pengaturan->receipt_message ?? 'Terima Kasih' }} ~</p>
  </div>
</div>
