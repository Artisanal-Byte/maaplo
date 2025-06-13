<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import GuestLayout from '@/layouts/GuestLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import { ref } from 'vue';
import { Icon } from '@iconify/vue';

const showPassword = ref(false);
const showConfirmationPassword = ref(false);
function togglePassword() {
    showPassword.value = !showPassword.value;
}
function toggleConfirmationPassword() {
    showConfirmationPassword.value = !showConfirmationPassword.value;
}
const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};

</script>

<template>
    <GuestLayout>
        <div
            class="relative max-w-7xl bg-[#FBFBFB] flex items-center justify-center px-4 py-8 lg:px-8 lg:py-12 mx-auto w-full max-h-7xl mt-0 lg:mt-0">
            <div class="flex flex-col lg:flex-row items-center justify-between w-full max-w-[1400px] mx-auto">

                <!-- Left Side: Register Form -->
                <div class="w-full lg:w-1/2 flex justify-center">
                    <div class="relative z-10 ">
                        <form @submit.prevent="submit" class="flex flex-col gap-6 w-full max-w-sm">
                            <h1
                                class="font-lato font-bold text-[40px] leading-[100%] tracking-[0%] text-center text-gray-800">
                                Register
                            </h1>
                            <p class="font-lato text-[18px] font-normal text-center text-gray-600">
                                Please fill in your details to create a new account.
                            </p>

                            <!-- Name -->
                            <div class="grid gap-2">
                                <!-- <Label for="name">Name</Label> -->
                                <Input id="name" type="text" required autofocus :tabindex="1" autocomplete="name"
                                    v-model="form.name" placeholder="Name" class="border border-black h-12" />
                                <InputError :message="form.errors.name" />
                            </div>

                            <!-- Email -->
                            <div class="grid gap-2">
                                <!-- <Label for="email">Email address</Label> -->
                                <Input id="email" type="email" required :tabindex="2" autocomplete="email"
                                    v-model="form.email" placeholder="Email" class="border border-black h-12" />
                                <InputError :message="form.errors.email" />
                            </div>

                            <!-- Password -->
                            <div class="grid gap-2">
                                <!-- <Label for="password">Password</Label> -->
                                <div class="relative w-full">
                                    <Input :type="showPassword ? 'text' : 'password'" id="password" required
                                        :tabindex="3" autocomplete="new-password" v-model="form.password"
                                        placeholder="Password" class="border border-black h-12" />
                                    <InputError :message="form.errors.password" />
                                    <!-- Toggle Icon -->
                                    <span class="absolute top-1/2 right-4 transform -translate-y-1/2 cursor-pointer"
                                        @click="togglePassword">
                                        <Icon v-if="showPassword" icon="mdi:hide" width="20" height="20" class="text-gray-500"/>
                                        <Icon v-else icon="zondicons:view-show" width="20" height="20" class="text-gray-500" />
                                    </span>
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div class="grid gap-2">
                                <div class="relative w-full">
                                    <!-- <Label for="password_confirmation">Confirm Password</Label> -->
                                    <Input :type="showConfirmationPassword ? 'text' : 'password'" id="password_confirmation"
                                        required :tabindex="4" autocomplete="new-password"
                                        v-model="form.password_confirmation" placeholder="Confirm password"
                                        class="border border-black h-12" />
                                    <InputError :message="form.errors.password_confirmation" />
                                    <!-- Toggle Icon -->
                                    <span class="absolute top-1/2 right-4 transform -translate-y-1/2 cursor-pointer"
                                        @click="toggleConfirmationPassword">
                                        <Icon v-if="showConfirmationPassword" icon="mdi:hide" width="20" height="20" class="text-gray-500" />
                                        <Icon v-else icon="zondicons:view-show" width="20" height="20" class="text-gray-500"/>
                                    </span>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <Button type="submit"
                                class="w-full mt-2 bg-[#263238] text-white hover:bg-[#263238]-dark rounded-md py-2"
                                tabindex="5" :disabled="form.processing">
                                <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin mr-2" />
                                Create account
                            </Button>

                            <!-- Already have an account -->
                            <div class="text-center text-sm text-muted-foreground mt-4">
                                Already have an account?
                                <TextLink :href="route('login')" class="text-primary underline underline-offset-2"
                                    :tabindex="6">
                                    Log in
                                </TextLink>
                            </div>
                        </form>
                    </div>
                </div>


                <!-- Right Side: Image Card -->
                <div class="hidden lg:block w-full lg:w-1/2 flex justify-center">
                    <div
                        class="w-[500px] h-[500px] rounded-tl-[20px] rounded-tr-[20px] rounded-br-[20px] rounded-bl-[50px] bg-black shadow-[0px_4px_75.3px_0px_#FFFFFF] overflow-hidden">
                        <img src="/images/loginpage.svg" alt="Login Illustration"
                            class="w-full h-full object-cover rounded-tl-[20px] rounded-tr-[20px] rounded-br-[20px] rounded-bl-[50px]" />
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
