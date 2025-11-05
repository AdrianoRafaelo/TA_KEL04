<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - SIPODA MR</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logoSIPODA2.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-screen flex items-center justify-center bg-white">

    <div class="flex w-full max-w-6xl items-center justify-center px-6">
        
        <div class="flex items-center space-x-6"> 
            <img src="{{ asset('img/logoSIPODA2.png') }}" alt="Logo SIPODA MR" class="w-44 h-44"> 
            <div>
                <h1 class="text-5xl font-bold text-blue-700">SIPODA MR</h1> 
                <p class="text-lg text-blue-500 leading-relaxed">
                    Sistem Informasi dan Pengelolaan Administrasi <br>
                    Manajemen Rekayasa
                </p>
            </div>
        </div>

       
        <div class="ml-16 bg-blue-100 p-10 rounded-lg shadow-md w-96"> 
            <h2 class="text-center text-xl font-semibold text-gray-800 mb-2">Silahkan Masuk</h2>

            <!-- Tab pilihan role -->
            <!-- <div class="flex justify-center space-x-4 text-sm mb-6">
                <a href="#" class="text-blue-700 font-semibold border-b-2 border-blue-700">Dosen</a>
                <a href="#" class="text-gray-600 hover:text-blue-700">Koor & Asisten dosen</a>
            </div> -->

            @if ($errors->any())
                <div class="bg-red-100 text-red-600 p-2 rounded mb-4">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <input type="text" name="username" placeholder="username" 
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                </div>

                <div>
                    <input type="password" name="password" placeholder="password" 
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                </div>

                <button type="submit" 
                    class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                    Masuk
                </button>
            </form>

            @if(session('role') === 'dosen')
                <a href="/dosen.dosen" class="block text-center text-blue-700 font-semibold mt-4">
                    Menu Admin
                </a>
            @endif
        </div>
    </div>
</body>
</html>
