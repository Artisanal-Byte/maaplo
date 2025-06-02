<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Convergence&display=swap" rel="stylesheet">

    <title>{{ $title ?? 'Maaplo' }}</title>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const btn = document.getElementById("menu-toggle");
            const menu = document.getElementById("mobile-menu");
            btn?.addEventListener("click", () => {
                menu.classList.toggle("hidden");
            });
        });
    </script>
</head>

<body>
    {{-- Navbar --}}
    <nav class="lg:px-20 mx-auto py-4">
        <div class="flex justify-between items-center h-[84px] mx-4 lg:mx-0">
               <x-icon name="logo" class="w-8 h-8 text-blue-500" />

            <!-- Hamburger (Mobile) -->
            <div class="lg:hidden">
                <button id="menu-toggle" class="text-black focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <!-- Desktop Nav -->
            <div class="hidden lg:flex space-x-7 items-center">
                @foreach (['features' => 'Features', 'about' => 'About Us', 'pricing' => 'Pricing', 'blogs' => 'Blogs', 'contact' => 'Contact Us'] as $id => $name)
                    <a href="#{{ $id }}"
                        class="text-black md:text-[15px] lg:text-[18px] font-lato p-2 hover:text-primary hover:border-b hover:border-primary hover:rounded-md transition duration-200">
                        {{ $name }}
                    </a>
                @endforeach
            </div>

            <!-- Desktop Buttons -->
            <div class="hidden lg:flex space-x-4">
                <button
                    class="px-[15px] py-[8px] text-black hover:bg-primary hover:text-white rounded-tl-[8px] rounded-tr-[8px] rounded-bl-[8px] rounded-br-[25px] hover:scale-105 transition-transform duration-200">
                    Demo
                </button>
                <button
                    class="px-[15px] py-[8px] bg-black text-white hover:bg-gray-600 rounded-tl-[8px] rounded-tr-[8px] rounded-bl-[8px] rounded-br-[25px] hover:scale-105 transition-transform duration-200 space-x-2">
                    <a href="/login">Login</a> / <a href="/register">Register</a>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden lg:hidden mt-4 mx-5">
            @foreach (['features' => 'Features', 'about' => 'About Us', 'pricing' => 'Pricing', 'blogs' => 'Blogs', 'contact' => 'Contact Us'] as $id => $name)
                <a href="#{{ $id }}"
                    class="block text-black text-[18px] font-lato hover:text-primary hover:bg-gray-100 rounded p-2 transition">
                    {{ $name }}
                </a>
            @endforeach
            <div class="flex flex-col space-y-2 mt-4">
                <button
                    class="px-[15px] py-[8px] text-black hover:bg-primary hover:text-white rounded-tl-[8px] rounded-tr-[8px] rounded-bl-[8px] rounded-br-[25px] hover:scale-105 transition-transform duration-200">
                    Demo
                </button>
                <button
                    class="px-[15px] py-[8px] bg-black text-white hover:bg-gray-600 rounded-tl-[8px] rounded-tr-[8px] rounded-bl-[8px] rounded-br-[25px] hover:scale-105 transition-transform duration-200 space-x-2">
                    <a href="/login">Login</a> / <a href="/register">Register</a>
                </button>
            </div>
        </div>
    </nav>

    {{-- Page Content --}}
    <main class=" mx-auto">
        @yield('content')
    </main>
</body>

</html>
