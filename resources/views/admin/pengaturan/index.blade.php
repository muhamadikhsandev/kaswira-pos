@extends('layouts.app')

@section('title', 'Pengaturan Toko')

@section('content')
<header class="mb-8 text-center">
  <h1 class="text-3xl sm:text-4xl font-bold text-gray-800 dark:text-gray-100 flex items-center justify-center gap-2 whitespace-nowrap">
    <i class="fas fa-store text-blue-600"></i>
    Pengaturan Toko
  </h1>
  <p class="mt-2 text-gray-600 dark:text-gray-400 text-sm sm:text-base">
    Atur informasi toko, perangkat printer, dan nama pemilik untuk kelancaran operasional.
  </p>
</header>

<div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 md:p-8">
  <form id="pengaturanForm" action="#" method="POST" class="space-y-6">
    @csrf

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      @foreach ([
        ['id' => 'store_name',        'label' => 'Nama Toko',            'placeholder' => 'Masukkan nama toko'],
        ['id' => 'store_contact',     'label' => 'Kontak (HP)',          'placeholder' => 'Masukkan nomor HP'],
        ['id' => 'store_owner',       'label' => 'Nama Pemilik Toko',    'placeholder' => 'Masukkan nama pemilik toko'],
        ['id' => 'printer_name',      'label' => 'Nama Printer',         'placeholder' => 'Masukkan nama printer'],
        ['id' => 'store_address',     'label' => 'Alamat Toko',          'placeholder' => 'Masukkan alamat toko'],
        ['id' => 'receipt_message',   'label' => 'Ucapan di Struk',      'placeholder' => 'Contoh: Terima kasih atas kunjungannya!'],
      ] as $field)
      <div>
        <label for="{{ $field['id'] }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
          {{ $field['label'] }}:
        </label>
        <input
          type="text"
          id="{{ $field['id'] }}"
          name="{{ $field['id'] }}"
          placeholder="{{ $field['placeholder'] }}"
          value="{{ old($field['id'], $pengaturan->{$field['id']} ?? '') }}"
          class="w-full h-[48px] p-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:outline-none"
        >
      </div>
      @endforeach
    </div>

    <div class="flex justify-center">
      <button
        type="submit"
        class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-lg shadow-md transition-all transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
      >
        <i class="fas fa-save"></i> Simpan Pengaturan
      </button>
    </div>
  </form>
</div>

<audio id="successSound" src="{{ asset('sounds/success.mp3') }}" preload="auto"></audio>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 2500,
    timerProgressBar: true,
  });

  // Fungsi untuk memainkan suara sukses
  function playSuccessSound() {
    var sound = document.getElementById('successSound');
    if (sound) {
      sound.currentTime = 0; // Rewind to the start
      sound.play().catch(function(error) {
        console.log("Error playing sound:", error); // Optional: Log any playback errors
      });
    }
  }

  const form = document.getElementById('pengaturanForm');

  form.addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch("{{ route('admin.pengaturan.update') }}", {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Accept': 'application/json'
      },
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        Toast.fire({
          icon: 'success',
          title: data.message
        });
        playSuccessSound(); // Mainkan suara sukses

        const updated = data.pengaturan;
        ['store_name', 'store_address', 'store_contact', 'store_owner', 'printer_name', 'receipt_message'].forEach(field => {
          const el = document.getElementById(field);
          if (el) el.value = updated[field];
        });
      } else {
        Toast.fire({
          icon: 'error',
          title: data.message || 'Gagal memperbarui pengaturan.'
        });
      }
    })
    .catch(error => {
      console.error('Error:', error); // Tambahkan log error untuk debugging
      Toast.fire({
        icon: 'error',
        title: 'Terjadi kesalahan. Silakan coba lagi.' // Pesan error yang lebih umum
      });
    });
  });
});
</script>
@endpush