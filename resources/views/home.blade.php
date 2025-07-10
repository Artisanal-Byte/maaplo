@extends('guest-layout')
<link href="https://fonts.googleapis.com/css2?family=Convergence&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
    .hover-rotate-x {
        transition: transform 0.1ms ease-in-out;
        transform-style: preserve-3d;
    }

    .hover-rotate-x:hover {
        transform: rotateY(360deg);
    }
</style>
<script src="{{ asset('js/custom.js') }}" defer></script>
<script>
    function toggleDropdown(id) {
        const el = document.getElementById(id);
        if (el.style.display === "none" || el.style.display === "") {
            el.style.display = "block";
        } else {
            el.style.display = "none";
        }
    }
</script>

@section('content')
    {{-- section 1 --}}
    <div class="flex flex-col lg:flex-row lg:justify-between lg:px-20 px-0 bg-white dark:bg-white">
        <div class="flex justify-center lg:justify-start">
            <div class="lg:py-20 px-5 lg:px-0 lg:w-[600px] text-center lg:text-left">
                <h1
                    class="font-convergence text-[30px] mt-4 lg:mt-8 md:text-[60px] lg:text-[65px] leading-[1.2] tracking-tight text-black dark:text-black">
                    Measure Once. Store Forever.
                </h1>

                <!-- Description -->
                <p class="mt-4 lg:text-[16px] text-[18px] leading-[1.6] font-convergence text-black dark:text-black">
                    MaapLo is a smart, made-to-measure fashion app designed to manage measurements, orders, client profiles,
                    and billing with ease.
                </p>

                <button
                    class="px-[15px] py-[8px] mt-10 bg-black text-white hover:bg-gray-600 rounded-tl-[8px] rounded-tr-[8px] rounded-bl-[8px] rounded-br-[25px] hover:scale-105 transition-transform duration-200 space-x-2">
                    <a onclick="window.location.href='{{ route('demo') }}'">Book a Free Demo</a>
                </button>
            </div>
        </div>

        <div class="px-5 md:px-24 lg:px-0 mt-10 lg:mt-0 mx-auto lg:mx-0">
            <x-icon name="home" class="w-8 h-8 text-blue-500" />
        </div>
    </div>

    {{-- section 2 --}}
    <div>
        <div class="px-5 lg:px-20 pt-10  bg-white dark:bg-white">
            <div
                class="lg:h-[250px] bg-[#BEDCDF] border-[12px] border-[#5CA7AF]  rounded-[42px] shadow-[0px_2px_9.9px_0px_#88DDE340] flex items-center">
                <div class="flex flex-col lg:flex-row justify-between w-full">
                    <div class="-ml-10 -mt-10 lg:mt-2 w-[180px] h-[150px] item-center justify-center item-center space-x-2">
                        <x-icon name="section2" class="w-8 h-8 text-blue-500" />
                    </div>
                    <div class="flex flex-col items-center text-center mt-0 lg:mt-5 ml-0 lg:ml-[180px]">
                        <h1
                            class="font-lato font-bold text-[20px] px-2 lg:px-0 md:text-[32px] lg:text-[30px] leading-[100%] max-w-[822px] text-black">
                            Smarter Management for Growing Businesses
                        </h1>
                        <p
                            class="font-lato font-normal text-[12px] lg:text-[16px] mt-2 leading-[152%] max-w-[590px] px-5 lg:px-10 text-black">
                            MaapLo simplifies business tasks, tracking orders, managing clients, and invoicing—in one easy
                            app,
                            saving you time and boosting productivity.
                        </p>
                        <button
                            class="mt-4 px-[15px] py-1 lg:py-[8px] w-[100px] bg-black text-white hover:bg-gray-600 rounded-tl-[8px] rounded-tr-[8px] rounded-bl-[8px] rounded-br-[25px] hover:scale-105 transition-transform duration-200">
                            <a href="/">Explore</a>
                        </button>
                    </div>
                    <div>
                        <x-icon name="section2-img" class="w-8 h-8 text-blue-500" />
                    </div>
                </div>

            </div>
        </div>
    </div>
    {{-- section 3 --}}
    <div id="features" class="px-5 lg:px-20 bg-white dark:bg-white">
        <h1
            class="font-[400] text-black pt-16 lg:pt-18 dark:text-black text-[40px] leading-[100%] tracking-[0%] text-center font-[Convergence]">
            Features
        </h1>

        {{-- mobile view --}}
        <!-- Slides Container -->
        <div class="block md:hidden relative mt-10">
            <div
                class="mySlides-feature rounded-[10px]  bg-[linear-gradient(0deg,_#FFFFFF_40.9%,_rgba(229,255,255,0.6)_116.83%)] shadow-[0px_0px_4px_0px_#00000040] py-8 px-4">
                <img src="/images/section3-img1.svg" class="mx-auto" />
                <h1
                    class="mt-6 text-[18px] lg:text-[25px] text-black dark:text-black leading-[130%] font-normal font-[Convergence]">
                    Measurement Management & Smart Dashboard
                </h1>

                <p class="mt-3 text-[14px] leading-[152%] font-medium font-[Lato] text-black dark:text-black">
                    Capture precise client measurements once and access them anytime—no repeats, no errors. With
                    MaapLo’s
                    smart dashboard, track orders, deadlines, and progress all in one glance, keeping your workflow
                    sharp,
                    simple, and stress-free.

                </p>
                {{-- <button
                    class="mt-3 flex items-center gap-1 text-[15px] font-medium leading-[152%] text-primary font-[Lato]">
                    Learn More
                    <span class="text-[15px] font-bold ml-2">→</span>
                </button> --}}

            </div>

            <div
                class="mySlides-feature rounded-[10px] bg-[linear-gradient(0deg,_#FFFFFF_40.9%,_rgba(255,252,229,0.6)_116.83%)] shadow-[0px_0px_4px_0px_#00000040] py-8 px-4">
                <img src="/images/feature-img2.svg" class="mx-auto" />
                <h1
                    class="mt-6 text-[18px] lg:text-[25px] leading-[130%] font-normal font-[Convergence text-black dark:text-black]">
                    Custom
                    Order
                    Management</h1>
                <p class="mt-3 text-[14px] leading-[152%] font-medium font-[Lato] text-black dark:text-black">
                    From first request to final delivery, manage every step of a custom order with ease. Add design
                    notes,
                    fabric preferences, deadlines, and more—all in one place. MaapLo simplifies the process so you
                    can focus
                    on delivering work that fits every client’s unique vision.
                </p>
                {{-- <button
                    class="mt-10 flex items-center gap-1 text-[15px] font-medium leading-[152%] text-primary font-[Lato]">
                    Learn More
                    <span class="text-[15px] font-bold ml-2">→</span>
                </button> --}}
            </div>

            <div
                class="mySlides-feature rounded-[10px]  rounded-[10px] shadow-[0px_0px_4px_0px_rgba(0,0,0,0.25)] bg-[linear-gradient(0deg,_#FFFFFF_40.9%,_rgba(229,255,229,0.6)_116.83%)] py-8 px-4">
                <img src="/images/Feature-img3.svg" class="mx-auto" />
                <h1
                    class="mt-6 text-[18px] lg:text-[25px] leading-[130%] font-normal font-[Convergence] text-black dark:text-black">
                    Client
                    Directory
                    with Notes</h1>
                <p class="mt-3 text-[14px] leading-[152%] font-medium font-[Lato] text-black dark:text-black">
                    Stay organized with a smart client database that does more than just store names. Add notes,
                    preferences, past orders, and special requests to offer personalized service every time. It’s
                    like
                    having a memory that never forgets—so your clients always feel valued.

                </p>
                {{-- <button
                    class="mt-10 flex items-center gap-1 text-[15px] font-medium leading-[152%] text-primary font-[Lato]">
                    Learn More
                    <span class="text-[15px] font-bold ml-2">→</span>
                </button> --}}
            </div>

            <div
                class="mySlides-feature rounded-[10px] bg-[linear-gradient(0deg,_#FFFFFF_40.9%,_rgba(229,_235,_255,_0.6)_116.83%)] shadow-[0px_0px_4px_0px_#00000040]  py-8 px-4">
                {{-- <x-icon name="section3-img4" class="w-8 h-8 text-blue-500" /> --}}
                <img src="/images/Feature-img4.svg" class="mx-auto" />
                <h1
                    class="mt-6 text-[18px] lg:text-[25px] leading-[130%] font-normal font-[Convergence] text-black dark:text-black">
                    Order
                    Tracking &
                    Lifecycle Management
                </h1>
                <p class="mt-3 text-[14px] leading-[152%] font-medium font-[Lato] text-black dark:text-black">
                    Stay in control of every order. MaapLo tracks each stage—from placement and measurements to
                    delivery and
                    payment—all in one place. Real-time updates keep you and your clients informed, making the
                    process
                    smooth and stress-free.
                </p>
                {{-- <button
                    class="mt-3 flex items-center gap-1 text-[15px] font-medium leading-[152%] text-primary font-[Lato]">
                    Learn More
                    <span class="text-[15px] font-bold ml-2">→</span>
                </button> --}}
            </div>

            <div
                class="mySlides-feature rounded-[10px] bg-[linear-gradient(0deg,_#FFFFFF_40.9%,_rgba(255,_237,_229,_0.6)_116.83%)] shadow-[0px_0px_4px_0px_#00000040] py-8 px-4">
                <img src="/images/Feature-img-5.svg" class="mx-auto" />
                <h1
                    class="mt-6 text-[18px] lg:text-[25px] leading-[130%] font-normal font-[Convergence] text-black dark:text-black">
                    Mobile-first, easy to use interface
                </h1>

                <p class="mt-3 text-[14px] leading-[152%] font-medium font-[Lato] text-black dark:text-black">
                    Built for busy hands and fast-paced workdays, MaapLo’s clean and intuitive design helps you
                    manage tasks
                    effortlessly on your phone or tablet. Whether you're at the studio, a client’s place, or on the
                    move,
                    everything you need is right in your pocket.

                </p>
                {{-- <button
                    class="mt-3 flex items-center gap-1 text-[15px] font-medium leading-[152%] text-primary font-[Lato]">
                    Learn More
                    <span class="text-[15px] font-bold ml-2">→</span>
                </button> --}}
            </div>

            <div
                class="mySlides-feature rounded-[10px] bg-[linear-gradient(0deg,_#FFFFFF_40.9%,_rgba(250,_229,_255,_0.6)_116.83%)] shadow-[0px_0px_4px_0px_#00000040] py-8 px-4">
                <img src="/images/Feature-img6.svg" class="mx-auto" />
                <h1
                    class="mt-6 text-[18px] lg:text-[25px] leading-[130%] font-normal font-[Convergence] text-black dark:text-black">
                    Secure Cloud Access
                </h1>

                <p class="mt-3 text-[14px] leading-[152%] font-medium font-[Lato] text-black dark:text-black">
                    Work with confidence wherever you are. MaapLo stores your data safely in the cloud, ensuring
                    it’s always
                    backed up, protected, and accessible—whether you're at home, in the studio, or on the move. Your
                    work
                    stays secure, so you can stay focused.

                </p>
                {{-- <button
                    class="mt-10 flex items-center gap-1 text-[15px] font-medium leading-[152%] text-primary font-[Lato]">
                    Learn More
                    <span class="text-[15px] font-bold ml-2">→</span>
                </button> --}}

            </div>

            <!-- Dots -->
            <div class="flex justify-center mt-10 gap-2">
                <span class="dot-feature w-3 h-3 bg-gray-300 rounded-full cursor-pointer"
                    onclick="currentSlideFeature(1)"></span>
                <span class="dot-feature w-3 h-3 bg-gray-300 rounded-full cursor-pointer"
                    onclick="currentSlideFeature(2)"></span>
                <span class="dot-feature w-3 h-3 bg-gray-300 rounded-full cursor-pointer"
                    onclick="currentSlideFeature(3)"></span>
                <span class="dot-feature w-3 h-3 bg-gray-300 rounded-full cursor-pointer"
                    onclick="currentSlideFeature(4)"></span>
                <span class="dot-feature w-3 h-3 bg-gray-300 rounded-full cursor-pointer"
                    onclick="currentSlideFeature(5)"></span>
                <span class="dot-feature w-3 h-3 bg-gray-300 rounded-full cursor-pointer"
                    onclick="currentSlideFeature(6)"></span>
            </div>
        </div>
        {{-- dasktop view --}}
        <div class="mt-20 hidden md:grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 justify-center items-center gap-5">
            {{-- Features 1 --}}
            <div
                class="rounded-[10px] bg-[linear-gradient(0deg,_#FFFFFF_40.9%,_rgba(229,255,255,0.6)_116.83%)] shadow-[0px_0px_4px_0px_#00000040] py-8 px-4">
                <img src="/images/section3-img1.svg" class="mx-auto" />
                <h1
                    class="mt-6 text-[18px] lg:text-[25px] leading-[130%] font-normal font-[Convergence] text-black dark:text-black">
                    Measurement Management & Smart Dashboard
                </h1>

                <p class="mt-3 text-[14px] leading-[152%] font-medium font-[Lato] text-black dark:text-black">
                    Capture precise client measurements once and access them anytime—no repeats, no errors. With
                    MaapLo’s
                    smart dashboard, track orders, deadlines, and progress all in one glance, keeping your workflow
                    sharp,
                    simple, and stress-free.

                </p>
                {{-- <button
                    class="mt-3 flex items-center gap-1 text-[15px] font-medium leading-[152%] text-primary font-[Lato]">
                    Learn More
                    <span class="text-[15px] font-bold ml-2">→</span>
                </button> --}}
            </div>
            {{-- Features 2 --}}
            <div
                class="rounded-[10px] bg-[linear-gradient(0deg,_#FFFFFF_40.9%,_rgba(255,252,229,0.6)_116.83%)] shadow-[0px_0px_4px_0px_#00000040] py-8 px-4">
                <img src="/images/feature-img2.svg" class="mx-auto" />
                <h1
                    class="mt-6 text-[18px] lg:text-[25px] leading-[130%] font-normal font-[Convergence] text-black dark:text-black">
                    Custom
                    Order
                    Management</h1>
                <p class="mt-3 mb-7 text-[14px] leading-[152%] font-medium font-[Lato] text-black dark:text-black">
                    From first request to final delivery, manage every step of a custom order with ease. Add design
                    notes,
                    fabric preferences, deadlines, and more—all in one place. MaapLo simplifies the process so you
                    can focus
                    on delivering work that fits every client’s unique vision.
                </p>
                {{-- <button
                    class="mt-10 flex items-center gap-1 text-[15px] font-medium leading-[152%] text-primary font-[Lato]">
                    Learn More
                    <span class="text-[15px] font-bold ml-2">→</span>
                </button> --}}
            </div>
            {{-- Features 3 --}}
            <div
                class="rounded-[10px]  rounded-[10px] shadow-[0px_0px_4px_0px_rgba(0,0,0,0.25)] bg-[linear-gradient(0deg,_#FFFFFF_40.9%,_rgba(229,255,229,0.6)_116.83%)] py-8 px-4">
                <img src="/images/Feature-img3.svg" class="mx-auto" />
                <h1
                    class="mt-6 text-[18px] lg:text-[25px] leading-[130%] font-normal font-[Convergence] text-black dark:text-black">
                    Client
                    Directory
                    with Notes</h1>
                <p class="mt-3 mb-7 text-[14px] leading-[152%] font-medium font-[Lato] text-black dark:text-black">
                    Stay organized with a smart client database that does more than just store names. Add notes,
                    preferences, past orders, and special requests to offer personalized service every time. It’s
                    like
                    having a memory that never forgets—so your clients always feel valued.

                </p>
                {{-- <button
                    class="mt-10 flex items-center gap-1 text-[15px] font-medium leading-[152%] text-primary font-[Lato]">
                    Learn More
                    <span class="text-[15px] font-bold ml-2">→</span>
                </button> --}}
            </div>
            {{-- Features 4 --}}
            <div
                class="rounded-[10px] bg-[linear-gradient(0deg,_#FFFFFF_40.9%,_rgba(229,_235,_255,_0.6)_116.83%)] shadow-[0px_0px_4px_0px_#00000040]  py-8 px-4">
                <img src="/images/Feature-img4.svg" class="mx-auto" />
                <h1
                    class="mt-6 text-[18px] lg:text-[25px] leading-[130%] font-normal font-[Convergence] text-black dark:text-black">
                    Order
                    Tracking &
                    Lifecycle Management
                </h1>
                <p class="mt-3 text-[14px] leading-[152%] font-medium font-[Lato] text-black dark:text-black">
                    Stay in control of every order. MaapLo tracks each stage—from placement and measurements to
                    delivery and
                    payment—all in one place. Real-time updates keep you and your clients informed, making the
                    process
                    smooth and stress-free.
                </p>
                {{-- <button
                    class="mt-3 flex items-center gap-1 text-[15px] font-medium leading-[152%] text-primary font-[Lato]">
                    Learn More
                    <span class="text-[15px] font-bold ml-2">→</span>
                </button> --}}

                <!-- content -->
            </div>

            {{-- Features 5 --}}
            <div
                class="rounded-[10px] bg-[linear-gradient(0deg,_#FFFFFF_40.9%,_rgba(255,_237,_229,_0.6)_116.83%)] shadow-[0px_0px_4px_0px_#00000040] py-8 px-4">
                <img src="/images/Feature-img-5.svg" class="mx-auto" />
                <h1
                    class="mt-6 text-[18px] lg:text-[25px] leading-[130%] font-normal font-[Convergence] text-black dark:text-black">
                    Mobile-first, easy to use interface
                </h1>

                <p class="mt-3 text-[14px] leading-[152%] font-medium font-[Lato] text-black dark:text-black">
                    Built for busy hands and fast-paced workdays, MaapLo’s clean and intuitive design helps you
                    manage tasks
                    effortlessly on your phone or tablet. Whether you're at the studio, a client’s place, or on the
                    move,
                    everything you need is right in your pocket.

                </p>
                {{-- <button
                    class="mt-3 flex items-center gap-1 text-[15px] font-medium leading-[152%] text-primary font-[Lato]">
                    Learn More
                    <span class="text-[15px] font-bold ml-2">→</span>
                </button> --}}
            </div>

            {{-- Features 6 --}}
            <div
                class="rounded-[10px] bg-[linear-gradient(0deg,_#FFFFFF_40.9%,_rgba(250,_229,_255,_0.6)_116.83%)] shadow-[0px_0px_4px_0px_#00000040] py-8 px-4">
                <img src="/images/Feature-img6.svg" class="mx-auto" />
                <h1
                    class="mt-6 text-[18px] lg:text-[25px] leading-[130%] font-normal font-[Convergence] text-black dark:text-black">
                    Secure Cloud Access
                </h1>

                <p class="mt-3 mb-7 text-[14px] leading-[152%] font-medium font-[Lato] text-black dark:text-black">
                    Work with confidence wherever you are. MaapLo stores your data safely in the cloud, ensuring
                    it’s always
                    backed up, protected, and accessible—whether you're at home, in the studio, or on the move. Your
                    work
                    stays secure, so you can stay focused.

                </p>
                {{-- <button
                    class="mt-10 flex items-center gap-1 text-[15px] font-medium leading-[152%] text-primary font-[Lato]">
                    Learn More
                    <span class="text-[15px] font-bold ml-2">→</span>
                </button> --}}

            </div>

        </div>
    </div>

    {{-- section 4 --}}
    <div id="about" class="px-5 lg:px-20  bg-white dark:bg-white">
        <h1
            class="font-[400] text-[25px] lg:text-[50px] pt-16 lg:pt-20 leading-[100%] tracking-[0%] text-center font-[Convergence] text-black">
            Who we are?
        </h1>
        <div
            class="flex flex-col lg:flex-row justify-center lg:justify-between mt-5 lg:mt-10 lg:gap-20 items-center lg:items-start">
            <div class="py-5 lg:py-20 lg:w-[700px] text-center lg:text-left">
                <h1
                    class="font-[Convergence] font-normal text-[30px] lg:text-[40px] leading-[100%] tracking-[0%] text-black">
                    Vision
                </h1>

                <p class="mt-6 font-[Lato] font-normal text-[15px] leading-[152%] tracking-[0%] text-black">
                    At MaapLo, we envision a world where craftsmanship meets convenience. We're transforming how
                    garment
                    professionals manage their daily operations—bringing everything from order tracking and precise
                    measurement recording to client profiles and invoicing into one intuitive platform. More than
                    just a
                    tool, MaapLo is a thoughtful companion built with deep appreciation for the art of personalized
                    clothing.
                </p>
                <p class="mt-6 font-[Lato] font-normal text-[15px] leading-[152%] tracking-[0%] text-black">
                    Whether you're running a boutique, managing a studio, working independently, or just starting
                    out as a
                    student, MaapLo empowers you to stay efficient, organized, and connected—while focusing on what
                    you do
                    best: creating with care and precision.
                </p>

                <p class="mt-6 font-[Lato] font-normal text-[15px] leading-[152%] tracking-[0%] text-black">Our mission is
                    to
                    bridge
                    tradition and technology, helping skilled professionals embrace digital tools without losing the
                    personal touch that defines their work.

                </p>
                <button
                    class="mt-5 px-[15px] py-1 lg:py-[8px] w-[100px] bg-black text-white hover:bg-gray-600 rounded-tl-[8px] rounded-tr-[8px] rounded-bl-[8px] rounded-br-[25px] hover:scale-105 transition-transform duration-200">
                    <a href="/">View</a>
                </button>
            </div>
            <div class="mx-10 mt-6 lg:mt-0">
                <x-icon name="section4" class="w-8 h-8 text-blue-500" />
            </div>
        </div>


    </div>

    {{-- section 5 --}}
    <div id="pricing" class="lg:px-20 px-0 bg-white dark:bg-white">
        <div
            class="py-10 lg:py-20 pt-5 rounded-[20px] bg-[radial-gradient(50%_50%_at_50%_50%,rgba(219,245,247,0.5)_0%,rgba(237,250,251,0.5)_100%)] flex flex-col items-center justify-center text-center">
            <div>
                <h1
                    class="font-[400] text-[25px] lg:text-[40px] leading-[1] tracking-medium font-[Convergence] text-black">
                    Simple Plans. Seamless Value!
                </h1>
                <p
                    class="font-lato font-normal text-[15px] leading-[1.52] tracking-normal text-center text-[#5B5B5B] mt-4">
                    Crafted for Every Budget, Styled for Every Need.
                </p>
                <div
                    class="mt-10 lg:w-[600px] h-[58px] rounded-[72px] gap-[10px] p-[5px] bg-white shadow-[0_0_6.4px_#16789340] flex items-center justify-center">
                    <button id="btn1" onclick="setActiveButton('btn1')"
                        class="bg-primary text-white px-3 lg:px-6 py-3 rounded-full transition-all w-full">
                        Monthly
                    </button>
                    <button id="btn2" onclick="setActiveButton('btn2')"
                        class="text-black px-3 lg:px-6 py-3 rounded-full transition-all w-full">
                        Yearly (save 20%)
                    </button>
                </div>
            </div>

            {{-- mobile view --}}
            {{-- card  --}}
            {{-- mobile view with dynamic dropdowns --}}
            <div class="lg:hidden block flex flex-col items-center gap-5 mt-20">
                @foreach ($subscriptionPlans as $index => $plan)
                    @php
                        $dropdownId = 'dropdownContent' . $index;
                        $toggleId = 'toggleDropdown' . $index;
                    @endphp

                    <div
                        class="w-[320px] lg:w-[350px] border-t-[12px] border-primary rounded-[10px] bg-white shadow-[0px_4px_8.7px_0px_#16789340]">

                        <!-- Header - Click to toggle dropdown -->
                        <div id="{{ $toggleId }}" onclick="toggleDropdown('{{ $dropdownId }}')"
                            class="cursor-pointer p-4 bg-[#FBFBFB]">
                            <div class="flex flex-row justify-between">
                                <div>
                                    <h1
                                        class="text-[30px] leading-[100%] font-normal text-left font-convergence text-black">
                                        {{ ucfirst($plan->plan_title) }} Plan
                                    </h1>
                                </div>
                                <div>
                                    <x-icon name="icon-down" id="iconDown{{ $index }}" />
                                </div>
                            </div>

                            <h1 class="text-[24px] mt-3 leading-[100%] font-extrabold text-left font-lato text-black plan-price"
                                data-monthly="{{ $plan->plan_price }}" id="price-mobile-{{ $index }}">
                                ${{ $plan->plan_price }}
                            </h1>
                            <p class="mt-4 text-[15px] leading-[152%] font-medium text-left font-lato text-black">
                                {{ $plan->plan_currency }} / {{ $plan->plan_description }}
                            </p>
                        </div>

                        <!-- Dropdown content -->
                        <div id="{{ $dropdownId }}" style="display: none;">
                            <div class="border-gray-300 mt-6 pt-4 px-6">
                                @if (is_array($plan->features) || is_object($plan->features))
                                    <ul
                                        class="text-left text-[15px] leading-[152%] font-lato border-y border-primary divide-y divide-[#167893] marker:text-primary">
                                        @foreach ((array) $plan->features as $feature)
                                            <li class="py-3 flex items-center gap-4">
                                                <i class="fa fa-check bg-primary text-white p-2 rounded-full"></i>
                                                {{ $feature }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-sm italic text-gray-500 text-center">No features available</p>
                                @endif
                            </div>
                            <div class="px-6 mt-8 mb-10 lg:mb-0">
                                <button
                                    class="w-full mt-5 px-[15px] text-lg font-bold py-1 lg:py-[8px] bg-primary text-white hover:bg-[#DEEFF4] rounded-tl-[8px] rounded-tr-[8px] rounded-bl-[8px] rounded-br-[25px] hover:scale-105 transition-transform duration-200">
                                    <a href="/">Get Started</a>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>


            {{-- dasktop view --}}
            {{-- card  --}}
            <div class="hidden lg:grid grid-cols-3 gap-8 mt-20 px-10">

                @foreach ($subscriptionPlans as $plan)
                    <div
                        class="w-[320px] lg:w-[350px] lg:h-[600px] border-t-[12px] border-primary rounded-[10px] bg-white shadow-[0px_4px_8.7px_0px_#16789340]">
                        <h1 class="mt-12 text-[30px] leading-[100%] font-normal text-center font-convergence text-black">
                            {{ $plan['plan_title'] }} Plan
                        </h1>
                        <x-icon name="start" class="" />
                        <h1 class="text-[30px] leading-[100%] font-extrabold text-center font-lato -mt-12 text-black plan-price"
                            data-monthly="{{ $plan['plan_price'] }}" id="price-desktop-{{ $loop->index }}">
                            ${{ $plan['plan_price'] }}
                        </h1>


                        <p class="mt-4 text-[15px] leading-[152%] font-medium text-center font-lato text-black">
                            {{ $plan['plan_currency'] }} / {{ $plan['plan_description'] }}
                        </p>

                        <div class="border-gray-300 mt-6 pt-4 px-6 text-black">
                            @php
                                // Check if features is a string (likely JSON), and decode it if true
                                if (is_string($plan['features'])) {
                                    $plan['features'] = json_decode($plan['features'], true); // Decode JSON string into array
                                }
                            @endphp

                            @if (is_array($plan['features']) && count($plan['features']) > 0)
                                <ul
                                    class="text-left text-[15px] leading-[152%] font-lato border-y border-primary divide-y divide-[#167893] marker:text-primary">
                                    @foreach ($plan['features'] as $feature)
                                        <li class="py-3 flex items-center gap-4">
                                            <i class="fa fa-check bg-primary text-white p-2 rounded-full"></i>
                                            {{ $feature }}
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p>No features available</p>
                            @endif
                        </div>

                        <div class="px-6 mt-8 mb-10 lg:mb-0">
                            <button
                                class="w-full mt-5 px-[15px] text-lg text-bold py-1 lg:py-[8px] bg-primary text-white hover:bg-[#75959e] rounded-tl-[8px] rounded-tr-[8px] rounded-bl-[8px] rounded-br-[25px] hover:scale-105 transition-transform duration-200">
                                <a href="/">Get Started</a>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    {{-- section 6 --}}
    <div class="px-5 lg:px-20 py-10 lg:py-16 lg:pt-20 bg-white dark:bg-white">
        <h1
            class="font-[400] text-[25px] lg:text-[40px] leading-[100%] tracking-[0%] text-center font-[Convergence] text-black">
            Why Choose MaapLo?
        </h1>
        <p
            class="lg:w-[713px] lg:h-[54px] mt-5 mx-auto font-lato font-normal text-[16px] leading-[152%] tracking-[0%] text-center text-black">
            MaapLo is a smart, budget-friendly app made for tailors — streamlining tasks with offline access and
            client-focused tools. Tailoring made simple.
        </p>


        <div class="flex flex-col lg:flex-row justify-between mt-10 overflow-hidden">
            <div class="flex flex-col gap-10 lg:max-w-[45%] items-center lg:items-start text-center lg:text-left">
                <div>
                    <h1 class="lg:mt-5 font-lato text-[18px] font-semibold leading-[100%] tracking-[0%] text-primary">
                        1. Smart Design for Your Craft
                    </h1>
                    <p
                        class="mt-4 font-lato font-normal text-[15px] w-[480px] leading-[152%] tracking-[0%] mt-2 text-black">
                        MaapLo is built to meet the unique needs of your profession. From accurate measurements to
                        order and
                        preference management, every feature simplifies your workflow. Stay organized and focused
                        with a
                        platform that works like you do.
                    </p>
                </div>
                <div class="lg:mt-10 mt-0">
                    <h1 class="font-lato font-semibold text-[18px] leading-[100%] tracking-[0%] text-[#167893]">
                        2. Saves Time, Improves Accuracy
                    </h1>
                    <p
                        class="mt-4 w-[350px] font-lato font-normal text-[15px] leading-[152%] tracking-[0%] mt-2 max-w-[435px] text-black">
                        By automating measurement tracking, order management, and invoicing, MaapLo reduces errors
                        and
                        speeds up processes—helping you deliver quality and grow your business effortlessly.
                    </p>
                </div>
            </div>

            <div>
                <x-icon name="section6" />
            </div>

            <div class="flex flex-col gap-7 lg:max-w-[45%] items-center lg:items-start text-center lg:text-left">
                <div>
                    <h1 class=" mt-5 font-lato font-semibold text-[18px] leading-[100%] tracking-[0%] text-[#167893]">
                        3. Stay Connected, Anywhere
                    </h1>
                    <p class="mt-3 font-lato font-normal text-[15px] leading-[152%] tracking-[0%] mt-2 text-black">
                        MaapLo keeps you connected in-store, studio, or on the go. Work online or offline—update
                        measurements and orders. Data syncs automatically for flexibility.
                    </p>
                </div>
                <div>
                    <h1 class="font-lato font-semibold text-[18px] leading-[100%] tracking-[0%] text-[#167893]">
                        4. Budget-Friendly and Scalable
                    </h1>
                    <p
                        class="mt-3 font-lato font-normal text-[15px] leading-[152%] tracking-[0%] mt-2 max-w-[435px] text-black">
                        Affordable and scalable, MaapLo fits all business sizes. Pay for what you need, and grow
                        seamlessly
                        with tools to manage more clients and orders without extra cost.
                    </p>
                </div>
                <div class="lg:-ml-20">
                    <h1 class="font-lato font-semibold text-[18px] leading-[100%] tracking-[0%] text-[#167893]">
                        5. Enhances customer experience
                    </h1>
                    <p
                        class="mt-3 font-lato font-normal text-[15px] leading-[152%] tracking-[0%] mt-2 max-w-[435px] text-black">
                        MaapLo helps you deliver smooth, personalized service with organized records, accurate
                        tracking, and
                        clear communication — boosting client satisfaction and loyalty.
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- section 7 --}}
    <div class="bg-[#FBFBFB] w-full px-0 mx-auto">
        <div class="lg:px-20 px-5 lg:py-20 pt-10">
            <div>
                <h1
                    class="font-[400] text-[25px] lg:text-[40px] leading-[100%] tracking-[0%] text-center font-[Convergence] text-black">
                    Testimonials
                </h1>
                <p
                    class="lg:w-[550px] lg:h-[54px] mt-5 mx-auto font-lato font-normal text-[15px] leading-[152%] tracking-[0%] text-center text-black">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                    labore et
                    dolore magna aliqua.
                </p>
            </div>
            {{-- testimonials --}}
            {{-- mobile view --}}
            <div class="block md:hidden flex flex-col lg:flex-row justify-between mt-10 gap-5 lg:gap-5">
                <div class="mySlides-testimonials bg-white rounded-[10px] p-4">
                    <div class="">
                        <x-icon name="section7" />
                    </div>
                    <p class="ml-7 -mt-3 font-lato font-normal text-[15px] leading-[152%] tracking-[0] text-black">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                        labore
                        et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris
                        nisi ut
                        aliquip ex ea commodo consequat.
                        {{-- <x-icon name="section7-img" /> --}}
                    </p>
                    <div class="border-t border-primary mt-4 pt-2 ml-7">
                        <div class="flex flex-row gap-5 mt-4">
                            <div>
                                {{-- <x-icon name="section7-img1" /> --}}
                                <img src="/images/Testimonials.svg" alt="Testimonial Image"
                                    class="w-14 h-14 rounded-full" />
                            </div>
                            <div class="mt-2">
                                <h1 class="font-lato font-medium text-[18px] leading-[100%] tracking-[0] text-black">
                                    Andres Jensen
                                </h1>
                                <p
                                    class="font-lato font-normal mt-2 text-[#4F4F4F] text-[14px] leading-[100%] tracking-[0]">
                                    Lorem ipsum dolor
                                </p>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="mySlides-testimonials bg-white rounded-[10px] p-4">
                    <div class="">
                        <x-icon name="section7" />
                    </div>
                    <p class="ml-7 -mt-3 font-lato font-normal text-[15px] leading-[152%] tracking-[0] text-black">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                        labore
                        et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris
                        nisi ut
                        aliquip ex ea commodo consequat.
                    </p>
                    <div class="border-t border-primary mt-4 pt-2 ml-7">
                        <div class="flex flex-row gap-5 mt-4">
                            <div>
                                <img src="/images/Testimonials.svg" alt="Testimonial Image"
                                    class="w-14 h-14 rounded-full">
                            </div>
                            <div class="mt-2">
                                <h1 class="font-lato font-medium text-[18px] leading-[100%] tracking-[0] text-black">
                                    Andres Jensen
                                </h1>
                                <p
                                    class="font-lato font-normal mt-2 text-[#4F4F4F] text-[14px] leading-[100%] tracking-[0]">
                                    Lorem ipsum dolor
                                </p>

                            </div>
                        </div>
                    </div>

                </div>

                <div class="mySlides-testimonials bg-white rounded-[10px] p-4">
                    <div class="">
                        <x-icon name="section7" />
                    </div>
                    <p class="ml-7 -mt-3 font-lato font-normal text-[15px] leading-[152%] tracking-[0] text-black">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                        labore
                        et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris
                        nisi ut
                        aliquip ex ea commodo consequat.
                    </p>
                    <div class="border-t border-primary mt-4 pt-2 ml-7">
                        <div class="flex flex-row gap-5 mt-4">
                            <div>
                                <img src="/images/Testimonials.svg" alt="Testimonial Image"
                                    class="w-14 h-14 rounded-full">
                            </div>
                            <div class="mt-2">
                                <h1 class="font-lato font-medium text-[18px] leading-[100%] tracking-[0] text-black">
                                    Andres Jensen
                                </h1>
                                <p
                                    class="font-lato font-normal mt-2 text-[#4F4F4F] text-[14px] leading-[100%] tracking-[0]">
                                    Lorem ipsum dolor
                                </p>

                            </div>
                        </div>
                    </div>

                </div>

                <div class="flex justify-center my-5 gap-2 ">
                    <span class="dot-testimonials w-3 h-3 bg-gray-300 rounded-full cursor-pointer"
                        onclick="currentSlideTestimonials(1)"></span>
                    <span class="dot-testimonials w-3 h-3 bg-gray-300 rounded-full cursor-pointer"
                        onclick="currentSlideTestimonials(2)"></span>
                    <span class="dot-testimonials w-3 h-3 bg-gray-300 rounded-full cursor-pointer"
                        onclick="currentSlideTestimonials(3)"></span>

                </div>
            </div>

            {{-- dasktop view --}}
            <div class="hidden md:flex flex-col lg:flex-row justify-between mt-10 gap-5 lg:gap-5">
                <div class=" bg-white rounded-[10px] p-4">
                    <div class="">
                        <x-icon name="section7" />
                    </div>
                    <p class="ml-7 -mt-3 font-lato font-normal text-[15px] leading-[152%] tracking-[0] text-black">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                        labore
                        et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris
                        nisi ut
                        aliquip ex ea commodo consequat.
                        {{-- <x-icon name="section7-img" /> --}}
                    </p>
                    <div class="border-t border-primary mt-4 pt-2 ml-7">
                        <div class="flex flex-row gap-5 mt-4">
                            <div>
                                <img src="/images/Testimonials.svg" alt="Testimonial Image" class="w-14 h-14">
                            </div>
                            <div class="">
                                <h1 class="font-lato font-medium text-[25px] leading-[100%] tracking-[0] text-black">
                                    Andres Jensen
                                </h1>
                                <p
                                    class="font-lato font-normal mt-2 text-[#4F4F4F] text-[14px] leading-[100%] tracking-[0]">
                                    Lorem ipsum dolor
                                </p>

                            </div>
                        </div>
                    </div>

                </div>

                <div class=" bg-white rounded-[10px] p-4">
                    <div class="">
                        <x-icon name="section7" />
                    </div>
                    <p class="ml-7 -mt-3 font-lato font-normal text-[15px] leading-[152%] tracking-[0] text-black">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                        labore
                        et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris
                        nisi ut
                        aliquip ex ea commodo consequat.
                    </p>
                    <div class="border-t border-primary mt-4 pt-2 ml-7">
                        <div class="flex flex-row gap-5 mt-4">
                            <div>
                                <img src="/images/Testimonials.svg" alt="Testimonial Image" class="w-14 h-14">
                            </div>
                            <div class="">
                                <h1 class="font-lato font-medium text-[25px] leading-[100%] tracking-[0] text-black">
                                    Andres Jensen
                                </h1>
                                <p
                                    class="font-lato font-normal mt-2 text-[#4F4F4F] text-[14px] leading-[100%] tracking-[0]">
                                    Lorem ipsum dolor
                                </p>

                            </div>
                        </div>
                    </div>

                </div>

                <div class=" bg-white rounded-[10px] p-4">
                    <div class="">
                        <x-icon name="section7" />
                    </div>
                    <p class="ml-7 -mt-3 font-lato font-normal text-[15px] leading-[152%] tracking-[0] text-black">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                        labore
                        et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris
                        nisi ut
                        aliquip ex ea commodo consequat.
                    </p>
                    <div class="border-t border-primary mt-4 pt-2 ml-7">
                        <div class="flex flex-row gap-5 mt-4">
                            <div>
                                <img src="/images/Testimonials.svg" alt="Testimonial Image" class="w-14 h-14">
                            </div>
                            <div class="">
                                <h1 class="font-lato font-medium text-[25px] leading-[100%] tracking-[0] text-black">
                                    Andres Jensen
                                </h1>
                                <p
                                    class="font-lato font-normal mt-2 text-[#4F4F4F] text-[14px] leading-[100%] tracking-[0]">
                                    Lorem ipsum dolor
                                </p>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- section 8 --}}
    <div class="bg-[#FAFFFF] px-5 lg:px-20 py-10 lg:py-16">
        <div>
            <h1
                class="font-[400] text-[25px] lg:text-[40px] leading-[100%] tracking-[0%] text-center font-[Convergence] text-black">
                Who it’s For
            </h1>
            <p
                class="lg:w-[580px] lg:h-[54px] mt-5 mx-auto font-lato font-normal text-[15px] leading-[152%] tracking-[0%] text-center text-black">
                MaapLo is designed for fashion professionals - from solo creators and boutique managers to mobile
                experts
                and students - making everyday tasks simpler and smarter.
            </p>
        </div>

        {{-- mobile view --}}
        <div class="block md:hidden relative mt-10 overflow-hidden">
            <!-- Slides -->
            <div class="relative">
                <!-- Slide 1 -->
                <div class="mySlides-who-for w-full min-h-[550px] bg-white rounded-[10px] shadow-[0_0_2.9px_0_#00000040] px-5 pt-5 lg:pt-10 relative flex flex-col gap-4"
                    style="display: none;">
                    <img src="/images/Vector.svg" alt=""
                        class="absolute inset-0 w-full h-full object-cover opacity-10" />
                    <div class="relative z-10">
                        <h1 class="font-lato font-semibold text-[20px] text-[#263238]">Boutique Owners</h1>
                        <p class="mt-5 text-[15px] text-[#636363]">
                            Boutique owners can streamline tailoring operations with MaapLo’s all-in-one solution. Manage
                            multiple clients, assign orders, monitor progress, and maintain records — anytime, anywhere.
                        </p>
                        <p class="mt-5 text-[16px] text-[#636363]">
                            It helps you stay efficient, reduce errors, and elevate your brand's service standards with
                            technology that keeps pace with your growing business.
                        </p>
                        <p class="mt-5 text-[16px] text-[#636363]">
                            Designed to simplify your workflow, MaapLo empowers your team to deliver consistent quality and
                            exceptional customer experiences every step of the way.
                        </p>
                    </div>
                    <div class="relative z-10 transition duration-300 transform hover:scale-105">
                        <img src="/images/section8-img1.svg" alt="" class="w-full mx-auto" />
                    </div>
                </div>

                <!-- Slide 2 -->
                <div class="mySlides-who-for w-full min-h-[550px] bg-white rounded-[10px] shadow-[0_0_2.9px_0_#00000040] px-5 pt-10 relative flex flex-col gap-4"
                    style="display: none;">
                    <img src="/images/Vector.svg" alt=""
                        class="absolute inset-0 w-full h-full object-cover opacity-10" />
                    <div class="relative z-10">
                        <h1 class="font-lato font-semibold text-[20px] text-[#263238]">Fashion Designers</h1>
                        <p class="my-5 text-[16px] text-[#636363]">
                            Fashion designers can use MaapLo to manage client profiles, fittings, custom orders, and
                            production schedules. It ensures accuracy and consistency, helping you bring your vision to life
                            while handling the backend with ease and precision.
                        </p>
                    </div>
                    <div class="relative z-10 mx-auto transition duration-300 transform hover:scale-105">
                        <img src="/images/section8-img2.svg" alt="" class="w-[250px] h-[250px] mx-auto" />
                    </div>
                </div>

                <!-- Slide 3 -->
                <div class="mySlides-who-for w-full min-h-[550px] bg-white rounded-[10px] shadow-[0_0_2.9px_0_#00000040] px-5 pt-10 relative flex flex-col gap-4"
                    style="display: none;">
                    <img src="/images/Vector.svg" alt=""
                        class="absolute inset-0 w-full h-full object-cover opacity-10" />
                    <div class="relative z-10">
                        <h1 class="font-lato font-semibold text-[20px] text-[#263238]">Field Tailors</h1>
                        <p class="text-[16px] text-[#636363] mt-3">
                            For tailors who work on the go, MaapLo is the perfect mobile assistant. Access and update client
                            data, record measurements offline, and manage orders from any location.
                        </p>
                        <p class="text-[16px] text-[#636363] mt-3">
                            Whether you’re visiting homes or taking on-site fittings, MaapLo keeps your workflow seamless
                            and your service efficient.
                        </p>
                    </div>
                    <div class="relative z-10 transition duration-300 transform hover:scale-105 mx-auto">
                        <img src="/images/section8-img3.svg" alt="" class="w-[250px] h-[230px] mx-auto mt-5" />
                    </div>
                </div>

                <!-- Slide 4 -->
                <div class="mySlides-who-for w-full min-h-[550px] bg-white rounded-[10px] shadow-[0_0_2.9px_0_#00000040] px-5 pt-10 relative flex flex-col gap-4"
                    style="display: none;">
                    <img src="/images/Vector.svg" alt=""
                        class="absolute inset-0 w-full h-full object-cover opacity-10" />
                    <div class="relative z-10">
                        <h1 class="font-lato font-semibold text-[20px] text-[#263238]">On-the-Go Professionals</h1>
                        <p class="text-[16px] text-[#636363] mt-3">
                            Always on the move? MaapLo is your reliable mobile companion. Update client details, record
                            measurements offline, and manage orders from anywhere—keeping your work smooth and your service
                            effortless.
                        </p>
                    </div>
                    <div class="relative z-10 transition duration-300 transform hover:scale-105 mx-auto">
                        <img src="/images/section8-img4.svg" alt="" class="w-[250px] h-[230px] mx-auto mt-4" />
                    </div>
                </div>

                <!-- Slide 5 -->
                <div class="mySlides-who-for w-full min-h-[550px] bg-white rounded-[10px] shadow-[0_0_2.9px_0_#00000040] px-5 pt-10 relative flex flex-col gap-4"
                    style="display: none;">
                    <img src="/images/Vector.svg" alt=""
                        class="absolute inset-0 w-full h-full object-cover opacity-10" />
                    <div class="relative z-10">
                        <h1 class="font-lato font-semibold text-[20px] text-[#263238]">Individual Tailors</h1>
                        <p class="text-[16px] text-[#636363] mt-3">
                            MaapLo supports individual tailors in managing daily tasks with ease. From storing measurements
                            to tracking orders and generating invoices, it simplifies operations so you can focus on your
                            craft.
                        </p>
                        <p class="text-[16px] text-[#636363] mt-3">
                            Stay organized, save time, and provide a professional experience — all from your mobile device.
                        </p>
                    </div>
                    <div class="relative z-10 transition duration-300 transform hover:scale-105 mx-auto">
                        <img src="/images/section8-img5.svg" alt="" class="w-[260px] h-[245px] mx-auto" />
                    </div>
                </div>
            </div>

            <!-- Dot navigation -->
            <div class="flex justify-center gap-2 mt-6">
                <span class="dot-who-for w-3 h-3 rounded-full bg-gray-300 cursor-pointer"
                    onclick="currentSlideWhoFor(1)"></span>
                <span class="dot-who-for w-3 h-3 rounded-full bg-gray-300 cursor-pointer"
                    onclick="currentSlideWhoFor(2)"></span>
                <span class="dot-who-for w-3 h-3 rounded-full bg-gray-300 cursor-pointer"
                    onclick="currentSlideWhoFor(3)"></span>
                <span class="dot-who-for w-3 h-3 rounded-full bg-gray-300 cursor-pointer"
                    onclick="currentSlideWhoFor(4)"></span>
                <span class="dot-who-for w-3 h-3 rounded-full bg-gray-300 cursor-pointer"
                    onclick="currentSlideWhoFor(5)"></span>
            </div>
        </div>

        {{-- dasktop view --}}
        <div class="hidden lg:block">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-10">
                <div
                    class="relative col-span-2 w-full rounded-[10px] shadow-[0_0_2.9px_0_#00000040] flex lg:flex-row bg-white flex-col gap-4 px-5 pt-10 overflow-hidden">

                    <img src="/images/Vector.svg" alt="Section 8 Image"
                        class="absolute inset-0 w-full h-full object-cover opacity-10">

                    <!-- Content, above the icon -->
                    <div class="relative z-10">
                        <h1 class="font-lato font-semibold text-[20px] leading-[100%] tracking-normal text-[#263238]">
                            Boutique Owners
                        </h1>

                        <p class="mt-5 font-lato font-normal text-[15px] leading-[152%] tracking-normal text-[#636363]">
                            Boutique owners can streamline tailoring operations with MaapLo’s all-in-one solution.
                            Manage
                            multiple clients, assign orders, monitor progress, and maintain records — anytime, anywhere.

                        </p>

                        <p class="mt-5 font-lato font-normal text-[16px] leading-[152%] tracking-normal text-[#636363]">
                            It helps you stay efficient, reduce errors, and elevate your brand's service standards with
                            technology that keeps pace with your growing business.
                        </p>

                        <p class="mt-5 font-lato font-normal text-[16px] leading-[152%] tracking-normal text-[#636363]">
                            Designed to simplify your workflow, MaapLo empowers your team to deliver consistent quality
                            and
                            exceptional customer experiences every step of the way.
                        </p>
                    </div>

                    <div class="relative z-10 transition duration-300 ease-in-out transform hover:scale-105">
                        {{-- <x-icon name="section8-img1" /> --}}
                        <img src="/images/section8-img1.svg" alt="Section 8 Image"
                            class="lg:w-[1000px] lg:h-[300px] w-full">
                    </div>

                </div>
                <!-- Second div: spans 1 column -->
                <div
                    class="relative flex flex-col w-full rounded-[10px] shadow-[0px_0px_2.9px_0px_#00000040] bg-white px-5 pt-2 bg-cover bg-center bg-no-repeat">
                    <img src="/images/Vector.svg" alt="Section 8 Image"
                        class="absolute inset-0 w-full h-full object-cover opacity-10">
                    <div
                        class="item-center mx-auto h-full relative z-10 transition duration-300 ease-in-out transform hover:scale-105">
                        {{-- <x-icon name="section8-img2" /> --}}
                        <img src="/images/section8-img2.svg" alt="Section 8 Image" class="w-[250px] h-[250px]">
                    </div>
                    <div class="relative z-10">
                        <h1 class="font-lato font-semibold text-[20px] leading-[100%] tracking-normal text-[#263238]">
                            Fashion Designers
                        </h1>
                        <p class="my-5 font-lato font-normal text-[16px] leading-[152%] tracking-normal text-[#636363]">
                            Fashion designers can use MaapLo to manage client profiles, fittings, custom orders, and
                            production
                            schedules. It ensures accuracy and consistency, helping you bring your vision to life while
                            handling
                            the backend with ease and precision.
                        </p>
                    </div>
                </div>

            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-10">
                <!-- First div: spans 2 columns -->
                <div
                    class="relative flex flex-col w-full rounded-[10px] shadow-[0px_0px_2.9px_0px_#00000040] bg-white px-5 pt-2 bg-cover bg-center bg-no-repeat">
                    <img src="/images/Vector.svg" alt="Section 8 Image"
                        class="absolute inset-0 w-full h-full object-cover opacity-10">
                    <!-- Icon and Heading Section -->
                    <div class="relative z-10 flex flex-col">
                        <!-- Top-right Icon -->
                        <div class="lg:absolute lg:top-0 lg:right-0">
                            {{-- <x-icon name="section8-img4" /> --}}
                            <img src="/images/section8-img3.svg" alt=""
                                class="hover-rotate-x w-[180px] h-[230px] mx-auto mt-5 transition-transform duration-300 hover:rotate-x-[360deg]" />

                        </div>

                        <!-- Heading Overlapping Below Icon -->
                        <h1
                            class="mt-5 lg:mt-[160px] font-lato font-semibold text-[20px] leading-[100%] tracking-normal text-[#263238]">
                            Field Tailors
                        </h1>

                        <!-- Description -->
                        <p
                            class="mt-3 w-[260px] font-lato font-normal text-[16px] leading-[152%] tracking-normal text-[#636363]">
                            For tailors who work on the go, MaaP Lo is the perfect mobile assistant. Access and update
                            client
                            data, record measurements offline, and manage orders from any location.

                        </p>
                    </div>

                    <!-- Additional Info Paragraph -->
                    <div class="mt-3">
                        <p class="font-lato mb-3 font-normal text-[16px] leading-[152%] tracking-normal text-[#636363]">
                            Whether you’re visiting homes or taking on-site fittings, MaaP Lo keeps your workflow
                            seamless and
                            your service efficient.
                        </p>
                    </div>

                </div>

                <div
                    class="relative flex flex-col w-full rounded-[10px] shadow-[0px_0px_2.9px_0px_#00000040] bg-white px-5 pt-5">
                    <img src="/images/Vector.svg" alt="Section 8 Image"
                        class="absolute inset-0 w-full h-full object-cover opacity-10">

                    <div>
                        <h1 class="font-lato font-semibold text-[20px] leading-[100%] tracking-normal text-[#263238]">
                            On-the-Go Professionals
                        </h1>
                        <p class="mt-3 font-lato font-normal text-[16px] leading-[152%] tracking-normal text-[#636363]">
                            Always on the move? MaapLo is your reliable mobile companion. Update client details, record
                            measurements offline, and manage orders from anywhere—keeping your work smooth and your
                            service
                            effortless.

                        </p>
                    </div>
                    <div class="item-center mx-auto h-full transition duration-300 ease-in-out transform hover:scale-105">
                        {{-- <x-icon name="section8-img3" /> --}}
                        <img src="/images/section8-img4.svg" alt="Section 8 Image"
                            class="lg:w-[300px] lg:h-[250px] w-full">
                    </div>

                </div>

                <!-- Second div: spans 1 column -->
                <div
                    class="relative flex flex-col w-full rounded-[10px] shadow-[0px_0px_2.9px_0px_#00000040] bg-white px-5 pt-5">
                    <img src="/images/Vector.svg" alt="Section 8 Image"
                        class="absolute inset-0 w-full h-full object-cover opacity-10">

                    <!-- Additional Info Paragraph -->
                    <div class="relative z-10">
                        <h1 class="font-lato font-semibold text-[20px] leading-[100%] tracking-normal text-[#263238]">
                            Individual Tailors
                        </h1>

                        <p class="mt-3 font-lato font-normal text-[16px] leading-[152%] tracking-normal text-[#636363]">
                            MaapLo supports individual tailors in managing daily tasks with ease. From storing
                            measurements to
                            tracking orders and generating invoices, it simplifies operations so you can focus on your
                            craft.
                        </p>
                    </div>
                    <div class="relative z-10 flex flex-col">
                        <!-- Top-right Icon -->
                        <div class="lg:absolute lg:top-0 lg:right-0">
                            {{-- <x-icon name="section8-img5" /> --}}
                            <img src="/images/section8-img5.svg" alt="Section 8 Image"
                                class="hover-rotate-x  w-[260px] h-[245px] lg:-mr-[100px] transition-transform duration-300 hover:rotate-x-[360deg]">
                        </div>

                        <!-- Heading Overlapping Below Icon -->

                        <!-- Description -->
                        <p
                            class="mt-3 mb-3 lg:mb-0 lg:w-[260px] font-lato font-normal text-[16px] leading-[152%] tracking-normal text-[#636363]">
                            Stay organized, save time, and provide a professional experience — all from your mobile
                            device.
                        </p>
                    </div>

                </div>
            </div>
        </div>

    </div>

    {{-- section 9 --}}
    <div id="blogs" class="bg-[#FBFBFB] px-5 lg:px-20 py-10 lg:py-16">
        <!-- Header Section -->
        <div class="text-center">
            <h1 class="font-[Convergence] text-[25px] lg:text-[40px] font-normal leading-[100%] text-black">
                See Our Blogs
            </h1>
            <p class="font-lato text-[15px] text-black mt-5 lg:w-[630px] mx-auto leading-[152%]">
                Stay informed with expert tips, industry updates, and success stories. Our blog offers valuable
                insights to
                help you grow, manage, and streamline your fashion business with MaapLo.
            </p>
        </div>

        {{-- mobile view --}}
        <!-- Blog Cards Grid -->
        <div class="block lg:hidden grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-10">
            <!-- Blog Card 1 -->
            <div class="mySlides-blogs bg-white p-5 rounded-[10px]">
                <div
                    class="bg-[#FAFAFA] p-6 rounded-[10px] flex justify-center transition duration-300 ease-in-out transform hover:scale-105">
                    <x-icon name="section9" />
                </div>
                <div class="flex flex-row mt-5 gap-8 text-[#787878] text-[16px] font-lato">
                    <p>Lorem Ipsum</p>
                    <div class="flex items-center gap-2">
                        <x-icon name="section9-icon" />
                        <p>10:30 AM</p>
                    </div>
                </div>
                <h2 class="font-lato font-semibold text-[24px] text-[#000000] mt-5">
                    5 Common Mistakes That Lose Clients
                </h2>
                <p class="font-lato text-[16px] text-[#636363] mt-3 leading-[152%]">
                    Even experienced pros can lose clients over simple mistakes. This blog covers five common errors
                    and how
                    MaapLo helps you avoid them for a smoother, more professional workflow.

                </p>
                <div class="flex gap-3 mt-5 items-center">
                    <img src="/images/Testimonials.svg" alt="Author Icon" class="w-8 h-8">
                    <p class="font-lato font-medium text-[16px] text-black">Andres Jensen</p>
                    <p class="font-lato text-[16px] text-[#787878]">2 days ago</p>
                </div>
            </div>

            <!-- Blog Card 2 -->
            <div class="mySlides-blogs bg-white p-5 rounded-[10px]">
                <div
                    class="bg-[#FAFAFA] p-6 rounded-[10px] flex justify-center transition duration-300 ease-in-out transform hover:scale-105">
                    <x-icon name="section9" />
                </div>
                <div class="flex flex-row mt-5 gap-8 text-[#787878] text-[16px] font-lato">
                    <p>Lorem Ipsum</p>
                    <div class="flex items-center gap-2">
                        <x-icon name="section9-icon" />
                        <p>10:30 AM</p>
                    </div>
                </div>
                <h2 class="font-lato font-semibold text-[24px] text-[#000000] mt-5">
                    Easily Manage Orders Digitally with MaapLo
                </h2>
                <p class="font-lato text-[16px] text-[#636363] mt-3 leading-[152%]">
                    Lost notes and scattered orders? Discover how digital order management with MaapLo helps you
                    stay
                    organized, reduce errors, and manage everything smoothly—from your mobile.

                </p>
                <div class="flex gap-3 mt-5 items-center">
                    <img src="/images/Testimonials.svg" alt="Author Icon" class="w-8 h-8">
                    <p class="font-lato font-medium text-[16px] text-black">Andres Jensen</p>
                    <p class="font-lato text-[16px] text-[#787878]">2 days ago</p>
                </div>
            </div>

            <!-- Blog Card 3 -->
            <div class="mySlides-blogs bg-white p-5 rounded-[10px]">
                <div
                    class="bg-[#FAFAFA] p-6 rounded-[10px] flex justify-center transition duration-300 ease-in-out transform hover:scale-105">
                    <x-icon name="section9" />
                </div>
                <div class="flex flex-row mt-5 gap-8 text-[#787878] text-[16px] font-lato">
                    <p>Lorem Ipsum</p>
                    <div class="flex items-center gap-2">
                        <x-icon name="section9-icon" />
                        <p>10:30 AM</p>
                    </div>
                </div>
                <h2 class="font-lato font-semibold text-[24px] text-[#000000] mt-5">
                    Smart Tips for Recording Accurate Measurements
                </h2>
                <p class="font-lato text-[16px] text-[#636363] mt-3 leading-[152%]">
                    Get practical tips to capture precise measurements confidently. See how MaapLo helps organize
                    data to
                    avoid errors and ensure perfect fits every time.

                </p>
                <div class="flex gap-3 mt-5 items-center">
                    <img src="/images/Testimonials.svg" alt="Author Icon" class="w-8 h-8">
                    <p class="font-lato font-medium text-[16px] text-black">Andres Jensen</p>
                    <p class="font-lato text-[16px] text-[#787878]">2 days ago</p>
                </div>
            </div>

            <!-- Blog Card 4 -->
            <div class="mySlides-blogs bg-white p-5 rounded-[10px]">
                <div
                    class="bg-[#FAFAFA] p-6 rounded-[10px] flex justify-center transition duration-300 ease-in-out transform hover:scale-105">
                    <x-icon name="section9" />
                </div>
                <div class="flex flex-row mt-5 gap-8 text-[#787878] text-[16px] font-lato">
                    <p>Lorem Ipsum</p>
                    <div class="flex items-center gap-2">
                        <x-icon name="section9-icon" />
                        <p>10:30 AM</p>
                    </div>
                </div>
                <h2 class="font-lato font-semibold text-[24px] text-[#000000] mt-5">
                    Work Smarter with MaapLo
                </h2>
                <p class="font-lato text-[16px] text-[#636363] mt-3 leading-[152%]">
                    Learn how MaapLo automates tasks like order tracking and invoicing to save time, reduce errors,
                    and keep
                    your business running smoothly—so you can focus on creativity.

                </p>
                <div class="flex gap-3 mt-5 items-center">
                    <img src="/images/Testimonials.svg" alt="Author Icon" class="w-8 h-8">
                    <p class="font-lato font-medium text-[16px] text-black">Andres Jensen</p>
                    <p class="font-lato text-[16px] text-[#787878]">2 days ago</p>
                </div>
            </div>

            <!-- Dots -->
            <div class="flex justify-center mt-5 gap-2">
                <span class="dot-blogs w-3 h-3 bg-gray-300 rounded-full cursor-pointer"
                    onclick="currentSlideBlogs(1)"></span>
                <span class="dot-blogs w-3 h-3 bg-gray-300 rounded-full cursor-pointer"
                    onclick="currentSlideBlogs(2)"></span>
                <span class="dot-blogs w-3 h-3 bg-gray-300 rounded-full cursor-pointer"
                    onclick="currentSlideBlogs(3)"></span>
                <span class="dot-blogs w-3 h-3 bg-gray-300 rounded-full cursor-pointer"
                    onclick="currentSlideBlogs(4)"></span>

            </div>
        </div>

        {{-- dasktop view --}}
        <!-- Blog Cards Grid -->
        <div class="hidden md:grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-10">
            <!-- Blog Card 1 -->
            <div class="bg-white p-5 rounded-[10px]">
                <div
                    class="bg-[#FAFAFA] p-6 rounded-[10px] flex justify-center transition duration-300 ease-in-out transform hover:scale-105">
                    <x-icon name="section9" />
                </div>
                <div class="flex flex-row mt-5 gap-8 text-[#787878] text-[16px] font-lato">
                    <p>Lorem Ipsum</p>
                    <div class="flex items-center gap-2">
                        <x-icon name="section9-icon" />
                        <p>10:30 AM</p>
                    </div>
                </div>
                <h2 class="font-lato font-semibold text-[24px] text-[#000000] mt-5">
                    5 Common Mistakes That Lose Clients
                </h2>
                <p class="font-lato text-[16px] text-[#636363] mt-3 leading-[152%]">
                    Even experienced pros can lose clients over simple mistakes. This blog covers five common errors
                    and how
                    MaapLo helps you avoid them for a smoother, more professional workflow.

                </p>
                <div class="flex gap-3 mt-5 items-center">
                    {{-- <x-icon name="section9-icon2" /> --}}
                    <img src="/images/Testimonials.svg" alt="Author Icon" class="w-8 h-8">
                    <p class="font-lato font-medium text-[16px] text-black">Andres Jensen</p>
                    <p class="font-lato text-[16px] text-[#787878]">2 days ago</p>
                </div>
            </div>

            <!-- Blog Card 2 -->
            <div class="bg-white p-5 rounded-[10px]">
                <div
                    class="bg-[#FAFAFA] p-6 rounded-[10px] flex justify-center transition duration-300 ease-in-out transform hover:scale-105">
                    <x-icon name="section9" />
                </div>
                <div class="flex flex-row mt-5 gap-8 text-[#787878] text-[16px] font-lato">
                    <p>Lorem Ipsum</p>
                    <div class="flex items-center gap-2">
                        <x-icon name="section9-icon" />
                        <p>10:30 AM</p>
                    </div>
                </div>
                <h2 class="font-lato font-semibold text-[24px] text-[#000000] mt-5">
                    Easily Manage Orders Digitally with MaapLo
                </h2>
                <p class="font-lato text-[16px] text-[#636363] mt-3 leading-[152%]">
                    Lost notes and scattered orders? Discover how digital order management with MaapLo helps you
                    stay
                    organized, reduce errors, and manage everything smoothly—from your mobile.

                </p>
                <div class="flex gap-3 mt-5 items-center">
                    <img src="/images/Testimonials.svg" alt="Author Icon" class="w-8 h-8">
                    <p class="font-lato font-medium text-[16px] text-black">Andres Jensen</p>
                    <p class="font-lato text-[16px] text-[#787878]">2 days ago</p>
                </div>
            </div>

            <!-- Blog Card 3 -->
            <div class="bg-white p-5 rounded-[10px]">
                <div
                    class="bg-[#FAFAFA] p-6 rounded-[10px] flex justify-center transition duration-300 ease-in-out transform hover:scale-105">
                    <x-icon name="section9" />
                </div>
                <div class="flex flex-row mt-5 gap-8 text-[#787878] text-[16px] font-lato">
                    <p>Lorem Ipsum</p>
                    <div class="flex items-center gap-2">
                        <x-icon name="section9-icon" />
                        <p>10:30 AM</p>
                    </div>
                </div>
                <h2 class="font-lato font-semibold text-[24px] text-[#000000] mt-5">
                    Smart Tips for Recording Accurate Measurements
                </h2>
                <p class="font-lato text-[16px] text-[#636363] mt-3 leading-[152%]">
                    Get practical tips to capture precise measurements confidently. See how MaapLo helps organize
                    data to
                    avoid errors and ensure perfect fits every time.

                </p>
                <div class="flex gap-3 mt-5 items-center">
                    <img src="/images/Testimonials.svg" alt="Author Icon" class="w-8 h-8">
                    <p class="font-lato font-medium text-[16px] text-black">Andres Jensen</p>
                    <p class="font-lato text-[16px] text-[#787878]">2 days ago</p>
                </div>
            </div>

            <!-- Blog Card 4 -->
            <div class="bg-white p-5 rounded-[10px]">
                <div
                    class="bg-[#FAFAFA] p-6 rounded-[10px] flex justify-center transition duration-300 ease-in-out transform hover:scale-105">
                    <x-icon name="section9" />
                </div>
                <div class="flex flex-row mt-5 gap-8 text-[#787878] text-[16px] font-lato">
                    <p>Lorem Ipsum</p>
                    <div class="flex items-center gap-2">
                        <x-icon name="section9-icon" />
                        <p>10:30 AM</p>
                    </div>
                </div>
                <h2 class="font-lato font-semibold text-[24px] text-[#000000] mt-5">
                    Work Smarter with MaapLo
                </h2>
                <p class="font-lato text-[16px] text-[#636363] mt-3 leading-[152%]">
                    Learn how MaapLo automates tasks like order tracking and invoicing to save time, reduce errors,
                    and keep
                    your business running smoothly—so you can focus on creativity.

                </p>
                <div class="flex gap-3 mt-5 items-center">
                    <img src="/images/Testimonials.svg" alt="Author Icon" class="w-8 h-8" />
                    <p class="font-lato font-medium text-[16px]">Andres Jensen</p>
                    <p class="font-lato text-[16px] text-[#787878]">2 days ago</p>
                </div>
            </div>
        </div>
    </div>

    {{-- section 10 --}}
    <div id="contact" class="bg-[#F7F7F7] px-5 lg:px-20 py-10 lg:py-16">
        <!-- Header Section -->
        <div class="text-center">
            <h1 class="font-[Convergence] text-[25px] lg:text-[40px] font-normal leading-[100%] text-black">
                Contact us
            </h1>
            <p class="font-lato text-[15px] text-black mt-5 lg:w-[600px] mx-auto leading-[152%]">
                Have questions or need support? We're here to help! Reach out to MaapLo for inquiries, collaborations, or
                assistance. Let’s navigate your path to success- together.
            </p>
        </div>
        <div
            class="relative mt-10 max-w-7xl mx-auto bg-white relative bg-[url('/images/Vector .svg')] bg-no-repeat bg-center bg-cover px-6 py-12 rounded-lg shadow-md">
            <div class="grid md:grid-cols-2 gap-10 items-center">
                <img src="/images/Vector.svg" alt="Section 8 Image"
                    class="absolute inset-0 w-full h-full object-cover opacity-10">

                <!-- Left: Contact Info -->
                <div class="mx-auto relative z-10">
                    <div class="flex justify-center mb-4 transition duration-300 ease-in-out transform hover:scale-105">
                        <x-icon name="contact" class="w-10 h-10 text-blue-600" />
                    </div>
                    <div class="mt-10 lg:ml-10 text-center lg:text-left flex flex-col items-center lg:items-start">
                        <h1
                            class="lg:flex items-center justify-center text-black lg:justify-start gap-2 mt-3 font-lato font-normal text-[15px] leading-[152%]">
                            <x-icon name="phone" class="mx-auto" /> +91 70696 26260
                        </h1>
                        <h1
                            class="lg:flex items-center justify-center text-black lg:justify-start gap-2 mt-3 font-lato font-normal text-[15px] leading-[152%]">
                            <x-icon name="email" /> contact@artisanalbyte.com
                        </h1>
                        <p
                            class="lg:flex items-center justify-center text-black lg:justify-start gap-2 mt-3 font-lato font-normal text-[15px] leading-[152%]">
                            <x-icon name="location" />
                            523, 5th Floor, North Plaza, Nr. 4D Square Mall, Visat Gandhinagar Highway, Ahmedabad-380005
                        </p>
                    </div>

                </div>


                <!-- Right: Contact Form -->
                <div class="mx-auto relative z-10">
                    <h1 class="text-2xl md:text-3xl font-semibold mb-4 text-center md:text-left text-black">Let’s Connect
                    </h1>

                    <p class="text-gray-600 mb-6">
                        Every great journey starts with a conversation. Whether you're curious, ready to collaborate, or
                        just exploring, MaapLo is just a message away. Let’s create something meaningful - together.
                    </p>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" placeholder="Enter your name"
                            class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" placeholder="Enter your email"
                            class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Number</label>
                        <input type="tel" placeholder="Enter your number"
                            class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                        <textarea rows="4" placeholder="Enter your message"
                            class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>

                    <!-- Wrapper div to control alignment -->
                    <div class="flex justify-end lg:justify-start">
                        <button type="submit"
                            class="mt-4 px-[15px] py-1 lg:py-[8px] w-[100px] bg-black text-white hover:bg-gray-600
               rounded-tl-[8px] rounded-tr-[8px] rounded-bl-[8px] rounded-br-[25px]
               hover:scale-105 transition-transform duration-200">
                            <a href="/">Submit</a>
                        </button>
                    </div>

                </div>
            </div>
        </div>


    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let isYearly = false;

            window.setActiveButton = function(buttonId) {
                // Highlight active button
                document.getElementById('btn1').classList.remove('bg-primary', 'text-white');
                document.getElementById('btn2').classList.remove('bg-primary', 'text-white');
                document.getElementById(buttonId).classList.add('bg-primary', 'text-white');

                // Determine if it's yearly mode
                isYearly = (buttonId === 'btn2');

                // Update all plan prices
                document.querySelectorAll('.plan-price').forEach(function(el) {
                    const monthly = parseFloat(el.getAttribute('data-monthly'));

                    if (!isNaN(monthly)) {
                        if (isYearly) {
                            const yearlyPrice = monthly * 12 * 0.8; // 20% discount
                            el.textContent = '$' + yearlyPrice.toFixed(2);
                        } else {
                            el.textContent = '$' + monthly.toFixed(2);
                        }
                    }
                });
            };
        });
    </script>
@endsection
