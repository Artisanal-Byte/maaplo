<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

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
    <div
        class="relative max-w-7xl bg-[#FBFBFB] flex items-center justify-center px-4 py-8 lg:px-8 lg:py-12 mx-auto w-full max-h-7xl mt-10 lg:mt-20">
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
                            <Input id="password" type="password" required :tabindex="3" autocomplete="new-password"
                                v-model="form.password" placeholder="Password" class="border border-black h-12" />
                            <InputError :message="form.errors.password" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="grid gap-2">
                            <!-- <Label for="password_confirmation">Confirm Password</Label> -->
                            <Input id="password_confirmation" type="password" required :tabindex="4"
                                autocomplete="new-password" v-model="form.password_confirmation"
                                placeholder="Confirm password" class="border border-black h-12" />
                            <InputError :message="form.errors.password_confirmation" />
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

</template>
