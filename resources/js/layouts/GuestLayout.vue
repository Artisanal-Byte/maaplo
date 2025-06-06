<script setup>
import { Icon } from '@iconify/vue';
import { ref } from 'vue'

const menuOpen = ref(false)

const toggleMenu = () => {
  menuOpen.value = !menuOpen.value
}

const navLinks = {
  features: 'Features',
  about: 'About Us',
  pricing: 'Pricing',
  blogs: 'Blogs',
  contact: 'Contact Us'
}
</script>
<template>
  <nav class="lg:px-20 mx-auto py-4">
    <!-- Navbar wrapper -->
    <div class="flex justify-between items-center h-[84px] mx-4 lg:mx-0">

      <img src="/images/maaplo-logo.svg" alt="Logo" class="w-40 h-40" />

      <!-- Hamburger (Mobile) -->
      <div class="lg:hidden">
        <button @click="toggleMenu" class="text-black focus:outline-none">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
      </div>

      <!-- Desktop Nav Links -->
      <div class="hidden lg:flex space-x-7 items-center">
        <a v-for="(label, id) in navLinks" :key="id" :href="'#' + id"
          class="text-black md:text-[15px] lg:text-[18px] font-lato p-2 hover:text-primary hover:border-b hover:border-primary hover:rounded-md transition duration-200">
          {{ label }}
        </a>
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
    <div v-if="menuOpen" class="lg:hidden mt-4 mx-5">
      <a v-for="(label, id) in navLinks" :key="id" :href="'#' + id"
        class="block text-black text-[18px] font-lato hover:text-primary hover:bg-gray-100 rounded p-2 transition">
        {{ label }}
      </a>
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

  <div>
    <slot />
  </div>


  <div class="bg-[#2B4146] px-5 lg:px-20 pt-16 pb-10">
    <div class="flex flex-col lg:flex-row justify-between gap-10 text-white">
      <!-- Logo Column -->
      <div class="flex items-start">
        <!-- <x-icon name="maaplo" class="w-24 h-auto" /> -->
        <img src="/images/maaplo1.svg" class="w-[150px]">
      </div>

      <!-- Repeatable Link Columns -->
      <div>
        <h1 class="font-lato font-extrabold text-[20px] leading-none mb-4">Product</h1>
        <ul class="space-y-3">
          <li class="font-lato font-medium text-[14px] leading-none">Lorem Ipsum</li>
          <li class="font-lato font-medium text-[14px] leading-none">Lorem Ipsum</li>
          <li class="font-lato font-medium text-[14px] leading-none">Lorem Ipsum</li>
          <li class="font-lato font-medium text-[14px] leading-none">Lorem Ipsum</li>
          <li class="font-lato font-medium text-[14px] leading-none">Lorem Ipsum</li>
        </ul>
      </div>

      <div>
        <h1 class="font-lato font-extrabold text-[20px] leading-none mb-4">Product</h1>
        <ul class="space-y-3">
          <li class="font-lato font-medium text-[14px] leading-none">Lorem Ipsum</li>
          <li class="font-lato font-medium text-[14px] leading-none">Lorem Ipsum</li>
          <li class="font-lato font-medium text-[14px] leading-none">Lorem Ipsum</li>
          <li class="font-lato font-medium text-[14px] leading-none">Lorem Ipsum</li>
          <li class="font-lato font-medium text-[14px] leading-none">Lorem Ipsum</li>
        </ul>
      </div>

      <div>
        <h1 class="font-lato font-extrabold text-[20px] leading-none mb-4">Product</h1>
        <ul class="space-y-3">
          <li class="font-lato font-medium text-[14px] leading-none">Lorem Ipsum</li>
          <li class="font-lato font-medium text-[14px] leading-none">Lorem Ipsum</li>
          <li class="font-lato font-medium text-[14px] leading-none">Lorem Ipsum</li>
          <li class="font-lato font-medium text-[14px] leading-none">Lorem Ipsum</li>
          <li class="font-lato font-medium text-[14px] leading-none">Lorem Ipsum</li>
        </ul>
      </div>
      <div class="col-span-2">
        <h1 class="font-lato font-extrabold text-[20px] leading-none mb-4">Follow Us</h1>
        <div class="flex gap-4 mt-10">
          <!-- <x-icon name="instagram" class="w-24 h-auto" /> -->
          <Icon icon="mdi:instagram" width="24" height="24" />
          <Icon icon="ic:baseline-facebook" width="24" height="24" />
          <Icon icon="iconoir:twitter" width="24" height="24" />
          <Icon icon="mdi:linkedin" width="24" height="24" />
        </div>
        <div class="lg:flex gap-10 mt-5 hidden">
          <input type="text" placeholder="Enter your email"
            class="mt-4 w-full border text-black border-gray-300 rounded-md p-3 focus:outline-none" />
          <button type="submit"
            class="mt-4 font-medium px-[15px] py-1 lg:py-[8px] w-[100px] bg-white text-black hover:bg-gray-600 rounded-md hover:scale-105 transition-transform duration-200">
            <a href="/">Subscribe</a>
          </button>
        </div>
      </div>
    </div>

    <div class="text-center border-t lg:border-none mt-20">
      <p
        class="lg:hidden block font-lato mt-5 lg:font-semibold text-[12px] leading-[100%] tracking-[0] text-center text-white">
        Privacy Policy</p>
      <p class="text-center mt-5 lg:mt-10 font-lato lg:font-semibold text-[15px] leading-none tracking-[0] text-white">
        2025 lorem ispaun dolor sit amet - All sed elusmod
      </p>
      <p
        class="lg:hidden block font-lato mt-5 lg:font-semibold text-[12px] leading-[100%] tracking-[0] text-center text-white">
        Privacy Policy</p>
    </div>
  </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Lato&display=swap');

.font-lato {
  font-family: 'Lato', sans-serif;
}
</style>
