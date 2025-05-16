<script setup>
import Button from '@/components/Button.vue';
import Input from '@/components/InputWithLabel.vue';
import AppLayout from '@/layouts/AppLayout.vue';

</script>

<template>
    <AppLayout>
        <div class="px-4 py-8 max-w-6xl mx-auto">
            <h1 class="text-[24px] leading-[16px] font-bold tracking-[0] text-gray-800 font-[Convergence]">
                User
            </h1>
            <div class="flex flex-col lg:mt-5 gap-3 rounded-lg lg:border lg:border-primary p-0 lg:p-4">
                <h1 class="text-xl font-bold lg:mt-0 mt-6">Enter User Details</h1>
                <div>
                    <!-- <span class="text-red-500">*</span> -->
                    <Input type="text" label="Name" color="grayBorder" :required="true"
                        placeholder="Enter Customer Name" />
                </div>
                <div>
                    <Input type="email" label="Email" color="grayBorder" :required="true" />
                </div>
                <div>
                    <Input type="text" label="Phone" color="grayBorder" :required="true" />
                </div>
                <div>
                    <Input type="text" label="Organization Name" color="grayBorder" :required="true" />
                </div>
                <div>
                    <Input type="text" label="Subscription Plan" color="grayBorder" :required="true" />

                </div>
                <div>
                    <Input type="date" label="Validity" color="grayBorder" :required="true" />
                </div>
                <Button :color="'primary'" :padding="'md'" :rounded="'full'" :textSize="'sm'">
                    Submit
                </Button>

            </div>
        </div>
    </AppLayout>
</template>