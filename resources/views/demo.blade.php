@extends('guest-layout')

@section('content')
    {{-- Section 1 --}}
    <div class="mx-auto px-4 py-12 text-center max-w-6xl">
        <h1 class="font-lato font-bold text-[48px] leading-[100%] tracking-[0%]">
            Lorem Ipsum Dolor
        </h1>

        <p class="mt-6 font-lato font-semibold text-[15px] tracking-[0%] text-center w-[900px] max-w-full mx-auto">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore
            magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
            consequat.
        </p>
    </div>

    {{-- Section 2 --}}

    <div class="relative max-w-7xl mx-auto py-16 space-y-20">
        <!-- Step 1 -->
        <div class="relative flex flex-col md:flex-row items-center justify-center">
            <!-- Text -->
            <div class="md:w-1/3 text-right ">
                <h2 class="text-xl font-bold mb-2 text-primary md:pr-10">Step 01</h2>
                <h2 class="text-2xl font-bold mb-2 md:pr-10">Create Customer</h2>
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
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 2 -->
        <div class="relative flex flex-col md:flex-row items-center justify-center rounded">
            <!-- Image with connector -->
            <div class="relative flex flex-col items-center">
                <!-- Circle Image -->
                <div class="hidden md:block h-48 -mt-[210px] border border-dashed border-gray-700 rounded"></div>
                <div class="relative w-[400px] h-[450px] bg-primary rounded-[10px] z-10">
                    <div
                        class="absolute top-1/2 left-1/2 w-[350px] h-[350px] bg-white rounded-full transform -translate-x-1/2 -translate-y-1/2 flex items-center justify-center overflow-hidden">
                        <img src="images/demo-img1.svg" alt="Step 2" class="w-[230px] h-[300px] object-cover" />
                    </div>
                </div>
            </div>

            <!-- Text -->
            <div class="md:w-1/2 text-left">
                <h2 class="text-xl font-bold mb-2 text-primary md:pl-10">Step 02</h2>
                <h2 class="text-2xl font-bold mb-2 md:pl-10">Create Order</h2>
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
                <h2 class="text-2xl font-bold mb-2 md:pr-10">Show Order</h2>
                <p class="text-md text-gray-700 md:pr-10">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.
                </p>
                <!-- Horizontal Solid Connector Line -->
                <div class="hidden md:block mt-16 w-[334px] border border-dashed border-gray-700 ml-auto rounded">
                </div>

            </div>

            <!-- Image -->
            <div class="relative flex flex-col items-center">
                <div class="hidden md:block h-48 -mt-[200px] border border-dashed border-gray-700 rounded"></div>
                <div class="relative w-[400px] h-[450px] bg-primary rounded-[10px] z-10">
                    <div
                        class="absolute top-1/2 left-1/2 w-[350px] h-[350px] bg-white rounded-full transform -translate-x-1/2 -translate-y-1/2 flex items-center justify-center overflow-hidden">
                        <img src="images/demo-img1.svg" alt="Step 1" class="w-[230px] h-[300px] object-cover" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 4 -->
        <div class="relative flex flex-col md:flex-row items-center justify-center rounded">
            <!-- Image with connector -->
            <div class="relative flex flex-col items-center">
                <!-- Circle Image -->
                <div class="hidden md:block h-48 -mt-[210px] border border-dashed border-gray-700 rounded"></div>
                <div class="relative w-[400px] h-[450px] bg-primary rounded-[10px] z-10">
                    <div
                        class="absolute top-1/2 left-1/2 w-[350px] h-[350px] bg-white rounded-full transform -translate-x-1/2 -translate-y-1/2 flex items-center justify-center overflow-hidden">
                        <img src="images/demo-img1.svg" alt="Step 2" class="w-[230px] h-[300px] object-cover" />
                    </div>
                </div>
            </div>

            <!-- Text -->
            <div class="md:w-1/2 text-left md:pl-10">
                <h2 class="text-xl font-bold mb-2 text-primary">Step 04</h2>
                <h2 class="text-2xl font-bold mb-2">Dashboard</h2>
                <p class="text-md text-gray-700 w-[360px]">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.
                </p>
            </div>
        </div>

    </div>

    {{-- section 3 --}}
    <div>
        <h1 class="font-lato text-md w-[700px] mx-auto text-center"> Lorem ipsum dolor sit amet, consectetur adipiscing
            elit, sed do eiusmod tempor incididunt ut labore et dolore
            magna aliqua. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
            labore et dolore magna aliqua.</h1>
        <div class="mx-10 my-10 bg-[#D9D9D9] h-[500px] flex items-center justify-center rounded-lg shadow">
            
        </div>

    </div>
@endsection
