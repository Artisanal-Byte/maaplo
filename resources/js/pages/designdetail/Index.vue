<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { ref } from 'vue';
import { router, Link, Head } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import Button from '@/components/Button.vue';

const props = defineProps({
  designDetails: Array,
});

const showModal = ref(false);
const selectedDetail = ref(null);

function openConfirmationModal(detail) {
  selectedDetail.value = detail;
  showModal.value = true;
}

function cancelModal() {
  showModal.value = false;
  selectedDetail.value = null;
}

function confirmDelete() {
  if (!selectedDetail.value) return;

  router.delete(route('design-details.destroy', selectedDetail.value.id));
  showModal.value = false;
}
</script>

<template>
    <Head title="DesignDetails" />
  <AppLayout>
    <div class="max-w-7xl mx-auto py-8 px-4">
      <!-- Header Section -->
      <div class="flex justify-between mb-6">
        <h1 class="text-2xl font-bold text-primary">Design Details</h1>
        <div class="flex gap-4 items-center text-gray-600">
          <Link :href="route('design-details.create')" class="relative group">
            <Icon icon="material-symbols:add-rounded" width="30" height="30" />
            <div
              class="absolute top-full mt-1 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 bg-gray-800 text-white text-xs rounded px-2 py-1 transition pointer-events-none z-10">
              Create Design
            </div>
          </Link>
        </div>
      </div>

      <!-- Desktop Table View -->
      <div class="hidden md:block mt-6 overflow-hidden rounded-lg">
        <table class="min-w-full border">
          <thead class="bg-primary text-white">
            <tr>
              <th class="p-2 border text-center">Body Section</th>
              <th class="p-2 border text-center">Gender</th>
              <th class="p-2 border text-center">Body Part</th>
              <th class="p-2 border text-center">Value</th>
              <th class="p-2 border text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="detail in designDetails" :key="detail.id" class="hover:bg-gray-100 transition">
              <td class="p-2 border text-center">{{ detail.body_section }}</td>
              <td class="p-2 border text-center capitalize">
                {{ detail.gender === 'm' ? 'Male' : detail.gender === 'f' ? 'Female' : 'Other' }}
              </td>
              <td class="p-2 border text-center">{{ detail.body_part }}</td>
              <td class="p-2 border text-center">{{ detail.value }}</td>
              <td class="p-2 border text-center">
                <div class="flex justify-center gap-4">
                  <Link :href="route('design-details.edit', detail.id)" class="relative group">
                    <Icon icon="ri:edit-fill" class="text-primary" width="20" height="20" />
                    <div
                      class="absolute bottom-full mb-1 left-1/2 -translate-x-1/2 bg-black text-white text-xs rounded px-2 py-1 opacity-0 group-hover:opacity-100 z-10">
                      Edit
                    </div>
                  </Link>

                  <button @click="openConfirmationModal(detail)" class="relative group">
                    <Icon icon="mdi:delete" class="text-red-600" width="20" height="20" />
                    <div
                      class="absolute bottom-full mb-1 left-1/2 -translate-x-1/2 bg-black text-white text-xs rounded px-2 py-1 opacity-0 group-hover:opacity-100 z-10">
                      Delete
                    </div>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Delete Confirmation Modal -->
      <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded shadow-lg w-11/12 max-w-md">
          <h2 class="text-xl font-bold mb-3">Are you sure?</h2>
          <p class="text-gray-700 mb-4">
            You are about to delete <strong>{{ selectedDetail?.body_section }}</strong>.
          </p>
          <div class="flex justify-end gap-3">
            <Button @click="cancelModal" color="gray">Cancel</Button>
            <Button @click="confirmDelete" color="red">Delete</Button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
