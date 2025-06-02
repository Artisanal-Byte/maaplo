<script setup>
import { ref, defineEmits, watch } from 'vue'
import { Icon } from '@iconify/vue'
import { useOrderFormStore } from '@/stores/orderFormStore'
const formStore = useOrderFormStore()
const fileInputGallery1 = ref(null)
const fileInputCamera1 = ref(null)
const fileInputGallery2 = ref(null)
const fileInputCamera2 = ref(null)

function triggerUpload(type, index) {
  if (type === 'gallery') {
    index === 1 ? fileInputGallery1.value.click() : fileInputGallery2.value.click()
  } else if (type === 'camera') {
    index === 1 ? fileInputCamera1.value.click() : fileInputCamera2.value.click()
  }
}

function onFileChange(event, index) {
  const file = event.target.files[0]
  if (file && file.type.startsWith('image/')) {

    if (index === 1) {
      formStore.order_items_template.cloth_img1 = file
    } else if (index === 2) {
      formStore.order_items_template.cloth_img2 = file
    }
  }
}

const previewImage = (file) => {
  return URL.createObjectURL(file)
}
</script>

<template>
  <div>
    <h1 class="font-medium leading-4 tracking-normal font-lato">Cloth Images</h1>
    <div class="flex gap-10">
      <!-- Cloth 1 -->
      <div class="w-40 mt-5 h-full rounded-md p-2 shadow-[0px_0px_6.1px_0px_#00000040] bg-white">
        <div class="flex items-center justify-between mb-2">
          <h1 class="font-normal text-[14px] leading-[8px] text-[#8C8C8C] font-lato">Cloth 1</h1>
          <div class="flex gap-2">
            <button @click="triggerUpload('gallery', 1)" class="relative group">
              <Icon icon="material-symbols:upload" width="16" height="16" class="text-primary hover:text-gray-700" />
              <div
                class="absolute top-full mt-2 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition bg-gray-800 text-white text-xs rounded py-1 px-2 pointer-events-none z-10">
                upload
              </div>
            </button>

            <button @click="triggerUpload('camera', 1)" class="relative group">
              <Icon icon="tabler:capture" width="16" height="16" class="text-primary hover:text-gray-700" />
              <div
                class="absolute top-full mt-2 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition bg-gray-800 text-white text-xs rounded py-1 px-2 pointer-events-none z-10">
                capture
              </div>
            </button>
          </div>

        </div>
        <div class="bg-[#BDDBDB3D] p-2 rounded h-24 mb-2">
          <img v-if="formStore.order_items_template.cloth_img1" :src="previewImage(formStore.order_items_template.cloth_img1)" alt="Preview" class="w-32 h-20 object-cover rounded" />
        </div>
        <input ref="fileInputGallery1" type="file" class="hidden" accept="image/*" @change="e => onFileChange(e, 1)" />
        <input ref="fileInputCamera1" type="file" class="hidden" accept="image/*" capture="environment"
          @change="e => onFileChange(e, 1)" />
      </div>

      <!-- Cloth 2 -->
      <div class="w-40 mt-5 h-full rounded-md p-2 shadow-[0px_0px_6.1px_0px_#00000040] bg-white">
        <div class="flex items-center justify-between mb-2">
          <h1 class="font-normal text-[14px] leading-[8px] text-[#8C8C8C] font-lato">Cloth 2</h1>
          <div class="flex gap-2">
            <button @click="triggerUpload('gallery', 2)" class="relative group">
              <Icon icon="material-symbols:upload" width="16" height="16" class="text-primary hover:text-gray-700" />
              <div
                class="absolute top-full mt-2 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition bg-gray-800 text-white text-xs rounded py-1 px-2 pointer-events-none z-10">
                upload
              </div>
            </button>
            <button @click="triggerUpload('camera', 2)" class="relative group">
              <Icon icon="tabler:capture" width="16" height="16" class="text-primary hover:text-gray-700" />
              <div
                class="absolute top-full mt-2 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition bg-gray-800 text-white text-xs rounded py-1 px-2 pointer-events-none z-10">
                capture
              </div>
            </button>
          </div>
        </div>
        <div class="bg-[#BDDBDB3D] p-2 rounded h-24 mb-2">
          <img v-if="formStore.order_items_template.cloth_img2" :src="previewImage(formStore.order_items_template.cloth_img2)" alt="Preview" class="w-32 h-20 object-cover rounded" />
        </div>
        <input ref="fileInputGallery2" type="file" class="hidden" accept="image/*" @change="e => onFileChange(e, 2)" />
        <input ref="fileInputCamera2" type="file" class="hidden" accept="image/*" capture="environment"
          @change="e => onFileChange(e, 2)" />
      </div>
    </div>
  </div>
</template>
