@extends('guest-layout')
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@section('content')
    {{-- Section 1 --}}
    <div class="mx-auto px-4 lg:py-12 pt-5 text-center max-w-6xl">
        <h1 class="font-lato font-bold text-[28px] lg:text-[48px] leading-[100%] tracking-[0%] text-black">
            Lorem Ipsum Dolor
        </h1>

        <p class="mt-6 font-lato font-semibold text-[15px] tracking-[0%] text-center w-[900px] max-w-full mx-auto text-black">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore
            magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
            consequat.
        </p>
    </div>

    {{-- Section 2 --}}

    {{-- dasktop view --}}
    <div class="hidden lg:block relative max-w-7xl mx-auto py-16 space-y-20">
        <!-- Step 1 -->
        <div class="relative flex flex-col md:flex-row items-center justify-center">
            <!-- Text -->
            <div class="md:w-1/3 text-right ">
                <h2 class="text-xl font-bold mb-2 text-primary md:pr-10">Step 01</h2>
                <h2 class="text-2xl font-bold mb-2 md:pr-10 text-black">Create Customer</h2>
                <p class="text-md text-gray-700 md:pr-10">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.
                </p>
                <!-- Horizontal Solid Connector Line -->

                <div class="hidden md:block mt-16 w-[334px] border border-dashed border-gray-700 ml-auto rounded">
                </div>

            </div>

            <!-- Image -->
            <div class="relative flex flex-col items-center">
                <div class="relative w-[400px] h-[450px] bg-primary rounded-[10px] z-10">
                    <div
                        class="absolute top-1/2 left-1/2 w-[350px] h-[350px] bg-white rounded-full transform -translate-x-1/2 -translate-y-1/2 flex items-center justify-center overflow-hidden">
                        <img src="images/demo-img1.svg" alt="Step 1" class="w-[230px] h-[300px] object-cover" />
                        <!-- YouTube Icon Overlay -->
                        <img src="images/youtube-icon.svg" alt="YouTube Icon" class="absolute w-8 h-8" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 2 -->
        <div class="relative flex flex-col md:flex-row items-center justify-center rounded">
            <!-- Image with connector -->
            <div class="relative flex flex-col items-center">
                <!-- Circle Image -->
                <div class="hidden md:block h-48 -mt-[210px] border border-dashed border-gray-700 rounded"></div><i class="fa fa-arrow-down text-black"></i>
                <div class="relative w-[400px] h-[450px] bg-primary rounded-[10px] z-10">
                    <div
                        class="absolute top-1/2 left-1/2 w-[350px] h-[350px] bg-white rounded-full transform -translate-x-1/2 -translate-y-1/2 flex items-center justify-center overflow-hidden relative">

                        <!-- Main Image -->
                        <img src="images/createorder.svg" alt="Step 2" class="w-[130px] h-[280px] object-cover" />

                        <!-- YouTube Icon Overlay -->
                        <img src="images/youtube-icon.svg" alt="YouTube Icon" class="absolute w-8 h-8" />
                    </div>

                </div>
            </div>

            <!-- Text -->
            <div class="md:w-1/2 text-left">
                <h2 class="text-xl font-bold mb-2 text-primary md:pl-10">Step 02</h2>
                <h2 class="text-2xl font-bold mb-2 md:pl-10 text-black">Create Order</h2>
                <p class="text-md text-gray-700 w-[360px] md:pl-10">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.
                </p>
                <!-- Horizontal Solid Connector Line -->
                <div class="hidden md:block mt-16 w-[334px] border border-dashed border-gray-700 rounded">
                </div>
            </div>
        </div>

        <!-- Step 3 -->
        <div class="relative flex flex-col md:flex-row items-center justify-center">
            <!-- Text -->
            <div class="md:w-1/3 text-right ">
                <h2 class="text-xl font-bold mb-2 text-primary md:pr-10">Step 03</h2>
                <h2 class="text-2xl font-bold mb-2 md:pr-10 text-black">Show Order</h2>
                <p class="text-md text-gray-700 md:pr-10">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.
                </p>
                <!-- Horizontal Solid Connector Line -->
                <div class="hidden md:block mt-16 w-[334px] border border-dashed border-gray-700 ml-auto rounded">
                </div>

            </div>

            <!-- Image -->
            <div class="relative flex flex-col items-center">
                <div class="hidden md:block h-48 -mt-[208px] border border-dashed border-gray-700 rounded"></div><i class="fa fa-arrow-down text-black"></i>
                <div class="relative w-[400px] h-[450px] bg-primary rounded-[10px] z-10">
                    <div
                        class="absolute top-1/2 left-1/2 w-[350px] h-[350px] bg-white rounded-full transform -translate-x-1/2 -translate-y-1/2 flex items-center justify-center overflow-hidden">
                        <img src="images/showorder.svg" alt="Step 1" class="w-[135px] h-[290px] object-cover" />
                        <!-- YouTube Icon Overlay -->
                        <img src="images/youtube-icon.svg" alt="YouTube Icon" class="absolute w-8 h-8" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 4 -->
        <div class="relative flex flex-col md:flex-row items-center justify-center rounded">
            <!-- Image with connector -->
            <div class="relative flex flex-col items-center">
                <!-- Circle Image -->
                <div class="hidden md:block h-48 -mt-[210px] border border-dashed border-gray-700 rounded"></div><i class="fa fa-arrow-down text-black"></i>
                <div class="relative w-[400px] h-[450px] bg-primary rounded-[10px] z-10">
                    <div
                        class="absolute top-1/2 left-1/2 w-[350px] h-[350px] bg-white rounded-full transform -translate-x-1/2 -translate-y-1/2 flex items-center justify-center overflow-hidden">
                        <img src="images/dashboard.svg" alt="Step 2" class="w-[140px] h-[300px] object-cover" />
                        <!-- YouTube Icon Overlay -->
                        <img src="images/youtube-icon.svg" alt="YouTube Icon" class="absolute w-8 h-8" />
                    </div>
                </div>
            </div>

            <!-- Text -->
            <div class="md:w-1/2 text-left md:pl-10">
                <h2 class="text-xl font-bold mb-2 text-primary">Step 04</h2>
                <h2 class="text-2xl font-bold mb-2 text-black">Dashboard</h2>
                <p class="text-md text-gray-700 w-[360px]">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.
                </p>
            </div>
        </div>

    </div>

    {{-- mobile view --}}
    <div class="block md:hidden max-w-md mx-auto px-8 py-8 flex flex-col items-center relative">

        <!-- Card 1 -->
        <div class="relative bg-primary rounded-[10px] p-6 text-center z-10 w-full">
            <h2 class="text-xl font-bold text-black mb-2">Step 01</h2>
            <h3 class="text-2xl font-bold text-white mb-4">Create Customer</h3>
            <div
                class="w-[150px] h-[150px] bg-white rounded-full mx-auto flex items-center justify-center overflow-hidden mb-4">
                <img src="images/demo-img1.svg" alt="Step 1" class="w-[100px] h-[125px] object-cover" />
                <!-- YouTube Icon Overlay -->
                <img src="images/youtube-icon.svg" alt="YouTube Icon" class="absolute w-8 h-8" />
            </div>
            <p class="text-white text-sm">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
                incididunt ut labore et dolore magna aliqua.</p>

        </div>

        <!-- Vertical dashed line (centered between cards) -->
        <div class="w-0.5 h-20 border-l-2 border-dashed border-black"></div><i class="fa fa-arrow-down text-black"></i> 
        <!-- Card 2 -->
        <div class="relative bg-primary rounded-[10px] p-6 text-center z-10 w-full">
            <h2 class="text-xl font-bold text-black mb-2">Step 02</h2>
            <h3 class="text-2xl font-bold text-white mb-4">Create Order</h3>
            <div
                class="w-[150px] h-[150px] bg-white rounded-full mx-auto flex items-center justify-center overflow-hidden mb-4">
                <img src="images/createorder.svg" alt="Step 2" class="w-[56px] h-[121px] object-cover" />
                <!-- YouTube Icon Overlay -->
                <img src="images/youtube-icon.svg" alt="YouTube Icon" class="absolute w-8 h-8" />
            </div>
            <p class="text-white text-sm">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
                incididunt ut labore et dolore magna aliqua.</p>
        </div>

        <!-- Vertical dashed line (centered between cards) -->
        <div class="w-0.5 h-20 border-l-2 border-dashed border-black"></div><i class="fa fa-arrow-down text-black"></i>

        <!-- Card 3 -->
        <div class="relative bg-primary rounded-[10px] p-6 text-center z-10 w-full">
            <h2 class="text-xl font-bold text-black mb-2">Step 03</h2>
            <h3 class="text-2xl font-bold text-white mb-4">Show Order</h3>
            <div
                class="w-[150px] h-[150px] bg-white rounded-full mx-auto flex items-center justify-center overflow-hidden mb-4">
                <img src="images/showorder.svg" alt="Step 2" class="w-[60px] h-[128px] object-cover" />
                <!-- YouTube Icon Overlay -->
                <img src="images/youtube-icon.svg" alt="YouTube Icon" class="absolute w-8 h-8" />
            </div>
            <p class="text-white text-sm">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
                incididunt ut labore et dolore magna aliqua.</p>
        </div>

        <!-- Vertical dashed line (centered between cards) -->
        <div class="w-0.5 h-20 border-l-2 border-dashed border-black"></div><i class="fa fa-arrow-down text-black"></i>

        <!-- Card 4 -->
        <div class="relative bg-primary rounded-[10px] p-6 text-center z-10 w-full">
            <h2 class="text-xl font-bold text-black mb-2">Step 04</h2>
            <h3 class="text-2xl font-bold text-white mb-4">Dashboard</h3>
            <div
                class="w-[150px] h-[150px] bg-white rounded-full mx-auto flex items-center justify-center overflow-hidden mb-4">
                <img src="images/dashboard.svg" alt="Step 2" class="w-[59px] h-[125px] object-cover" />
                <!-- YouTube Icon Overlay -->
                <img src="images/youtube-icon.svg" alt="YouTube Icon" class="absolute w-8 h-8" />
            </div>
            <p class="text-white text-sm">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
                incididunt ut labore et dolore magna aliqua.</p>
        </div>

    </div>


    </div>

    {{-- section 3 --}}
    <div>
        <h1 class="font-lato text-md lg:w-[700px] px-4 lg:mx-auto text-center text-black"> Lorem ipsum dolor sit amet, consectetur
            adipiscing
            elit, sed do eiusmod tempor incididunt ut labore et dolore
            magna aliqua. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
            labore et dolore magna aliqua.</h1>
        <div class="mx-10 mt-10 bg-[#D9D9D9] h-[500px] flex items-center justify-center rounded-lg shadow">

        </div>

    </div>
@endsection
