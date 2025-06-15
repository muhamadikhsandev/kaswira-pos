<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login Kaswira</title>

  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <script src="https://unpkg.com/lucide@latest"></script>
  <style>
    /* Custom styles for animations and focus effects */
    .hover-animate:hover {
      transform: translateY(-3px) scale(1.02); /* Slight lift and scale */
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15); /* More pronounced shadow */
      transition: all 0.3s ease-in-out;
    }
    .input-focus:focus {
      border-color: #6366F1; /* Indigo-500 */
      box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.4); /* Indigo-500 with transparency */
    }
    .glow-on-hover:hover {
        box-shadow: 0 0 20px rgba(99, 102, 241, 0.6); /* Indigo glow */
    }
    .fade-in {
      animation: fadeIn 0.8s ease-out forwards;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .rotate-scale-hover:hover {
        transform: rotate(6deg) scale(1.15);
        transition: transform 0.4s cubic-bezier(0.68, -0.55, 0.27, 1.55); /* Bouncy effect */
    }
  </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-200 flex items-center justify-center min-h-screen p-4">

  <div class="bg-white shadow-2xl rounded-xl p-8 sm:p-12 max-w-sm sm:max-w-md w-full hover-animate fade-in">
    <div class="text-center mb-8">
        <h2 class="flex items-center justify-center space-x-3 text-3xl sm:text-4xl font-extrabold mb-4">
            <span
                class="flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 bg-gradient-to-br from-blue-500 to-indigo-700 text-white rounded-full shadow-lg ring-4 ring-indigo-300 transform rotate-scale-hover cursor-pointer"
            >
                K
            </span>
            <span
                class="tracking-wider uppercase bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-purple-700 drop-shadow-lg"
            >
                aswira
            </span>
        </h2>
        <p class="text-gray-600 text-md sm:text-lg">Masuk untuk mengelola toko Anda dengan mudah.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-6 sm:space-y-8" id="loginForm">
      @csrf

      <div>
        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Username</label>
        <div class="relative">
          <input type="text" id="name" name="name" value="{{ old('name') }}" required
                 class="input-focus mt-1 block w-full pl-12 pr-5 py-3 sm:py-3.5 bg-gray-50 border border-gray-300 rounded-lg shadow-sm
                        focus:outline-none focus:ring-opacity-50 transition duration-300 text-gray-800"
                 placeholder="Masukkan username admin">
          <i data-lucide="user" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
        </div>
        @error('name')
          <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
        @enderror
      </div>

      <div>
        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
        <div class="relative">
          <input type="password" id="password" name="password" required
                 class="input-focus mt-1 block w-full pl-12 pr-12 py-3 sm:py-3.5 bg-gray-50 border border-gray-300 rounded-lg shadow-sm
                        focus:outline-none focus:ring-opacity-50 transition duration-300 text-gray-800"
                 placeholder="Masukkan password admin">
          <i data-lucide="lock" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
          <button type="button" onclick="togglePassword()" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 focus:outline-none p-1 rounded-full hover:bg-gray-200 transition duration-200">
            <i id="togglePasswordIcon" data-lucide="eye" class="w-5 h-5"></i>
          </button>
        </div>
        @error('password')
          <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
        @enderror
      </div>

      <div class="flex items-center justify-between text-sm">
        <div class="flex items-center">
          <input type="checkbox" id="remember" name="remember"
                 class="rounded-md border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-200">
          <label for="remember" class="text-gray-600 ml-2 select-none">Ingat saya</label>
        </div>
      </div>

      <button type="submit" id="submitButton"
              class="w-full py-3 sm:py-3.5 px-4 bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-semibold rounded-lg shadow-lg
                     hover:from-blue-700 hover:to-indigo-800 transition duration-300 ease-in-out transform hover:scale-105 glow-on-hover
                     flex items-center justify-center space-x-2">
        <span class="text-lg">Login</span>
        <i data-lucide="log-in" class="w-5 h-5"></i>
      </button>
    </form>

    <div id="errorMessage" class="mt-6 text-center text-red-500 font-medium"></div>

    @if(session('status'))
      <p class="text-green-600 text-md mt-6 text-center font-medium">{{ session('status') }}</p>
    @endif
  </div>

  <script>
    // Inisialisasi Lucide Icons
    lucide.createIcons();

    // Toggle password visibility
    function togglePassword() {
      const passwordField = document.getElementById('password');
      const toggleIcon = document.getElementById('togglePasswordIcon');
      if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleIcon.setAttribute('data-lucide', 'eye-off');
      } else {
        passwordField.type = 'password';
        toggleIcon.setAttribute('data-lucide', 'eye');
      }
      lucide.createIcons(); // Re-create icons to apply changes
    }

    // Proses login AJAX tanpa reload halaman
    const loginForm = document.getElementById('loginForm');
    const errorMessage = document.getElementById('errorMessage');
    const submitButton = document.getElementById('submitButton');

    loginForm.addEventListener('submit', function(e) {
      e.preventDefault();
      errorMessage.textContent = '';
      const formData = new FormData(loginForm);

      submitButton.disabled = true;
      submitButton.innerHTML = `<svg class="animate-spin h-5 w-5 text-white mr-3" viewBox="0 0 24 24">
                                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Memuat...</span>`;

      fetch(loginForm.action, {
        method: 'POST',
        headers: {
          'Accept': 'application/json'
        },
        body: formData
      })
      .then(response => {
        submitButton.disabled = false;
        submitButton.innerHTML = `<span class="text-lg">Login</span><i data-lucide="log-in" class="w-5 h-5"></i>`;
        lucide.createIcons(); // Re-create icons for the button

        if (!response.ok) {
          return response.json().then(data => {
            // Handle Laravel validation errors specifically
            if (response.status === 422 && data.errors) {
              let errorMessages = '';
              for (const key in data.errors) {
                errorMessages += data.errors[key].join(', ') + '<br>';
              }
              throw new Error(errorMessages);
            }
            throw new Error(data.message || 'Login gagal, silakan coba lagi.');
          });
        }
        return response.json();
      })
      .then(data => {
        if (data.success) {
          if(window.Turbo) {
            Turbo.visit(data.redirect);
          } else {
            window.location.href = data.redirect || '/admin/dashboard';
          }
        }
      })
      .catch(error => {
        errorMessage.innerHTML = error.message; // Use innerHTML for <br> tags
      });
    });
  </script>
</body>
</html>