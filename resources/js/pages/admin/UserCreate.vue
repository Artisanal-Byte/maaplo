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
            <h1 class="text-[24px] text-primary leading-[16px] font-bold tracking-[0] text-gray-800 font-[Convergence]">
                User
            </h1>
            <div class="flex flex-col lg:mt-5 gap-3 rounded-lg lg:border lg:border-primary p-0 lg:p-4">
                <h1 class="text-xl font-bold lg:mt-0 mt-6">Enter User Details</h1>
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
                <div class="flex items-center gap-4">
                    <h1 class="text-[16px] font-normal font-lato">Disable user :</h1>
                    <ToggleButton />
                </div>
                <Button :color="'primary'" :padding="'md'" :rounded="'full'" :textSize="'sm'" class="mt-3">
                    Submit
                </Button>

            </div>
        </div>
    </AppLayout>
</template>