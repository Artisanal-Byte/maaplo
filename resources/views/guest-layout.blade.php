<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	{{--    @vite('resources/css/app.css')--}}
	@vite(['resources/js/app.ts'])
	<link href="https://fonts.googleapis.com/css2?family=Convergence&display=swap" rel="stylesheet">

	<title>{{ $title ?? 'Maaplo' }}</title>
	<script>
      document.addEventListener('DOMContentLoaded', () =>
      {
          const btn = document.getElementById('menu-toggle');
          const menu = document.getElementById('mobile-menu');
          btn?.addEventListener('click', () =>
          {
              menu.classList.toggle('hidden');
          });
      });
	</script>
</head>

<body>
{{-- Navbar --}}
<nav class="lg:px-20 mx-auto py-4 bg-white">
	<div class="flex justify-between items-center h-[84px] mx-4 lg:mx-0">
		<a href="{{ url('/') }}">
			<x-icon name="logo" class="w-8 h-8 text-blue-500" />
		</a>
		{{-- <x-icon name="logo" class="w-8 h-8 text-blue-500 cursor-pointer" /> --}}

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
				<a href="{{ route('home') }}#{{ $id }}"
					 class="text-black md:text-[15px] lg:text-[18px] font-lato p-2 hover:text-primary hover:border-b hover:border-primary hover:rounded-md transition duration-200">
					{{ $name }}
				</a>
			@endforeach
		</div>

		<!-- Desktop Buttons -->
		<div class="hidden lg:flex space-x-4">
			<button onclick="window.location.href='{{ route('demo') }}'"
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
			<button onclick="window.location.href='{{ route('demo') }}'"
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
<main class="mx-auto">
	@yield('content')
</main>

{{-- footer --}}

    <div class="bg-[#2B4146] px-5 lg:px-20 pt-16 pb-10">
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-10 text-white">

            <!-- Logo Column -->
            <div class="flex lg:col-span-2 flex-col gap-4 lg:-mt-5">
                <div>
                    <x-icon name="maaplo" class="w-24 h-24" />
                </div>
                <!-- Repeatable Link Columns -->
                <div>
                    {{-- <h1 class="font-lato font-extrabold text-[20px] leading-none mb-4">Product</h1>
                <ul class="space-y-3">
                    <li class="font-lato font-medium text-[14px] leading-none">Lorem Ipsum</li>
                    <li class="font-lato font-medium text-[14px] leading-none">Lorem Ipsum</li>
                    <li class="font-lato font-medium text-[14px] leading-none">Lorem Ipsum</li>
                    <li class="font-lato font-medium text-[14px] leading-none">Lorem Ipsum</li>
                    <li class="font-lato font-medium text-[14px] leading-none">Lorem Ipsum</li>
                </ul> --}}
                    <p class="font-lato font-semibold text-[15px] tracking-[0] lg:w-[400px]">
                        MaapLo is your smart partner, built to simplify garment workflows with deep insight into the
                        personalised clothing industry.

                    </p>
                </div>
            </div>
            <div>
                <h1 class="font-lato font-extrabold text-[20px] leading-none mb-4">Quick Links</h1>
                <ul class="space-y-1">
                    @foreach ([
        'features' => 'Features',
        'about' => 'About Us',
        'pricing' => 'Pricing',
        'blogs' => 'Blogs',
        'contact' => 'Contact Us',
    ] as $id => $label)
                        <li>
                            <a href="{{ route('home') }}#{{ $id }}"
                                class="font-lato font-medium text-[14px] leading-none hover:text-primary">
                                {{ $label }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h1 class="font-lato font-extrabold text-[20px] leading-none mb-6">Product</h1>
                <ul class="space-y-3">
                    <li class="font-lato font-medium text-[14px] leading-none">Privacy Policy</li>
                    <li class="font-lato font-medium text-[14px] leading-none">Terms & Conditions</li>
                    <li class="font-lato font-medium text-[14px] leading-none">Pricing</li>
                    <li class="font-lato font-medium text-[14px] leading-none">Blogs</li>
                    {{-- <li class="font-lato font-medium text-[14px] leading-none">Lorem Ipsum</li> --}}
                </ul>
            </div>
            <div class="lg:col-span-2">
                <h1 class="font-lato font-extrabold text-[20px] leading-none mb-4">Follow Us</h1>
                <div class="flex gap-4 mt-10">
                    <x-icon name="instagram" class="w-24 h-auto" />
                    <x-icon name="facebook" class="w-24 h-auto" />
                    <x-icon name="twitter" class="w-24 h-auto" />
                    <x-icon name="linkedin" class="w-24 h-auto" />
                </div>
                <div class="lg:flex gap-10 mt-5 hidden">
                    <input type="text" placeholder="Enter your email"
                        class="mt-4 w-full border text-black border-gray-300 rounded-md p-3 focus:outline-none" />
                    <button type="submit"
                        class="mt-4 font-medium px-[15px] py-1 lg:py-[8px] w-[100px] bg-white text-black hover:bg-gray-600 rounded-md hover:scale-105 transition-transform duration-200">
                        <a href="/">Subscribe</a>
                </div>
            </div>
        </div>

	<div class="text-center border-t lg:border-none mt-20">
		<p
		 class="lg:hidden block font-lato mt-5 lg:font-semibold text-[12px] leading-[100%] tracking-[0] text-center text-white">
			Privacy Policy</p>
		<p
		 class="text-center mt-5 lg:mt-10 font-lato lg:font-semibold text-[15px] leading-none tracking-[0] text-white">
			© 2025 artisanalbyte. All rights reserved.
		</p>
		<p
		 class="lg:hidden block font-lato mt-5 lg:font-semibold text-[12px] leading-[100%] tracking-[0] text-center text-white">
			Privacy Policy</p>
	</div>
</div>
</body>

</html>
