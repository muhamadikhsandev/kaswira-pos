<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register Page</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    /* Custom animation for modern look */
    .hover-animate:hover {
      transform: scale(1.05);
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }
  </style>
</head>
<body class="bg-gradient-to-r from-gray-100 via-gray-200 to-gray-300 flex items-center justify-center min-h-screen">
  <div class="bg-white shadow-xl rounded-lg p-8 w-full max-w-md">
    <h2 class="text-3xl font-semibold text-center text-gray-900 mb-6">Create Account</h2>
    <p class="text-center text-gray-600 mb-6 text-sm">Start your journey by creating an account</p>
    <form method="POST" action="{{ route('register') }}" class="space-y-6">
      @csrf
      <!-- Full Name -->
      <div>
        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
        <input 
          type="text" 
          id="name" 
          name="name" 
          value="{{ old('name') }}" 
          required 
          class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-200" 
          placeholder="Your full name"
        />
        @error('name')
          <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
        @enderror
      </div>
      <!-- Email Address -->
      <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
        <input 
          type="email" 
          id="email" 
          name="email" 
          value="{{ old('email') }}" 
          required 
          class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-200" 
          placeholder="example@mail.com"
        />
        @error('email')
          <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
        @enderror
      </div>
      <!-- Password -->
      <div>
        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
        <input 
          type="password" 
          id="password" 
          name="password" 
          required 
          class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-200" 
          placeholder="Create a password"
        />
        @error('password')
          <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
        @enderror
      </div>
      <!-- Confirm Password -->
      <div>
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
        <input 
          type="password" 
          id="password_confirmation" 
          name="password_confirmation" 
          required 
          class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-200" 
          placeholder="Re-enter your password"
        />
        @error('password_confirmation')
          <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
        @enderror
      </div>
      <button 
        type="submit" 
        class="w-full py-3 bg-gradient-to-r from-indigo-500 to-indigo-700 text-white rounded-lg shadow-md hover:bg-gradient-to-l hover-animate transition duration-200">
        Register
      </button>
    </form>
    <p class="text-center text-sm text-gray-600 mt-6">
      Already have an account? 
      <a href="{{ route('login') }}" class="text-indigo-600 font-medium hover:underline">Log in</a>
    </p>
  </div>
</body>
</html>
