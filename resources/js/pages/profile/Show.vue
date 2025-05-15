<script setup>
import Button from '@/components/Button.vue';
import Input from '@/components/InputWithLabel.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps(['user'])
const form = useForm({
  name: props.user.name || '',
  email: props.user.email || '',
  phone: props.user.phone || '',
  organization_name: props.user.organization_name || '',
  organization_logo: props.user.organization_logo || '',
  subscription_plan: props.user.subscription_plan || '',
  validity: props.user.validity || ''
});

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
      <h1 class="text-[24px] leading-[16px] font-bold tracking-[0] text-gray-800 font-[Convergence]">
        Profile
      </h1>
      <div class="flex flex-col lg:mt-5 gap-3 rounded-lg lg:border lg:border-primary p-0 lg:p-4">
        <h1 class="text-xl font-bold lg:mt-0 mt-6">Enter Profile Details</h1>
        <div>
          <!-- <span class="text-red-500">*</span> -->
          <Input type="text" label="Name" color="grayBorder" :required="true" placeholder="Enter Customer Name"
            v-model="form.name" />
        </div>
        <div>
          <Input type="email" label="Email" color="grayBorder" :required="true" v-model="form.email" />
        </div>
        <div>
          <Input type="text" label="Phone" color="grayBorder" :required="true" v-model="form.phone" />
        </div>
        <div>
          <Input type="text" label="Organization Name" color="grayBorder" :required="true" v-model="form.organization_name" />
        </div>
        <div>
          <Input type="file" label="Organization Logo" color="grayBorder" :required="true" v-model="form.organization_logo" />
        </div>
        <div>
          <Input type="text" label="Subscription Plan" color="grayBorder" :required="true" v-model="form.subscription_plan" />
          <!-- <Button :color="'primary'" :padding="'md'" :rounded="'full'" :textSize="'sm'" @click="submit">
            Upgrade Plan
          </Button> -->
        </div>
        <div>
          <Input type="date" label="Validity" color="grayBorder" :required="true" v-model="form.validity" />
        </div>
        <Button :color="'primary'" :padding="'md'" :rounded="'full'" :textSize="'sm'" @click="submit">
          Submit
        </Button>
         
      </div>
    </div>
  </AppLayout>
</template>
