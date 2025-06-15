<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Halaman Admin')</title>

    <script>
      // Setel mode gelap secara instan saat memuat halaman
      if (localStorage.getItem('darkMode') === 'true') {
        document.documentElement.classList.add('dark');
        // Pastikan tidak ada transisi saat pemuatan awal
        document.documentElement.classList.add('no-transition');
      }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap"
      rel="stylesheet"
    />
    <link
      href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet"
    />

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css"
    />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
    />

    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
      rel="stylesheet"
    />

    <link
      rel="stylesheet"
      href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css"
    />

    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"
    />

    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon" />

    <script src="https://unpkg.com/lucide@latest" defer></script>

    <link rel="prefetch" href="{{ url('/admin/dashboard/kasir') }}" />
    <link rel="prefetch" href="{{ url('/admin/dashboard/laporanpenjualan') }}" />
    <link rel="prefetch" href="{{ url('/admin/dashboard/stokbarang') }}" />
    <link rel="prefetch" href="{{ url('/admin/dashboard/kategori') }}" />
    <link rel="prefetch" href="{{ url('/admin/dashboard/pengaturan') }}" />

    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.pjax/2.0.1/jquery.pjax.min.js"></script>

    <style>
     /* Existing CSS */

html,
body {
  background-color: #f3f4f6; /* Default light mode background */
  margin: 0;
  padding: 0;
}
.dark html,
.dark body {
  background-color: #111827; /* Default dark mode background */
}
/* pjax-container itself will not have direct padding, it will be handled by its child */
#pjax-container {
  min-height: 500px; /* Adjust as needed for content */
  background-color: inherit; /* Inherit background from body */
}

/* Add transition to sidebar for smooth collapse/expand */
#sidebar {
  transition: width 0.3s ease-in-out, padding 0.3s ease-in-out,
    margin 0.3s ease-in-out, opacity 0.3s ease-in-out;
}

@media (max-width: 640px) {
  .hide-on-mobile {
    display: none;
  }
}

@media (min-width: 1024px) {
  /* Modified sidebar-collapsed to use width: 0 and overflow: hidden */
  .sidebar-collapsed {
    width: 0 !important;
    padding-left: 0 !important;
    padding-right: 0 !important;
    margin-left: 0 !important;
    margin-right: 0 !important;
    overflow: hidden !important;
    opacity: 0 !important; /* Make it completely invisible */
  }
}

/* Modern PJAX Spinner Styling */
.page-load-spinner {
  display: none; /* Hidden by default */
  position: fixed;
  top: 20px; /* Adjusted to be slightly below the header */
  left: 50%;
  transform: translateX(-50%);
  z-index: 10000; /* High z-index to be on top */
  padding: 5px 15px;
  background-color: rgba(255, 255, 255, 0.9);
  border-radius: 50px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  transition: opacity 0.3s ease; /* Smooth appearance/disappearance */
}
.dark .page-load-spinner {
  background-color: rgba(31, 41, 55, 0.9); /* Dark mode background for spinner */
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
}
.spinner-inner {
  width: 20px;
  height: 20px;
  border: 3px solid rgba(59, 130, 246, 0.3); /* Tailwind blue-500 at 30% opacity */
  border-top-color: rgb(59, 130, 246); /* Tailwind blue-500 */
  border-radius: 50%;
  animation: spin 0.6s linear infinite;
}
@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

body {
  font-family: 'Poppins', sans-serif;
}

/* Styles for dataTables_filter - applied only on screens up to 1023px (mobile/tablet) */
@media (max-width: 1023px) {
  .dataTables_filter {
    position: relative;
    margin-bottom: 1.5rem; /* Added spacing below the search input for mobile */
  }
  .dataTables_filter input {
    padding: 0.75rem 1rem 0.75rem 2.5rem !important; /* Adjusted padding */
    border: 1px solid #d1d5db; /* Modern border */
    border-radius: 0.5rem; /* Rounded corners */
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05); /* Subtle shadow */
    transition: all 0.3s ease-in-out; /* Smooth transitions */
    width: 100%; /* Make it full width if needed, or adjust as per layout */
    max-width: 300px; /* Max width for consistency */
  }
  .dataTables_filter input:focus {
    outline: none;
    border-color: #3b82f6; /* Blue border on focus */
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25); /* Focus ring */
  }
  .dataTables_filter .material-icons {
    position: absolute;
    left: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    pointer-events: none;
    color: #6b7280; /* Icon color */
  }
}

/* New styles for desktop (min-width: 1024px) */
@media (min-width: 1024px) {
  .dataTables_filter {
    margin-bottom: 1rem; /* Memberikan sedikit jarak di desktop */
  }
}

/* Added responsive table wrapper */
.table-responsive {
  overflow-x: auto; /* Enable horizontal scrolling for tables */
  -webkit-overflow-scrolling: touch; /* Smooth scrolling on iOS */
  margin-bottom: 1rem; /* Add some space below the table */
}

/* Ensure table takes full width inside its container */
.dataTables_wrapper .dataTables_scrollHeadInner,
.dataTables_wrapper .dataTables_scrollBody table {
  width: 100% !important; /* Ensure table uses full width */
}

/* Adjust DataTables pagination wrapper for better spacing and responsiveness */
.dataTables_wrapper .dataTables_paginate {
  display: flex;
  flex-wrap: wrap; /* Allow pagination buttons to wrap to the next line */
  justify-content: center; /* Center align pagination buttons */
  padding: 1rem 0; /* Add padding above and below pagination */
}

.dataTables_wrapper .dataTables_info {
  text-align: center; /* Center align "Showing X of Y entries" text */
  margin-bottom: 0.5rem; /* Space below info text */
}

.modal-hidden {
  display: none;
}
.modal-visible {
  display: flex;
}
.floating-button {
  position: fixed;
  bottom: 2rem;
  right: 2rem;
  width: 3rem;
  height: 3rem;
  display: flex;
  align-items: center;
  justify-content: center;
}
.paginate_button {
  border: none !important;
  background: transparent !important;
  cursor: pointer;
  padding: 0.5rem 0.75rem; /* Added padding for better hover target */
  border-radius: 0.25rem; /* Slightly rounded corners */
  transition: background-color 0.2s ease, color 0.2s ease; /* Smooth transition */
  margin: 0.25rem; /* Add margin between pagination buttons */
}
.paginate_button:hover {
  background-color: rgba(59, 130, 246, 0.1); /* Light blue background on hover */
  color: #3b82f6; /* Blue text on hover */
}
/* Style for the active pagination button */
.paginate_button.current {
  background-color: #2563eb !important; /* Solid blue background */
  color: #fff !important; /* White text */
  font-weight: 600; /* Bolder text */
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); /* Subtle shadow */
}
.paginate_button.current:hover {
  background-color: #1d4ed8 !important; /* Darker blue on hover for active state */
}

body {
  font-family: 'Poppins', sans-serif;
  background-color: #f4f4f4; /* Soft background color */
}
.dark body {
  background-color: #1a202c; /* Darker background in dark mode */
}

.card {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  background-color: white; /* White card background */
  border-radius: 1rem; /* Rounded corners */
  overflow: hidden; /* Hide overflowing content */
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow */
}
.dark .card {
  background-color: #1f2937; /* Darker card background */
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3); /* Darker shadow */
}

.card:hover {
  transform: translateY(-8px); /* More pronounced hover effect */
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15); /* Darker shadow on hover */
}
.dark .card:hover {
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.5); /* Even darker shadow on hover in dark mode */
}

.card-icon {
  font-size: 3rem; /* Larger icon size */
  color: #2563eb; /* Indigo icon color */
}
/* No change needed for card-icon in dark mode, as blue works well */

.card-title {
  font-weight: 600; /* Semi-bold title */
  color: #374151; /* Darker title color */
}
.dark .card-title {
  color: #d1d5db; /* Lighter title color in dark mode */
}

.card-value {
  font-size: 2.5rem; /* Larger value size */
  font-weight: 700; /* Bold value */
  color: #111827; /* Very dark value color */
}
.dark .card-value {
  color: #f9fafb; /* White-ish value color in dark mode */
}

.chart-container {
  background-color: white;
  border-radius: 1rem;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  padding: 2rem;
}
.dark .chart-container {
  background-color: #1f2937; /* Darker chart container background */
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
}

.chart-title {
  font-size: 1.5rem;
  font-weight: 600;
  color: #374151;
  margin-bottom: 1rem;
}
.dark .chart-title {
  color: #d1d5db; /* Lighter chart title in dark mode */
}

/* Hover styles untuk sidebar */
#sidebar a:hover .sidebar-text,
#sidebar a:hover .material-icons,
#sidebar .relative button:hover .sidebar-text,
#sidebar .relative button:hover .material-icons {
  color: #3b82f6;
}
#sidebar a:hover,
#sidebar .relative button:hover {
  background-color: rgba(59, 130, 246, 0.1);
  transform: translateX(5px);
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}
.dark #sidebar a:hover,
.dark #sidebar .relative button:hover {
  background-color: rgba(59, 130, 246, 0.2); /* Slightly more opaque hover in dark mode */
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
}

button:hover {
  background-color: rgba(59, 130, 246, 0.1);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}
.dark button:hover {
  background-color: rgba(59, 130, 246, 0.2);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
}

#profileMenu:hover {
  background-color: rgba(59, 130, 246, 0.1);
  transform: scale(1.05);
}
.dark #profileMenu:hover {
  background-color: rgba(59, 130, 246, 0.2);
}

#toggleSidebar:hover {
  color: #3b82f6;
  transform: rotate(180deg);
}
.material-icons:hover {
  color: #3b82f6;
  transform: scale(1.2);
}
#sidebar a,
button {
  transition: background-color 0.3s ease, transform 0.3s ease,
    box-shadow 0.3s ease;
}
.material-icons {
  transition: color 0.3s ease, transform 0.3s ease;
}
/* Atur tampilan sidebar untuk mobile */
#sidebar {
  transition: all 0.3s ease-in-out;
}

* {
  font-family: 'Poppins', sans-serif;
}
.action-btn {
  transition: all 0.3s ease-in-out;
}
.action-btn:hover {
  transform: scale(1.1);
}
.delete-btn:hover {
  background-color: rgba(239, 68, 68, 0.2);
}
.floating-button {
  position: fixed;
  bottom: 20px;
  right: 20px;
  z-index: 50;
}
/* Modal overlay */
.modal-overlay {
  position: fixed;
  inset: 0;
  z-index: 100;
  background: rgba(0, 0, 0, 0.5);
  transition: opacity 0.3s ease-in-out;
  overflow-y: auto;
  padding: 1rem;
}
.dark .modal-overlay {
  background: rgba(0, 0, 0, 0.7); /* Darker overlay in dark mode */
}

.modal-hidden {
  opacity: 0;
  pointer-events: none;
}
.modal-visible {
  opacity: 1;
  pointer-events: auto;
}
/* Custom scrollbar */
::-webkit-scrollbar {
  width: 6px; /* Kembali ke 6px atau bahkan 4px jika ingin lebih ramping lagi */
  height: 6px;
}
::-webkit-scrollbar-track {
  background: transparent; /* Track transparan */
}
.dark ::-webkit-scrollbar-track {
  background: transparent; /* Track transparan di dark mode juga */
}

/* Menambahkan transisi ke thumb untuk efek fade-in/fade-out */
::-webkit-scrollbar-thumb {
  background: rgba(37, 99, 235, 0); /* Awalnya benar-benar transparan di light mode */
  border-radius: 10px; /* Membuat thumb sangat bulat */
  transition: background-color 0.3s ease, opacity 0.3s ease; /* Transisi untuk opacity dan warna */
}
/* Saat elemen yang mengandung scrollbar di-hover, thumb muncul */
body:hover::-webkit-scrollbar-thumb { /* Target body atau container utama yang di-scroll */
  background: rgba(37, 99, 235, 0.4); /* Muncul dengan opacity lebih rendah (40%) di light mode */
}
/* Saat thumb itu sendiri di-hover, menjadi solid */
::-webkit-scrollbar-thumb:hover {
  background: #2563eb; /* Biru solid saat hover di light mode */
}


.dark ::-webkit-scrollbar-thumb {
  /* Awalnya benar-benar transparan di dark mode */
  background: linear-gradient(
    180deg,
    rgba(59, 130, 246, 0), /* blue-500 dengan 0% opacity */
    rgba(37, 99, 235, 0)    /* blue-600 dengan 0% opacity */
  );
  border-radius: 10px; /* Membuat thumb sangat bulat */
  transition: background 0.3s ease, opacity 0.3s ease; /* Transisi untuk opacity dan gradasi */
}
/* Saat elemen yang mengandung scrollbar di-hover, thumb muncul */
.dark body:hover::-webkit-scrollbar-thumb { /* Target body atau container utama yang di-scroll */
  background: linear-gradient(
    180deg,
    rgba(59, 130, 246, 0.4), /* blue-500 dengan 40% opacity */
    rgba(37, 99, 235, 0.4)    /* blue-600 dengan 40% opacity */
  );
}
/* Saat thumb itu sendiri di-hover, menjadi solid */
.dark ::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(
    180deg,
    #3b82f6, /* blue-500 solid saat hover */
    #2563eb    /* blue-600 solid saat hover */
  );
}


/* Universal Table Styling */
/* Table header with blue-600 color */
table thead tr th {
  font-weight: 600;
  background-color: #2563eb !important; /* blue-600 (default for light mode) */
  color: #fff !important;
  border-bottom: 2px solid #1d4ed8; /* blue-700 for bottom border (default for light mode) */
}
.dark table thead tr th {
  background-color: #1a4d8c !important; /* Slightly desaturated/darker blue for dark mode */
  border-bottom: 2px solid #153e70; /* Darker blue for border in dark mode */
  color: #e0e0e0 !important; /* Slightly off-white for text in dark mode header */
}


/* Table border */
table td,
table th {
  border: 1px solid #dbeafe; /* Tailwind blue-100, soft border */
}
.dark table td,
.dark table th {
  border: 1px solid #374151; /* Darker border in dark mode */
  color: #d1d5db; /* Lighter text color for table cells */
}

/* Default row background (odd rows) */
table tbody tr {
  background-color: #ffffff; /* White default for light mode */
}
.dark table tbody tr {
  background-color: #2d3748; /* Darker default for odd rows in dark mode */
}

/* Table row hover */
table tbody tr:hover {
  background-color: #dbeafe; /* blue-100 background on hover (light mode) */
}
.dark table tbody tr:hover {
  background-color: #3b526d; /* A more distinct bluish-gray for hover in dark mode */
}

/* Alternating row background (striped effect) */
table tbody tr:nth-child(even) {
  background-color: #f0f9ff; /* Very light blue (light mode) */
}
.dark table tbody tr:nth-child(even) {
  background-color: #25334a; /* A slightly darker bluish-gray for alternating rows in dark mode */
}

/* New CSS to disable transitions */
.no-transition * {
  transition: none !important;
}
</style>


    @stack('styles')
  </head>
  <body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200">
    
    <div id="page-load-spinner" class="page-load-spinner">
      <div class="spinner-inner"></div>
    </div>

    <audio id="lightModeSound" src="{{ asset('sounds/lightmode.mp3') }}" preload="auto"></audio>
    <audio id="darkModeSound" src="{{ asset('sounds/darkmode.mp3') }}" preload="auto"></audio>

    <header class="fixed top-4 left-2 right-2 sm:left-4 sm:right-4 shadow-lg bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-3xl z-20">
      <div class="mx-auto px-4 py-2 flex items-center justify-between">
        <div class="flex items-center">
          <button id="toggleSidebar" aria-label="Toggle Sidebar" class="hidden lg:flex p-2 rounded-2xl hover:bg-gray-100 dark:hover:bg-gray-700">
            <span class="material-icons text-3xl lg:text-4xl">chevron_left</span>
          </button>
          <button id="hamburger" aria-label="Open Mobile Menu" class="lg:hidden p-2 rounded-2xl hover:bg-gray-100 dark:hover:bg-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
          </button>
        </div>

        <div class="absolute left-1/2 transform -translate-x-1/2 text-center">
          <h2 class="flex items-center justify-center space-x-2 text-xl sm:text-2xl font-bold mr-10">
            <span class="flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-blue-500 to-indigo-600 text-white rounded-full shadow-lg ring-4 ring-indigo-400 hover:rotate-12 hover:scale-110">
              K
            </span>
            <span class="tracking-widest uppercase bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-purple-600 drop-shadow-md">
              aswira
            </span>
          </h2>
        </div>

        <div class="flex items-center space-x-2">
          <button id="darkModeToggle" aria-label="Toggle Dark Mode" class="p-2 rounded-full hover:text-gray-600 dark:hover:text-gray-300">
            <span class="material-icons text-xl sm:text-2xl">dark_mode</span>
          </button>
          <div class="relative">
            @php
              $user = auth()->user();
              $profilePhoto = $user && optional($user->profile)->profile_photo_path
                  ? asset('storage/' . $user->profile->profile_photo_path)
                  : asset('images/default-profile.png');
            @endphp
            <button id="profileMenu" aria-haspopup="true" aria-expanded="false" aria-label="Open Profile Menu" class="flex items-center p-1 sm:p-2 rounded-full focus:outline-none">
              <img id="navbar-profile-photo" src="{{ $profilePhoto }}" alt="Foto Profil" class="w-8 h-8 sm:w-9 sm:h-9 rounded-full shadow-md" />
              <span id="navbar-username" class="hidden sm:inline ml-2 text-sm sm:text-base font-medium">
               {{ $user->profile?->name ?? 'Nama Admin' }}
              </span>
              <span class="material-icons ml-1 text-xl sm:text-2xl hide-on-mobile">expand_more</span>
            </button>
            <div id="profileDropdown" class="absolute right-0 mt-2 w-40 sm:w-48 bg-white dark:bg-gray-800 shadow-lg rounded-3xl p-2 border dark:border-gray-700 hidden">
              <a href="{{ url('admin/dashboard/profile') }}" data-pjax class="group flex items-center px-3 py-2 text-xs sm:text-sm rounded-2xl hover:bg-blue-100 dark:hover:bg-blue-900">
                <span class="material-icons mr-2 text-base group-hover:text-blue-600">person</span>
                <span class="group-hover:text-blue-600">Profil</span>
              </a>
              <form action="{{ route('logout') }}" method="POST" id="logoutForm">
                @csrf
                <button type="button" id="logoutButton" class="group flex items-center px-3 py-2 text-xs sm:text-sm rounded-2xl w-full text-left hover:bg-blue-100 dark:hover:bg-blue-900">
                  <span class="material-icons mr-2 text-base group-hover:text-blue-600">logout</span>
                  <span class="group-hover:text-blue-600">Keluar</span>
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </header>

    <div class="flex pt-24">
      <aside id="sidebar" class="w-64 mx-4 bg-white dark:bg-gray-800 border dark:border-gray-700 p-6 shadow-xl rounded-3xl hidden lg:block sticky top-24 h-[calc(100vh-7rem)] overflow-y-auto">
        <h2 class="text-2xl font-bold border-b pb-3">Menu</h2>
        <nav class="mt-4">
          <ul class="space-y-3">
            <li>
              <a href="{{ url('admin/dashboard') }}" data-pjax class="flex items-center gap-3 p-3 rounded-2xl hover:bg-gray-100 dark:hover:bg-gray-700 shadow-sm">
                <span class="material-icons text-2xl text-blue-600">dashboard</span>
                <span>Dashboard</span>
              </a>
            </li>
            <li class="relative">
              <button onclick="toggleDropdown('dropdownMenu')" class="flex items-center gap-3 p-3 w-full rounded-2xl hover:bg-gray-100 dark:hover:bg-gray-700 shadow-sm">
                <span class="material-icons text-2xl text-blue-600">shopping_cart</span>
                <span>Transaksi</span>
                <span class="material-icons ml-auto">arrow_drop_down</span>
              </button>
              <ul id="dropdownMenu" class="hidden bg-white dark:bg-gray-800 shadow-md rounded-2xl mt-2">
                <li>
                  <a href="{{ url('/admin/dashboard/kasir') }}" data-pjax class="block px-6 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">Kasir</a>
                </li>
                <li>
                  <a href="{{ url('/admin/dashboard/laporanpenjualan') }}" data-pjax class="block px-6 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">Laporan Penjualan</a>
                </li>
              </ul>
            </li>
            <li class="relative">
              <button onclick="toggleDropdown('kelolaDropdownMenu')" class="flex items-center gap-3 p-3 w-full rounded-2xl hover:bg-gray-100 dark:hover:bg-gray-700 shadow-sm">
                <span class="material-icons text-2xl text-blue-600">manage_accounts</span>
                <span>Kelola</span>
                <span class="material-icons ml-auto">arrow_drop_down</span>
              </button>
              <ul id="kelolaDropdownMenu" class="hidden bg-white dark:bg-gray-800 shadow-md rounded-2xl mt-2">
                <li>
                  <a href="{{ url('/admin/dashboard/stokbarang') }}" data-pjax class="block px-6 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">Barang</a>
                </li>
                <li>
                  <a href="{{ url('/admin/dashboard/kategori') }}" data-pjax class="block px-6 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">Kategori</a>
                </li>
                <li>
                  <a href="{{ url('/admin/dashboard/satuan') }}" data-pjax class="block px-6 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">Satuan</a>
                </li>
              </ul>
            </li>
            <li>
              <a href="{{ url('/admin/dashboard/pengaturan') }}" data-pjax class="flex items-center gap-4 p-3 rounded-2xl hover:bg-gray-100 dark:hover:bg-gray-700 shadow-sm">
                <span class="material-icons text-2xl text-blue-600">settings</span>
                <span>Pengaturan</span>
              </a>
            </li>
          </ul>
        </nav>
        <div class="absolute bottom-2 left-6 right-6 text-center text-gray-500 text-sm">
          <hr class="mb-2 border-gray-300 dark:border-gray-600" />
          &copy; {{ date('Y') }} Kaswira Kasir Wirausaha. Semua hak dilindungi.
        </div>
      </aside>

      {{-- pjax-container itu sendiri tidak akan memiliki padding langsung, akan ditangani oleh turunannya --}}
      <div id="pjax-container" class="flex-1 overflow-y-auto dark:bg-gray-900">
        {{-- Menambahkan padding responsif di sini: tidak ada padding horizontal di mobile, p-8 di layar lebih besar --}}
        <div class="px-4 py-4 sm:px-6 sm:py-6 lg:p-8">
            @yield('content')
        </div>
      </div>
    </div>

    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
      $(document).ready(function() {
        // Hapus kelas no-transition setelah DOMContentLoaded untuk mengaktifkan transisi normal setelah pemuatan awal
        setTimeout(() => {
          document.documentElement.classList.remove('no-transition');
        }, 0);

        // Inisiasi PJAX: Semua link dengan atribut data-pjax akan dimuat ulang di dalam #pjax-container
        if ($.support.pjax) {
          $(document).pjax('a[data-pjax]', '#pjax-container', {
            timeout: 5000 // Contoh timeout: 5 detik
          });
        }

        var pjaxSpinner = $('#page-load-spinner');

        $(document).on('pjax:send', function() {
          console.log("PJAX: send");
          pjaxSpinner.show();
        });

        $(document).on('pjax:complete', function() {
          console.log("PJAX: complete");
          pjaxSpinner.hide();
           // Re-initialize any scripts or plugins specific to the loaded content here if needed
        });
        
        $(document).on('pjax:error', function(event, xhr, textStatus, errorThrown, options) {
          console.error("PJAX: error", errorThrown);
          pjaxSpinner.hide();
          // Opsional, tampilkan pesan kesalahan kepada pengguna
          toastr.error('Gagal memuat halaman. Silakan coba lagi.', 'Error Navigasi');
        });

        $(document).on('pjax:timeout', function(event, xhr, options) {
          console.warn("PJAX: timeout");
          pjaxSpinner.hide();
          toastr.warning('Waktu tunggu habis. Silakan periksa koneksi Anda.', 'Navigasi Lambat');
          // Opsional, coba muat ulang halaman atau arahkan ulang
          // event.preventDefault(); // untuk mencegah perilaku coba lagi default jika Anda ingin menanganinya sepenuhnya khusus
        });


        // Fungsi dropdown
        window.toggleDropdown = function(menuId) {
          var dropdowns = ["dropdownMenu", "kelolaDropdownMenu"];
          dropdowns.forEach(function(id) {
            var el = document.getElementById(id);
            if (el && id !== menuId) {
              el.classList.add("hidden");
            }
          });
          var targetEl = document.getElementById(menuId);
          if (targetEl) {
            targetEl.classList.toggle("hidden");
          }
        };

        // Sidebar toggle (Desktop)
        var toggleSidebarBtn = document.getElementById("toggleSidebar");
        if (toggleSidebarBtn) {
          toggleSidebarBtn.addEventListener("click", function () {
            var sidebar = document.getElementById("sidebar");
            var chevronIcon = this.querySelector("span");
            if (window.innerWidth >= 1024 && sidebar) { // Hanya untuk desktop
              sidebar.classList.toggle("sidebar-collapsed");
              chevronIcon.textContent = sidebar.classList.contains("sidebar-collapsed") ? "chevron_right" : "chevron_left";
            }
          });
        }
        
        // Sidebar toggle (Mobile)
        var hamburgerBtn = document.getElementById("hamburger");
        if (hamburgerBtn) {
          hamburgerBtn.addEventListener("click", function () {
            var sidebar = document.getElementById("sidebar");
            if (sidebar) {
              sidebar.classList.toggle("hidden");
            }
          });
        }

        // Profile Dropdown Toggle
        var profileMenuBtn = document.getElementById("profileMenu");
        if (profileMenuBtn) {
          profileMenuBtn.addEventListener("click", function () {
            var profileDropdown = document.getElementById("profileDropdown");
            if (profileDropdown) {
              profileDropdown.classList.toggle("hidden");
            }
          });
        }
        
        // Logout Button
        var logoutButton = document.getElementById("logoutButton");
        if (logoutButton) {
          logoutButton.addEventListener("click", function () {
            Swal.fire({
              title: "Keluar dari Akun?",
              text: "Anda yakin ingin keluar? Semua sesi akan ditutup.",
              iconHtml: `<svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1"/>
                         </svg>`,
              showCancelButton: true,
              confirmButtonColor: "#2563eb",
              cancelButtonColor: "#9ca3af",
              confirmButtonText: "Ya, Keluar!",
              cancelButtonText: "Batal",
              customClass: {
                popup: "rounded-xl shadow-2xl p-6 bg-gray-50 dark:bg-gray-800",
                title: "text-2xl font-semibold text-gray-800 dark:text-gray-100",
                htmlContainer: "text-gray-600 dark:text-gray-300 text-sm",
                confirmButton: "bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg",
                cancelButton: "bg-gray-300 hover:bg-gray-400 text-gray-800 dark:text-gray-200 dark:bg-gray-600 dark:hover:bg-gray-500 font-semibold py-2 px-4 rounded-lg",
              },
              background: document.documentElement.classList.contains('dark') ? '#1f2937' : '#f9fafb', // gray-800 or gray-50
              backdrop: "rgba(0, 0, 0, 0.5)",
              allowOutsideClick: false,
              allowEscapeKey: true,
              showClass: { popup: "animate_animated animate_fadeInDown" },
              hideClass: { popup: "animate_animated animate_fadeOutUp" },
            }).then((result) => {
              if (result.isConfirmed) {
                const btn = this;
                btn.disabled = true;
                btn.innerHTML = `<svg class="animate-spin h-5 w-5 inline-block mr-2 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                  </svg>
                                  Memuat...`;

                fetch("{{ route('logout') }}", {
                  method: "POST",
                  headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json",
                  },
                })
                  .then((response) => {
                    if (!response.ok) {
                      throw new Error("Network response was not ok");
                    }
                    return response.json();
                  })
                  .then((data) => {
                    if (data.success) {
                      window.location.href = data.redirect; // Pengalihan penuh setelah logout
                    } else {
                      throw new Error(data.message || "Logout gagal, success=false");
                    }
                  })
                  .catch((error) => {
                    console.error("Kesalahan logout:", error);
                    btn.disabled = false;
                    // Kembalikan teks dan ikon tombol asli
                    const originalIcon = `<span class="material-icons mr-2 text-base group-hover:text-blue-600">logout</span>`;
                    const originalText = `<span class="group-hover:text-blue-600">Keluar</span>`;
                    btn.innerHTML = originalIcon + originalText;
                    Swal.fire("Error", "Terjadi kesalahan saat logout: " + error.message, "error");
                  });
              }
            });
          });
        }

        // Dark Mode Toggle
        var darkModeToggle = document.getElementById("darkModeToggle");
        // Ambil elemen audio
        var lightModeSound = document.getElementById("lightModeSound");
        var darkModeSound = document.getElementById("darkModeSound");

        if (darkModeToggle) {
          var darkModeIcon = darkModeToggle.querySelector("span");
          // Setel ikon awal berdasarkan status mode gelap
          darkModeIcon.textContent = document.documentElement.classList.contains("dark") ? "light_mode" : "dark_mode";
          
          darkModeToggle.addEventListener("click", function () {
            // Tambahkan kelas untuk menonaktifkan transisi sebelum mengubah mode
            document.documentElement.classList.add('no-transition');
            
            document.documentElement.classList.toggle("dark");
            var isDarkMode = document.documentElement.classList.contains("dark");
            darkModeIcon.textContent = isDarkMode ? "light_mode" : "dark_mode";
            localStorage.setItem("darkMode", isDarkMode ? "true" : "false");

            // Putar suara berdasarkan mode
            if (isDarkMode) {
              if (darkModeSound) darkModeSound.play().catch(e => console.error("Error playing dark mode sound:", e));
            } else {
              if (lightModeSound) lightModeSound.play().catch(e => console.error("Error playing light mode sound:", e));
            }

            // Hapus kelas no-transition setelah perubahan mode diterapkan
            // Gunakan setTimeout dengan 0ms untuk menunda penghapusan kelas,
            // memastikan perubahan gaya instan diterapkan terlebih dahulu
            setTimeout(() => {
                document.documentElement.classList.remove('no-transition');
            }, 0);

            // Perbarui latar belakang SweetAlert jika sedang terbuka, atau untuk waktu berikutnya
            if (Swal.isVisible()) {
                Swal.update({
                    background: isDarkMode ? '#1f2937' : '#f9fafb',
                    customClass: {
                        popup: "rounded-xl shadow-2xl p-6 " + (isDarkMode ? "bg-gray-800" : "bg-gray-50"),
                        title: "text-2xl font-semibold " + (isDarkMode ? "text-gray-100" : "text-gray-800"),
                        htmlContainer: "text-sm " + (isDarkMode ? "text-gray-300" : "text-gray-600"),
                    }
                });
            }
          });
        }

        // --- Kode baru untuk pembaruan nama pengguna dinamis ---
        // Ini mengasumsikan formulir pembaruan profil Anda disubmit melalui AJAX
        // dan bahwa halaman pembaruan profil dimuat melalui PJAX.
        $(document).on('pjax:complete', function() {
            // Ikatan ulang pendengar acara untuk formulir yang dimuat melalui PJAX jika diperlukan
            // Atau, jika formulir pembaruan profil Anda selalu ada, ini bisa di luar pjax:complete
            handleProfileUpdateForm();
        });

        function handleProfileUpdateForm() {
            // Mengasumsikan formulir pembaruan info profil Anda memiliki ID seperti 'profile-info-form'
            $('#profile-info-form').off('submit').on('submit', function(e) { // Gunakan .off() untuk mencegah multiple bindings
                e.preventDefault(); // Mencegah pengiriman formulir default

                let form = $(this);
                let url = form.attr('action');
                let formData = form.serialize();

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        if (response.message) {
                            toastr.success(response.message, 'Berhasil');
                        }
                        if (response.profile_name) {
                            $('#navbar-username').text(response.profile_name);
                        }
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON.errors;
                        if (errors) {
                            for (let key in errors) {
                                toastr.error(errors[key][0], 'Error');
                            }
                        } else {
                            toastr.error('Terjadi kesalahan saat memperbarui profil.', 'Error');
                        }
                    }
                });
            });

            // Juga, tangani pembaruan gambar profil jika itu panggilan AJAX terpisah
            $('#profile-picture-form').off('submit').on('submit', function(e) { // Gunakan .off() untuk mencegah multiple bindings
                e.preventDefault();
                let form = $(this);
                let formData = new FormData(this); // Gunakan FormData untuk unggahan file

                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false, // Penting untuk FormData
                    contentType: false, // Penting untuk FormData
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        if (response.message) {
                            toastr.success(response.message, 'Berhasil');
                        }
                        if (response.profile_photo_url) {
                            $('#navbar-profile-photo').attr('src', response.profile_photo_url);
                        }
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON.errors;
                        if (errors) {
                            for (let key in errors) {
                                toastr.error(errors[key][0], 'Error');
                            }
                        } else {
                            toastr.error('Terjadi kesalahan saat memperbarui foto profil.', 'Error');
                        }
                    }
                });
            });
        }

        // Panggilan awal jika halaman profil dimuat langsung (bukan melalui PJAX pada pemuatan pertama)
        handleProfileUpdateForm();

      }); // Akhir dari $(document).ready
    </script>
    @stack('scripts')
  </body>
</html>