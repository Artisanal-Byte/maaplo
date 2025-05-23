<script setup>
import Button from '@/components/Button.vue';
import Input from '@/components/InputWithLabel.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
const props = defineProps(['user', 'errors'])
const form = useForm({
  name: props.user.name || '',
  email: props.user.email || '',
  phone: props.user.phone || '',
  organization_name: props.user.organization_name || '',
  organization_logo: props.user.organization_logo || '',
  subscription_plan: props.user.subscription_plan || '',
  validity: props.user.validity || ''
});
console.log('Form Data:', form);



function submit() {
  //i want save this data in the database

  form.put(route('profile.update', props.user.id), {
    onSuccess: () => {
      // Handle success, e.g., show a success message or redirect
      console.log('Profile updated successfully');
    },
    onError: (error) => {
      // Handle error, e.g., show an error message
      console.log('Failed to update profile: ' + error.name);
    },

  });
}
</script>
<template>
  <AppLayout>

    <div class="px-4 py-8 max-w-6xl mx-auto">
      <h1 class="text-[24px] text-primary leading-[16px] font-bold tracking-[0] text-gray-800 font-[Convergence]">
        Profile
      </h1>
      <div class="flex flex-col lg:mt-5 gap-3 rounded-lg lg:border lg:border-primary p-0 lg:p-4">
        <h1 class="text-xl font-bold lg:mt-0 mt-6">Enter Profile Details</h1>
        <div>
          <!-- <span class="text-red-500">*</span> -->
          <Input type="text" label="Name" color="grayBorder" :required="true" placeholder="Enter Customer Name"
            v-model="form.name" :error="errors.name">
          <template #icon>
            <Icon icon="bitcoin-icons:contacts-filled" width="24" height="24" class="text-black" />
          </template>
          </Input>
        </div>
        <div>
          <Input type="email" label="Email" color="grayBorder" :required="true" v-model="form.email"
            :error="errors.email">
          <template #icon>
            <Icon icon="ic:round-email" width="18" height="18" class="text-black ml-1" />
          </template>
          </Input>
        </div>
        <div>
          <Input type="text" label="Phone" color="grayBorder" :required="true" v-model="form.phone">
          <template #icon>
            <Icon icon="ic:round-phone" width="20" height="20" class="text-black" />
          </template>
          </Input>
        </div>
        <div>
          <Input type="text" label="Organization Name" color="grayBorder" v-model="form.organization_name">
          <template #icon>
            <Icon icon="fluent:organization-16-filled" width="20" height="20" class="text-black" />
          </template>
          </Input>
        </div>
        <div>
          <Input type="file" label="Organization Logo" color="grayBorder" v-model="form.organization_logo">
          <template #icon>
            <Icon icon="material-symbols:image-rounded" width="20" height="20" class="text-black" />
          </template>
          </Input>

        </div>
        <div>
          <Input type="text" label="Subscription Plan" color="grayBorder" :required="true"
            v-model="form.subscription_plan" :error="errors.subscription_plan">
          <template #icon>
            <Icon icon="stash:subscription-list" width="18" height="18" class="text-black" />
          </template>
          </Input>

        </div>
        <div>
          <Input type="date" label="Validity" color="grayBorder" v-model="form.validity">
          <template #icon>
            <Icon icon="material-symbols:date-range-outline-rounded" width="18" height="18" class="text-black" />
          </template>
          </Input>
        </div>
        <Button :color="'primary'" :padding="'md'" :rounded="'full'" :textSize="'sm'" @click="submit" class="mt-3">
          Submit
        </Button>

      </div>
    </div>
  </AppLayout>
</template>
