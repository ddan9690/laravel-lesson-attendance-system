<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Moi Nyabohanse Girls High School :: Remedial Management System</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet"
    />

    <!-- Boxicons -->
    <link rel="stylesheet" href="{{ asset('remedialsystem/assets/vendor/fonts/boxicons.css') }}" />

    <!-- Tailwind -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js CDN -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="h-full bg-school-green flex items-center justify-center font-sans">

    <div class="w-full max-w-md p-6 bg-white/90 backdrop-blur-sm rounded-lg shadow-lg">
        
        <!-- Logo -->
        <div class="flex justify-center mb-4">
            <img src="{{ asset('remedialsystem/assets/img/logo.png') }}" alt="Logo" class="h-20 w-auto">
        </div>

        <h1 class="text-3xl font-bold text-school-green text-center mb-6">Login</h1>

        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Phone Number -->
            <div>
                <label for="login" class="block text-school-green font-medium mb-1">Email or Phone Number</label>
                <input
                    type="text"
                    id="login"
                    name="login"
                    value="{{ old('login') }}"
                    placeholder="Enter your phone number"
                    class="w-full px-4 py-2 border border-school-accent rounded-lg focus:outline-none focus:ring-2 focus:ring-school-accent focus:border-school-accent"
                    autofocus
                />
                @if ($errors->has('login'))
                    <p class="mt-1 text-red-600 text-sm">{{ $errors->first('login') }}</p>
                @endif
            </div>

            <!-- Password -->
            <div x-data="{ show: false }">
                <label for="password" class="block text-school-green font-medium mb-1">
                    Password <span class="text-school-accent">*</span>
                </label>
                <div class="relative">
                    <input
                        :type="show ? 'text' : 'password'"
                        id="password"
                        name="password"
                        value="{{ old('password') }}"
                        placeholder="************"
                        class="w-full px-4 py-2 border border-school-accent rounded-lg focus:outline-none focus:ring-2 focus:ring-school-accent focus:border-school-accent"
                    />
                    <span @click="show = !show" class="absolute inset-y-0 right-3 flex items-center text-school-accent cursor-pointer">
                        <i :class="show ? 'bx bx-show' : 'bx bx-hide'"></i>
                    </span>
                </div>
                {{-- <p class="text-school-accent text-sm mt-1">Use your phone number as the password</p> --}}
                @if ($errors->has('password'))
                    <p class="mt-1 text-red-600 text-sm">{{ $errors->first('password') }}</p>
                @endif
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
                @php
                    $rememberChecked = isset($_COOKIE['remember_me']) ? 'checked' : '';
                @endphp
                <input
                    type="checkbox"
                    name="remember"
                    id="remember"
                    {{ $rememberChecked }}
                    class="h-4 w-4 text-school-accent focus:ring-school-accent border-school-accent rounded"
                />
                <label for="remember" class="ml-2 block text-school-green">Remember Me</label>
            </div>

            <!-- Submit -->
            <div>
                <button
                    type="submit"
                    class="w-full py-2 px-4 bg-school-accent hover:bg-school-green text-white font-semibold rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-school-accent focus:ring-offset-2"
                >
                    Log in
                </button>
            </div>
        </form>
    </div>

</body>
</html>
