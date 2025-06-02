@extends('guest-layout')
<link href="https://fonts.googleapis.com/css2?family=Convergence&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<script>
    function setActiveButton(id) {
        const buttons = document.querySelectorAll('button');
        buttons.forEach(btn => {
            btn.classList.remove('bg-primary', 'text-white');
            btn.classList.add('text-black', 'bg-transparent');
        });

        const activeBtn = document.getElementById(id);
        activeBtn.classList.remove('text-black', 'bg-transparent');
        activeBtn.classList.add('bg-primary', 'text-white');
    }
</script>
@section('content')
    {{-- section 1 --}}
    <div class="flex flex-col lg:flex-row lg:justify-between lg:px-20 px-0">
        <div class="flex justify-center lg:justify-start">
            <div class="lg:py-20 px-5 lg:px-0 lg:w-[600px] text-center lg:text-left">
                <h1 class="font-convergence text-[24px] lg:mt-8 md:text-[60px] lg:text-[65px] leading-[1.2] tracking-tight">
                    Measure Once. Store Forever.
                </h1>

                <!-- Description -->
                <p class="mt-4 lg:text-[16px] text-[18px] leading-[1.6] font-convergence">
                    MaapLo is a smart, tailor-focused app designed to simplify measurement tracking, order
                    management, client records, and invoicing.
                </p>

                <button
                    class="px-[15px] py-[8px] mt-10 bg-black text-white hover:bg-gray-600 rounded-tl-[8px] rounded-tr-[8px] rounded-bl-[8px] rounded-br-[25px] hover:scale-105 transition-transform duration-200 space-x-2">
                    <a href="/">Book a Free Demo</a>
                </button>
            </div>
        </div>

        <div class="px-5 md:px-24 lg:px-0 mt-10 lg:mt-0">
            <x-icon name="home" class="w-8 h-8 text-blue-500" />
        </div>
    </div>

    {{-- section 2 --}}
    <div class="px-5 lg:px-20 mt-16 lg:mt-18 ">
        <div
            class="lg:h-[250px] bg-[#BEDCDF] border-[12px] border-[#5CA7AF] rounded-[42px] shadow-[0px_2px_9.9px_0px_#88DDE340] flex items-center">
            <div class="flex flex-col lg:flex-row justify-between w-full">
                <div class="-ml-10 -mt-10 lg:mt-5 w-[180px] h-[150px] item-center justify-center item-center space-x-2">
                    <x-icon name="section2" class="w-8 h-8 text-blue-500" />

                </div>
                <div class="flex flex-col items-center text-center mt-0 lg:mt-5 ml-0 lg:ml-20">
                    <h1
                        class="font-lato font-bold text-[18px] px-2 lg:px-0 md:text-[32px] lg:text-[30px] leading-[100%] max-w-[822px]">
                        Smarter Management for Growing Businesses
                    </h1>
                    <p
                        class="font-lato font-normal text-[12px] lg:text-[16px] mt-2 leading-[152%] max-w-[590px] px-5 lg:px-10">
                        MaapLo simplifies business tasks, tracking orders, managing clients, and invoicing—in one easy app,
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

    {{-- section 3 --}}
    <div class="px-5 lg:px-20 mt-16 lg:mt-18">
        <h1 class="font-[400] text-[40px] leading-[100%] tracking-[0%] text-center font-[Convergence]">
            Features
        </h1>
        {{-- <div class="bg-[#E5FFFF] backdrop-blur-[557.6px]"> --}}
        <div class="mt-20 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 justify-center items-center gap-5">
            {{-- Features 1 --}}
            <div
                class="rounded-[10px] 
         bg-[linear-gradient(0deg,_#FFFFFF_40.9%,_rgba(229,255,255,0.6)_116.83%)] 
         shadow-[0px_0px_4px_0px_#00000040] py-8 px-4">
                <x-icon name="section3-img1" class="w-8 h-8 text-blue-500" />
                <h1 class="mt-6 text-[18px] lg:text-[25px] leading-[130%] font-normal font-[Convergence]">
                    Measurement Recording & History
                </h1>

                <p class="mt-3 text-[14px] leading-[152%] font-medium font-[Lato]">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat.
                </p>
                <button
                    class="mt-3 flex items-center gap-1 text-[15px] font-medium leading-[152%] text-primary font-[Lato]">
                    Learn More
                    <span class="text-[15px] font-bold ml-2">→</span>
                </button>

            </div>
            {{-- Features 2 --}}
            <div
                class="
         rounded-[10px] 
         bg-[linear-gradient(0deg,_#FFFFFF_40.9%,_rgba(255,252,229,0.6)_116.83%)] 
         shadow-[0px_0px_4px_0px_#00000040] py-8 px-4">
                <x-icon name="section3-img2" class="w-8 h-8 text-blue-500" />
                <h1 class="mt-6 text-[18px] lg:text-[25px] leading-[130%] font-normal font-[Convergence]">Custom Order
                    Management</h1>
                <p class="mt-3 text-[14px] leading-[152%] font-medium font-[Lato]">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat.
                </p>
                <button
                    class="mt-12 flex items-center gap-1 text-[15px] font-medium leading-[152%] text-primary font-[Lato]">
                    Learn More
                    <span class="text-[15px] font-bold ml-2">→</span>
                </button>
            </div>
            {{-- Features 3 --}}
            <div
                class="
         rounded-[10px]  rounded-[10px] shadow-[0px_0px_4px_0px_rgba(0,0,0,0.25)] bg-[linear-gradient(0deg,_#FFFFFF_40.9%,_rgba(229,255,229,0.6)_116.83%)] py-8 px-4">
                <x-icon name="section3-img3" class="w-8 h-8 text-blue-500" />
                <h1 class="mt-6 text-[18px] lg:text-[25px] leading-[130%] font-normal font-[Convergence]">Client Directory
                    with Notes</h1>
                <p class="mt-3 text-[14px] leading-[152%] font-medium font-[Lato]">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat.
                </p>
                <button
                    class="mt-12 flex items-center gap-1 text-[15px] font-medium leading-[152%] text-primary font-[Lato]">
                    Learn More
                    <span class="text-[15px] font-bold ml-2">→</span>
                </button>
            </div>
            {{-- Features 4 --}}
            <div
                class="rounded-[10px] 
            bg-[linear-gradient(0deg,_#FFFFFF_40.9%,_rgba(229,_235,_255,_0.6)_116.83%)] 
            shadow-[0px_0px_4px_0px_#00000040] 
            py-8 px-4">
                <x-icon name="section3-img4" class="w-8 h-8 text-blue-500" />
                <h1 class="mt-6 text-[18px] lg:text-[25px] leading-[130%] font-normal font-[Convergence]">Order Tracking &
                    Notifications
                </h1>
                <p class="mt-3 text-[14px] leading-[152%] font-medium font-[Lato]">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat.
                </p>
                <button
                    class="mt-12 flex items-center gap-1 text-[15px] font-medium leading-[152%] text-primary font-[Lato]">
                    Learn More
                    <span class="text-[15px] font-bold ml-2">→</span>
                </button>

                <!-- content -->
            </div>

            {{-- Features 5 --}}
            <div
                class="rounded-[10px]
            bg-[linear-gradient(0deg,_#FFFFFF_40.9%,_rgba(255,_237,_229,_0.6)_116.83%)]
            shadow-[0px_0px_4px_0px_#00000040]
            py-8 px-4">
                <x-icon name="section3-img5" class="w-8 h-8 text-blue-500" />
                <h1 class="mt-6 text-[18px] lg:text-[25px] leading-[130%] font-normal font-[Convergence]">
                    Mobile-first, easy to use interface
                </h1>

                <p class="mt-3 text-[14px] leading-[152%] font-medium font-[Lato]">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat.
                </p>
                <button
                    class="mt-3 flex items-center gap-1 text-[15px] font-medium leading-[152%] text-primary font-[Lato]">
                    Learn More
                    <span class="text-[15px] font-bold ml-2">→</span>
                </button>
            </div>

            {{-- Features 6 --}}
            <div
                class="rounded-[10px] 
            bg-[linear-gradient(0deg,_#FFFFFF_40.9%,_rgba(250,_229,_255,_0.6)_116.83%)] 
            shadow-[0px_0px_4px_0px_#00000040] 
            py-8 px-4">
                <x-icon name="section3-img6" class="w-8 h-8 text-blue-500" />
                <h1 class="mt-6 text-[18px] lg:text-[25px] leading-[130%] font-normal font-[Convergence]">
                    Measurement Recording & History
                </h1>

                <p class="mt-3 text-[14px] leading-[152%] font-medium font-[Lato]">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat.
                </p>
                <button
                    class="mt-3 flex items-center gap-1 text-[15px] font-medium leading-[152%] text-primary font-[Lato]">
                    Learn More
                    <span class="text-[15px] font-bold ml-2">→</span>
                </button>

                {{-- </div> --}}


            </div>
        </div>
    </div>

    {{-- section 4 --}}
    <div class="px-5 lg:px-20 mt-16 lg:mt-20">
        <h1 class="font-[400] text-[25px] lg:text-[50px] leading-[100%] tracking-[0%] text-center font-[Convergence]">
            Who we are?
        </h1>
        <div
            class="flex flex-col lg:flex-row justify-center lg:justify-between mt-5 lg:mt-10 lg:gap-20 items-center lg:items-start">
            <div class="py-5 lg:py-20 lg:w-[700px] text-center lg:text-left">
                <h1 class="font-[Convergence] font-normal text-[30px] lg:text-[40px] leading-[100%] tracking-[0%]">
                    Vision
                </h1>

                <p class="mt-6 font-[Lato] font-normal text-[15px] leading-[152%] tracking-[0%]">
                    At MaapLo, we’re redefining the way tailoring businesses operate. Our comprehensive, tailor-first app
                    simplifies shop management with powerful features like order tracking, measurement recording, client
                    data management, and invoicing — all in one place. Designed with deep respect for the craft, MaapLo
                    blends traditional tailoring values with smart technology to help tailors, boutique owners, studios, and
                    students work more efficiently, stay organized, and deliver a seamless client experience every step of
                    the way.
                </p>

                <p class="mt-6 font-[Lato] font-normal text-[15px] leading-[152%] tracking-[0%]">Lorem ipsum dolor sit
                    amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat.
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
    <div class="lg:px-20 px-0">
        <div
            class="py-20 mt-5 rounded-[20px] bg-[radial-gradient(50%_50%_at_50%_50%,rgba(219,245,247,0.5)_0%,rgba(237,250,251,0.5)_100%)] flex flex-col items-center justify-center text-center">
            <div>
                <h1 class="font-[400] text-[25px] lg:text-[40px] leading-[1] tracking-medium font-[Convergence]">
                    Simple Plans. Seamless Value!
                </h1>
                <p class="font-lato font-normal text-[15px] leading-[1.52] tracking-normal text-center text-[#5B5B5B] mt-4">
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

            {{-- card  --}}
            <div class="flex flex-col lg:flex-row lg:justify-center lg:items-center gap-5 mt-20">
                <div
                    class="w-[320px] lg:w-[350px] lg:h-[600px] border-t-[12px] border-primary rounded-[10px] bg-white shadow-[0px_4px_8.7px_0px_#16789340]">
                    <h1 class="mt-12 text-[30px] leading-[100%] font-normal text-center font-convergence">
                        Free Plan
                    </h1>
                    <x-icon name="start" class="" />
                    <h1 class="text-[30px] leading-[100%] font-extrabold text-center font-lato -mt-12">
                        $2500
                    </h1>

                    <p class="mt-4 text-[15px] leading-[152%] font-medium text-center font-lato">
                        USD / monthly
                    </p>

                    <div class="border-gray-300 mt-6 pt-4 px-6">
                        <ul
                            class=" text-left text-[15px] leading-[152%] font-lato border-y border-primary divide-y divide-[#167893] marker:text-primary">
                            <li class="py-3 flex items-center gap-4">
                                <i class="fa fa-check bg-primary text-white p-1 rounded-full text-[25px]"></i>
                                Lorem Ipsum
                            </li>
                            <li class="py-3 flex items-center gap-4">
                                <i class="fa fa-check bg-primary text-white p-2 rounded-full"></i>
                                Lorem Ipsum
                            </li>
                            <li class="py-3 flex items-center gap-4">
                                <i class="fa fa-check bg-primary text-white p-2 rounded-full"></i>
                                Lorem Ipsum
                            </li>
                            <li class="py-3 flex items-center gap-4">
                                <i class="fa fa-check bg-primary text-white p-2 rounded-full"></i>
                                Lorem Ipsum
                            </li>
                        </ul>
                    </div>
                    <div class="px-6 mt-8 mb-10 lg:mb-0">
                        <button
                            class="w-full mt-5 px-[15px] text-lg text-bold py-1 lg:py-[8px] bg-primary text-white hover:bg-[#DEEFF4] rounded-tl-[8px] rounded-tr-[8px] rounded-bl-[8px] rounded-br-[25px] hover:scale-105 transition-transform duration-200">
                            <a href="/">Get Started</a>
                        </button>
                    </div>
                </div>


                <div
                    class="w-[320px] lg:w-[350px] lg:h-[600px] border-t-[12px] border-primary rounded-[10px] bg-white shadow-[0px_4px_8.7px_0px_#16789340]">
                    <h1 class="mt-12 text-[30px] leading-[100%] font-normal text-center font-convergence">
                        Free Plan
                    </h1>

                    <h1 class="mt-7 text-[30px] leading-[100%] font-extrabold text-center font-lato">
                        $2500
                    </h1>

                    <p class="mt-4 text-[15px] leading-[152%] font-medium text-center font-lato">
                        USD / monthly
                    </p>

                    <div class="border-gray-300 mt-6 pt-4 px-6">
                        <ul
                            class=" text-left text-[15px] leading-[152%] font-lato border-y border-primary divide-y divide-[#167893] marker:text-primary">
                            <li class="py-3 flex items-center gap-4">
                                <i class="fa fa-check bg-primary text-white p-1 rounded-full text-[25px]"></i>
                                Lorem Ipsum
                            </li>
                            <li class="py-3 flex items-center gap-4">
                                <i class="fa fa-check bg-primary text-white p-2 rounded-full"></i>
                                Lorem Ipsum
                            </li>
                            <li class="py-3 flex items-center gap-4">
                                <i class="fa fa-check bg-primary text-white p-2 rounded-full"></i>
                                Lorem Ipsum
                            </li>
                            <li class="py-3 flex items-center gap-4">
                                <i class="fa fa-check bg-primary text-white p-2 rounded-full"></i>
                                Lorem Ipsum
                            </li>
                        </ul>
                    </div>
                    <div class="px-6 mt-8 mb-10 lg:mb-0">
                        <button
                            class="w-full mt-5 px-[15px] text-lg text-bold py-1 lg:py-[8px] bg-primary text-white hover:bg-[#DEEFF4] rounded-tl-[8px] rounded-tr-[8px] rounded-bl-[8px] rounded-br-[25px] hover:scale-105 transition-transform duration-200">
                            <a href="/">Get Started</a>
                        </button>
                    </div>
                </div>

                <div
                    class="w-[320px] lg:w-[350px] lg:h-[600px] border-t-[12px] border-primary rounded-[10px] bg-white shadow-[0px_4px_8.7px_0px_#16789340]">
                    <h1 class="mt-12 text-[30px] leading-[100%] font-normal text-center font-convergence">
                        Free Plan
                    </h1>

                    <h1 class="mt-7 text-[30px] leading-[100%] font-extrabold text-center font-lato">
                        $2500
                    </h1>

                    <p class="mt-4 text-[15px] leading-[152%] font-medium text-center font-lato">
                        USD / monthly
                    </p>
                    <div class="flex justify-end">
                        <x-icon name="start1" />
                    </div>

                    <div class="border-gray-300 px-6">
                        <ul
                            class=" text-left text-[15px] leading-[152%] font-lato border-y border-primary divide-y divide-[#167893] marker:text-primary">
                            <li class="py-3 flex items-center gap-4">
                                <i class="fa fa-check bg-primary text-white p-1 rounded-full text-[25px]"></i>
                                Lorem Ipsum
                            </li>
                            <li class="py-3 flex items-center gap-4">
                                <i class="fa fa-check bg-primary text-white p-2 rounded-full"></i>
                                Lorem Ipsum
                            </li>
                            <li class="py-3 flex items-center gap-4">
                                <i class="fa fa-check bg-primary text-white p-2 rounded-full"></i>
                                Lorem Ipsum
                            </li>
                            <li class="py-3 flex items-center gap-4">
                                <i class="fa fa-check bg-primary text-white p-2 rounded-full"></i>
                                Lorem Ipsum
                            </li>
                        </ul>
                    </div>
                    <div class="px-6 mt-8  mb-10 lg:mb-0">
                        <button
                            class="w-full mt-5 px-[15px] text-lg text-bold py-1 lg:py-[8px] bg-primary text-white hover:bg-[#DEEFF4] rounded-tl-[8px] rounded-tr-[8px] rounded-bl-[8px] rounded-br-[25px] hover:scale-105 transition-transform duration-200">
                            <a href="/">Get Started</a>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
    {{-- section 6 --}}
    <div class="px-5 lg:px-20 my-16 lg:mt-20">
        <h1 class="font-[400] text-[25px] lg:text-[40px] leading-[100%] tracking-[0%] text-center font-[Convergence]">
            Why Choose MaapLo?
        </h1>
        <p
            class="lg:w-[713px] lg:h-[54px] mt-5 mx-auto font-lato font-normal text-[16px] leading-[152%] tracking-[0%] text-center">
            MaapLo is a smart, budget-friendly app made for tailors — streamlining tasks with offline access and
            client-focused tools. Tailoring made simple.
        </p>


        <div class="flex flex-col lg:flex-row justify-between mt-10 overflow-hidden">
            <div class="flex flex-col gap-10 lg:max-w-[45%] items-center lg:items-start text-center lg:text-left">
                <div>
                    <h1 class="mt-5 font-lato text-[18px] font-semibold leading-[100%] tracking-[0%] text-primary">
                        1. Tailor-focused design (not generic tools)
                    </h1>
                    <p class="mt-4 font-lato font-normal text-[15px] w-[500px] leading-[152%] tracking-[0%] mt-2">
                        MaapLo is a smart system made for tailors — streamlining measurements, orders, and customer needs to
                        fit your daily workflow. Tailored for tailors, because your craft deserves better.
                    </p>
                </div>
                <div class="lg:mt-10 mt-0">
                    <h1 class="font-lato font-semibold text-[18px] leading-[100%] tracking-[0%] text-[#167893]">
                        2. Saves time, improves accuracy
                    </h1>
                    <p
                        class="mt-4 w-[350px] font-lato font-normal text-[15px] leading-[152%] tracking-[0%] mt-2 max-w-[435px]">
                        MaapLo automates tasks like measurements, orders, and invoices to reduce errors and save time.
                        Tailors get faster workflows, better accuracy, and more time to focus on their craft and clients.
                    </p>
                </div>
            </div>

            <div>
                <x-icon name="section6" />
            </div>

            <div class="flex flex-col gap-10 lg:max-w-[45%] items-center lg:items-start text-center lg:text-left">
                <div>
                    <h1 class="mt-5 font-lato font-semibold text-[18px] leading-[100%] tracking-[0%] text-[#167893]">
                        3.Works offline & online
                    </h1>
                    <p class="mt-4 font-lato font-normal text-[15px] leading-[152%] tracking-[0%] mt-2">
                        MaapLo lets you work anytime, anywhere — online or offline. Manage orders, measurements, and clients
                        with ease. Tailoring, made flexible.
                    </p>
                </div>
                <div>
                    <h1 class="font-lato font-semibold text-[18px] leading-[100%] tracking-[0%] text-[#167893]">
                        4. Budget-friendly and scalable
                    </h1>
                    <p class="mt-4 font-lato font-normal text-[15px] leading-[152%] tracking-[0%] mt-2 max-w-[435px]">
                        MaapLo suits tailors of all sizes with flexible pricing and scalable features. As your business
                        grows, MaapLo grows with you—without compromising on cost or quality.
                    </p>
                </div>
                <div class="lg:-ml-20">
                    <h1 class="font-lato font-semibold text-[18px] leading-[100%] tracking-[0%] text-[#167893]">
                        5. Enhances customer experience
                    </h1>
                    <p class="mt-4 font-lato font-normal text-[15px] leading-[152%] tracking-[0%] mt-2 max-w-[435px]">
                        MaapLo helps you deliver smooth, personalized service with organized records, accurate tracking, and
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
                    class="font-[400] text-[25px] lg:text-[40px] leading-[100%] tracking-[0%] text-center font-[Convergence]">
                    Testimonials
                </h1>
                <p
                    class="lg:w-[550px] lg:h-[54px] mt-5 mx-auto font-lato font-normal text-[15px] leading-[152%] tracking-[0%] text-center">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua.
                </p>
            </div>
            {{-- testimonials --}}
            <div class="flex flex-col lg:flex-row justify-between mt-10 gap-5 lg:gap-5">
                <div class=" bg-white rounded-[10px] p-4">
                    <div class="">
                        <x-icon name="section7" />
                    </div>
                    <p class="ml-7 -mt-3 font-lato font-normal text-[15px] leading-[152%] tracking-[0]">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore
                        et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
                        aliquip ex ea commodo consequat.
                        {{-- <x-icon name="section7-img" /> --}}
                    </p>
                    <div class="border-t border-primary mt-4 pt-2 ml-7">
                        <div class="flex flex-row gap-5 mt-4">
                            <div>
                                <x-icon name="section7-img1" />
                            </div>
                            <div class="mt-3">
                                <h1 class="font-lato font-medium text-[25px] leading-[100%] tracking-[0]">
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
                    <p class="ml-7 -mt-3 font-lato font-normal text-[15px] leading-[152%] tracking-[0]">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore
                        et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
                        aliquip ex ea commodo consequat.
                    </p>
                    <div class="border-t border-primary mt-4 pt-2 ml-7">
                        <div class="flex flex-row gap-5 mt-4">
                            <div>
                                <x-icon name="section7-img1" />
                            </div>
                            <div class="mt-3">
                                <h1 class="font-lato font-medium text-[25px] leading-[100%] tracking-[0]">
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
                    <p class="ml-7 -mt-3 font-lato font-normal text-[15px] leading-[152%] tracking-[0]">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore
                        et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
                        aliquip ex ea commodo consequat.
                    </p>
                    <div class="border-t border-primary mt-4 pt-2 ml-7">
                        <div class="flex flex-row gap-5 mt-4">
                            <div>
                                <x-icon name="section7-img1" />
                            </div>
                            <div class="mt-3">
                                <h1 class="font-lato font-medium text-[25px] leading-[100%] tracking-[0]">
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
    <div class="px-5 lg:px-20 my-16 lg:mt-20">
        <div>
            <h1 class="font-[400] text-[25px] lg:text-[40px] leading-[100%] tracking-[0%] text-center font-[Convergence]">
                Who it’s For
            </h1>
            <p
                class="lg:w-[500px] lg:h-[54px] mt-5 mx-auto font-lato font-normal text-[15px] leading-[152%] tracking-[0%] text-center">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
                dolore magna aliqua.
            </p>
        </div>

        <div class="grid grid-cols-3 gap-6 mt-10">

            <div
                class="relative col-span-2 w-full rounded-[10px] shadow-[0_0_2.9px_0_#00000040] bg-white flex lg:flex-row flex-col gap-4 px-5 pt-10 overflow-hidden">

                <!-- Background icon, positioned absolutely -->
                <div class="absolute pointer-events-none flex justify-center items-center">
                    <x-icon name="grid" />
                </div>

                <!-- Content, above the icon -->
                <div class="relative z-10">
                    <h1 class="font-lato font-semibold text-[20px] leading-[100%] tracking-normal text-[#263238]">
                        Independent Tailors
                    </h1>

                    <p class="mt-5 font-lato font-normal text-[15px] leading-[152%] tracking-normal text-[#636363]">
                        MaapLo is built to help individual tailors handle everyday tasks effortlessly. It stores client
                        measurements, tracks orders, and generates invoices — all in one place. With a user-friendly
                        interface, it takes the hassle out of managing your workflow.
                    </p>

                    <p class="mt-5 font-lato font-normal text-[16px] leading-[152%] tracking-normal text-[#636363]">
                        Designed for mobility and ease, MaapLo keeps you organized and saves valuable time. Whether you're
                        in
                        your shop or on the move, you can deliver a professional experience right from your mobile device —
                        so you can focus more on perfecting your craft.
                    </p>
                </div>

                <div class="relative z-10">
                    <x-icon name="section8-img1" />
                </div>
            </div>


            <!-- Second div: spans 1 column -->
            <div class="flex flex-col w-full rounded-[10px] shadow-[0px_0px_2.9px_0px_#00000040] bg-white px-5 pt-2">
                {{-- <x-icon name="grid" /> --}}
                <div class="item-center mx-auto h-full">
                    <x-icon name="section8-img2" />
                </div>
                <div>
                    <h1 class="font-lato font-semibold text-[20px] leading-[100%] tracking-normal text-[#263238]">
                        Boutique Owners
                    </h1>
                    <p class="my-5 font-lato font-normal text-[16px] leading-[152%] tracking-normal text-[#636363]">
                        Boutique owners can streamline operations with MaapLo — managing clients, orders, and progress
                        anytime, anywhere. Stay efficient, reduce errors, and boost your brand’s service with scalable
                        technology.
                    </p>
                </div>

            </div>
        </div>

        <div class="grid grid-cols-3 gap-6 mt-10">
            <!-- First div: spans 2 columns -->
            <div class="flex flex-col w-full rounded-[10px] shadow-[0px_0px_2.9px_0px_#00000040] bg-white px-5 pt-10">

                <div class="item-center mx-auto h-full">
                    <x-icon name="section8-img4" />
                </div>
                <div>
                    <h1 class="font-lato font-semibold text-[20px] leading-[100%] tracking-normal text-[#263238]">
                        Freelance/Field Tailors
                    </h1>
                    <p class="mt-5 font-lato font-normal text-[16px] leading-[152%] tracking-normal text-[#636363]">
                        For tailors who work on the go, MaaP Lo is the perfect mobile assistant. Access and update client
                        data, record measurements offline, and manage orders from any location.
                    </p>
                    <p class="mt-5 font-lato font-normal text-[16px] leading-[152%] tracking-normal text-[#636363]">
                        Whether you’re visiting homes or taking on-site fittings, MaaP Lo keeps your workflow seamless and
                        your service efficient.
                    </p>
                </div>
            </div>
            <div class="flex flex-col w-full rounded-[10px] shadow-[0px_0px_2.9px_0px_#00000040] bg-white px-5 pt-10">
                <div>
                    <h1 class="font-lato font-semibold text-[20px] leading-[100%] tracking-normal text-[#263238]">
                        Fashion Designers
                    </h1>
                    <p class="mt-5 font-lato font-normal text-[16px] leading-[152%] tracking-normal text-[#636363]">
                        Fashion designers use MaapLo to manage client profiles, fittings, orders, and production — ensuring
                        accuracy and smooth backend workflow.
                    </p>
                </div>
                <div class="item-center mx-auto h-full">
                    <x-icon name="section8-img3" />
                </div>

            </div>

            <!-- Second div: spans 1 column -->
            <div class="flex flex-col w-full rounded-[10px] shadow-[0px_0px_2.9px_0px_#00000040] bg-white px-5 pt-10">
                <div>
                    <h1 class="font-lato font-semibold text-[20px] leading-[100%] tracking-normal text-[#263238]">
                        Fashion Designers
                    </h1>
                    <p class="mt-5 font-lato font-normal text-[16px] leading-[152%] tracking-normal text-[#636363]">
                        Fashion designers use MaapLo to manage client profiles, fittings, orders, and production — ensuring
                        accuracy and smooth backend workflow.
                    </p>
                </div>
                <div class="item-center mx-auto h-full">
                    <x-icon name="section8-img3" />
                </div>

            </div>
        </div>

    </div>
@endsection
