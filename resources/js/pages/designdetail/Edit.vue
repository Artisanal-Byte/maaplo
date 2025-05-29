<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { defineProps, ref, computed, watch } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import Input from '@/components/InputWithLabel.vue';
import Button from '@/components/Button.vue';
import { Icon } from '@iconify/vue';

const toast = new ToastMagic();

const props = defineProps({
  designDetail: Object,
  errors: Object,
});

const imagePreview = ref(null);

const form = useForm({
  body_section: props.designDetail.body_section,
  gender: props.designDetail.gender,
  body_part: props.designDetail.body_part,
  value: props.designDetail.value,
  image: props.designDetail.image,
});

watch(() => form.image, (newVal) => {
  if (typeof newVal === 'string' && newVal.includes('<svg')) {
    let updated = newVal;
    updated = updated.replace(/width="[^"]*"/, 'width="50"');
    updated = updated.replace(/height="[^"]*"/, 'height="50"');
    updated = updated.replace(/style="[^"]*?width:\s*\d+[^;]*;?[^"]*?"/, '');
    updated = updated.replace(/style="[^"]*?height:\s*\d+[^;]*;?[^"]*?"/, '');

    if (updated !== newVal) {
      form.image = updated;
    }
  }
});

const updateDesignDetail = () => {
  if (form.image?.includes('<svg')) {
    form.image = form.image
      .replace(/width="[^"]*"/, 'width="50"')
      .replace(/height="[^"]*"/, 'height="50"');
  }
  form.transform(data => ({ ...data, _method: 'put' })).post(route('design-details.update', props.designDetail.id), {
    onSuccess: () => toast.success('Design Detail updated successfully!'),
    onError: () => toast.error('Update failed. Please check the form.'),
  });
};
</script>

<template>
  <AppLayout>
    <div class="px-6 py-10 max-w-5xl mx-auto">
      <!-- Header -->
      <div class="flex justify-between items-center mb-10">
        <h1 class="text-4xl font-extrabold text-primary flex items-center gap-3">
          <Icon icon="mdi:vector-square-edit" width="32" height="32" />
          Edit Design Detail
        </h1>
        <Link
          :href="route('design-details.index')"
          class="flex items-center gap-2 text-gray-500 hover:text-primary transition-colors duration-200"
        >
          <Icon icon="material-symbols:arrow-back-rounded" width="26" height="26" />
          <span class="font-semibold text-lg">Back</span>
        </Link>
      </div>

      <!-- Form -->
      <form @submit.prevent="updateDesignDetail" class="bg-white p-10 rounded-xl shadow-lg space-y-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <!-- Body Section -->
          <div>
            <label for="body_section" class="block text-lg font-semibold text-gray-800 mb-2">
              Body Section <span class="text-red-600">*</span>
            </label>
            <select
              id="body_section"
              v-model="form.body_section"
              class="w-full rounded-md border border-gray-300 px-4 py-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
              required
            >
              <option disabled value="">Select body section</option>
              <option value="Upper">Upper</option>
              <option value="Lower">Lower</option>
            </select>
            <p v-if="props.errors.body_section" class="mt-1 text-sm text-red-600">{{ props.errors.body_section }}</p>
          </div>

          <!-- Gender -->
          <div>
            <label for="gender" class="block text-lg font-semibold text-gray-800 mb-2">
              Gender <span class="text-red-600">*</span>
            </label>
            <select
              id="gender"
              v-model="form.gender"
              class="w-full rounded-md border border-gray-300 px-4 py-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
              required
            >
              <option value="m">Male</option>
              <option value="f">Female</option>
              <option value="o">Other</option>
            </select>
            <p v-if="props.errors.gender" class="mt-1 text-sm text-red-600">{{ props.errors.gender }}</p>
          </div>

          <!-- Body Part -->
          <Input
            type="text"
            label="Body Part"
            v-model="form.body_part"
            :error="props.errors.body_part"
            placeholder="Enter body part"
            required
          >
            <template #icon>
              <Icon icon="mdi:human-male-height" width="22" height="22" />
            </template>
          </Input>

          <!-- Value -->
          <Input
            type="text"
            label="Value"
            v-model="form.value"
            :error="props.errors.value"
            placeholder="Enter value"
            required
          >
            <template #icon>
              <Icon icon="mdi:tag-text" width="22" height="22" />
            </template>
          </Input>
        </div>

        <!-- SVG Path -->
        <Input
          type="textarea"
          label="SVG Path or full SVG markup"
          v-model="form.image"
          :error="props.errors.image"
          placeholder="Paste your SVG path or full SVG markup here"
          required
          rows="5"
        >
          <template #icon>
            <Icon icon="mdi:vector-line" width="22" height="22" />
          </template>
        </Input>

        <!-- SVG Preview -->
        <div class="mt-6 border border-gray-300 rounded-lg p-6 bg-gray-50 flex justify-center items-center min-h-[120px]">
          <label class="sr-only">SVG Preview</label>
          <div v-if="form.image?.includes('<svg')" v-html="form.image" class="max-w-[120px] max-h-[120px]"></div>
          <svg
            v-else
            width="120"
            height="120"
            viewBox="0 0 100 100"
            class="stroke-black fill-none"
          >
            <path :d="form.image" stroke="black" stroke-width="2" />
          </svg>
        </div>

        <!-- Submit Button -->
        <div>
          <Button
            type="submit"
            :color="'primary'"
            :rounded="'full'"
            :textSize="'md'"
            class="w-full py-4 font-semibold hover:scale-105 transform transition-transform duration-300"
            :disabled="form.processing"
          >
            <Icon icon="mdi:check-bold" width="22" class="mr-3" />
            Update Design Detail
          </Button>
        </div>
      </form>
    </div>
  </AppLayout>
</template>
