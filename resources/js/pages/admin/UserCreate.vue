<script setup>
import Button from '@/components/Button.vue';
import Input from '@/components/InputWithLabel.vue';
import ToggleButton from '@/components/ToggleButton.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Icon } from '@iconify/vue';
import { ref } from 'vue'

</script>

<template>
    <AppLayout>
        <div class="px-4 py-8 max-w-6xl mx-auto">
            <h1 class="flex items-center gap-2 lg:gap-2 text-[28px] leading-[16px] font-bold text-gray-800 font-[Convergence] text-primary tracking-[0]">
                <Icon icon="mdi:user" width="34" height="34" /> 
                User
            </h1>
            <div class="mt-8 lg:mt-10 gap-3 bg-white lg:p-7 rounded-lg shadow-md p-5 border-t-4 border-primary">
                <h1 class="text-xl font-bold lg:mt-0 mt-2">Enter User Details</h1>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 gap-x-10 mt-3">
                    <div>
                        <!-- <span class="text-red-500">*</span> -->
                        <Input type="text" label="Name" color="grayBorder" :required="true"
                            placeholder="Enter Customer Name">
                        <template #icon>
                            <Icon icon="bitcoin-icons:contacts-filled" width="24" height="24" class="text-black" />
                        </template>
                        </Input>
                    </div>
                    <div>
                        <Input type="email" label="Email" color="grayBorder" :required="true">
                        <template #icon>
                            <Icon icon="ic:round-email" width="20" height="20" class="text-black" />
                        </template>
                        </Input>
                    </div>
                    <div>
                        <Input type="text" label="Phone" color="grayBorder" :required="true">
                        <template #icon>
                            <Icon icon="ic:round-phone" width="20" height="20" class="text-black" />
                        </template>
                        </Input>
                    </div>
                    <div>
                        <Input type="text" label="Organization Name" color="grayBorder" :required="true">
                        <template #icon>
                            <Icon icon="fluent:organization-16-filled" width="20" height="20" class="text-black" />
                        </template>
                        </Input>
                    </div>
                    <div>
                        <Input type="text" label="Subscription Plan" color="grayBorder" :required="true">
                        <template #icon>
                            <Icon icon="stash:subscription-list" width="18" height="18" class="text-black" />
                        </template>
                        </Input>
                    </div>
                    <div>
                        <Input type="date" label="Validity" color="grayBorder" :required="true">
                        <template #icon>
                            <Icon icon="material-symbols:date-range-outline-rounded" width="18" height="18"
                                class="text-black" />
                        </template>
                        </Input>
                    </div>
                </div>
                <div class="flex items-center gap-4 mt-5">
                    <h1 class="text-[16px] font-normal font-lato">Disable user :</h1>
                    <ToggleButton />
                </div>
                <Button :color="'primary'" :padding="'md'" :rounded="'full'" :textSize="'sm'" class="mt-3 w-full">
                    Submit
                </Button>

            </div>
        </div>
    </AppLayout>
</template>