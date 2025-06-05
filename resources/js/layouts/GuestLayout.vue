<script setup>
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
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M4 6h16M4 12h16M4 18h16" />
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
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Lato&display=swap');

.font-lato {
  font-family: 'Lato', sans-serif;
}
</style>
