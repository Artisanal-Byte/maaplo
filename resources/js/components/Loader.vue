<script setup>
defineProps();
</script>

<template>
  <div class="fixed inset-0 z-50 bg-black bg-opacity-50 backdrop-blur-xl flex items-center justify-center pointer-events-auto animate-fade-in">
    <div class="text-center flex flex-col items-center space-y-4">
      <div>
        <div
          class="h-24 w-24 border-8 rounded-full spinner"
          style="border-color: white var(--text-primary) white var(--text-primary);"
        ></div>
      </div>
      <p class="mt-4 text-white font-semibold text-lg animate-slide-up opacity-90">
        Updating order, please wait...
      </p>
    </div>
  </div>
</template>

<style>
:root {
  --text-primary: #167893;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}
.spinner {
  animation: spin 1s linear infinite;
}

@keyframes fade-in {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}
.animate-fade-in {
  animation: fade-in 1s ease-out;
}

@keyframes slide-up {
  from {
    transform: translateY(10px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}
.animate-slide-up {
  animation: slide-up 0.6s ease-out;
}
</style>
