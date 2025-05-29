<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import Button from '@/components/Button.vue';
import Input from '@/components/InputWithLabel.vue';

const toast = new ToastMagic(); // optional: if you're using a toast notification system

const imagePreview = ref(null);

const form = useForm({
  body_section: '',
  gender: 'm',
  body_part: '',
  value: '',
  image: null,
});

const handleImageUpload = (event) => {
  const file = event.target.files?.[0];
  if (file) {
    form.image = file;
    imagePreview.value = URL.createObjectURL(file);
  }
};

const createDesignDetail = () => {
  form.post(route('design-details.store'), {
    forceFormData: true,
    onSuccess: () => toast.success('Design Detail created successfully!'),
    onError: () => toast.error('Error creating Design Detail.'),
  });
};
</script>

<template>
  <AppLayout>
    <div class="px-4 py-8 max-w-6xl mx-auto">
      <!-- Header -->
      <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-primary flex items-center gap-2">
          <Icon icon="mdi:vector-square-edit" width="28" height="28" />
          Create Design Detail
        </h1>
        <Link :href="route('design-details.index')" class="flex items-center gap-2 text-gray-600 hover:text-black">
          <Icon icon="material-symbols:arrow-back-rounded" width="24" height="24" />
          <span class="text-md font-medium">Back</span>
        </Link>
      </div>

      <!-- Form -->
      <div class="bg-white p-8 rounded-lg shadow-md space-y-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Body Section (Dropdown) -->
          <div>
            <label class="block font-medium text-black mb-1">Body Section <span class="text-red-500">*</span></label>
            <select
              v-model="form.body_section"
              class="w-full rounded-md border-gray-300 shadow-sm focus:ring-primary focus:border-primary"
            >
              <option disabled value="">Select body section</option>
              <option value="Upper">Upper</option>
              <option value="Lower">Lower</option>
            </select>
            <p v-if="form.errors.body_section" class="text-sm text-red-500 mt-1">{{ form.errors.body_section }}</p>
          </div>

          <!-- Body Part -->
          <Input
            type="text"
            label="Body Part"
            v-model="form.body_part"
            :error="form.errors.body_part"
            placeholder="Enter body part"
            required
          >
            <template #icon>
              <Icon icon="mdi:human-male-height" width="20" height="20" />
            </template>
          </Input>

          <!-- Value -->
          <Input
            type="text"
            label="Value"
            v-model="form.value"
            :error="form.errors.value"
            placeholder="Enter value"
            required
          >
            <template #icon>
              <Icon icon="mdi:tag-text" width="20" height="20" />
            </template>
          </Input>

          <!-- Gender Select -->
          <div>
            <label class="block font-medium text-black mb-1">Gender <span class="text-red-500">*</span></label>
            <select
              v-model="form.gender"
              class="w-full rounded-md border-gray-300 shadow-sm focus:ring-primary focus:border-primary"
            >
              <option value="m">Male</option>
              <option value="f">Female</option>
              <option value="o">Other</option>
            </select>
            <p v-if="form.errors.gender" class="text-sm text-red-500 mt-1">{{ form.errors.gender }}</p>
          </div>
        </div>

        <!-- Image Upload -->
        <div>
          <label class="block font-medium text-black mb-2">Upload Image <span class="text-red-500">*</span></label>
          <input
            type="file"
            @change="handleImageUpload"
            class="block w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 cursor-pointer"
          />
          <div v-if="imagePreview" class="mt-4">
            <img :src="imagePreview" alt="Preview" class="w-64 h-64 object-contain border rounded-lg" />
          </div>
          <p v-if="form.errors.image" class="text-sm text-red-500 mt-1">{{ form.errors.image }}</p>
        </div>

        <!-- Submit Button -->
        <div>
          <Button
            @click="createDesignDetail"
            :color="'primary'"
            :rounded="'full'"
            :textSize="'sm'"
            class="w-full justify-center hover:scale-105 transition-transform"
          >
            <Icon icon="mdi:check-bold" class="mr-2" width="20" />
            Save Design Detail
          </Button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
