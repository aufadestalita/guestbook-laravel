<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - KSOP Banten</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-sm border-t-4 border-blue-800">
        <h2 class="text-2xl font-bold text-center mb-2 text-gray-800">Login Admin</h2>
        <p class="text-xs text-center text-gray-500 mb-6">Silakan masuk untuk memantau tamu</p>

        <!-- Pesan Error jika password salah -->
        @if($errors->any())
            <div class="bg-red-100 text-red-600 px-3 py-2 rounded-lg mb-4 text-sm font-medium">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-1 text-gray-700">Email Akses</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="Contoh: admin@ksop.go.id"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-semibold mb-1 text-gray-700">Password</label>
                <input type="password" name="password" required placeholder="Masukkan password"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <button type="submit" class="w-full bg-blue-800 text-white font-semibold py-2 rounded-lg hover:bg-blue-900 transition">
                Masuk Dashboard
            </button>
        </form>
    </div>

</body>
</html>