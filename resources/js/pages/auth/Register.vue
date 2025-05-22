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
    <!-- <AuthBase title="Create an account" description="Enter your details below to create your account">
        <Head title="Register" /> -->
    <div class="min-h-screen flex items-center justify-center bg-[#DEEFF4] px-4">
        <div class="flex flex-col md:flex-row overflow-hidden max-w-4xl w-full bg-white shadow-md rounded-lg">
            <!-- Image Section -->
            <div class="md:w-1/2 hidden lg:block">
                <img src="https://img.freepik.com/free-vector/sign-up-concept-illustration_114360-7965.jpg?size=338&ext=jpg&ga=GA1.1.2008272138.1721174400&semt=ais_user"
                    alt="Login Illustration" class="w-full h-full object-cover" />
            </div>
            <div class="md:w-1/2 p-8 ">
                <form @submit.prevent="submit" class="flex flex-col gap-6">
                    <h1 class="text-2xl font-bold text-gray-800 text-center">Create an Account</h1>
                    <div class="grid gap-6">
                        <div class="grid gap-2">
                            <Label for="name">Name</Label>
                            <Input id="name" type="text" required autofocus :tabindex="1" autocomplete="name"
                                v-model="form.name" placeholder="Full name" />
                            <InputError :message="form.errors.name" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="email">Email address</Label>
                            <Input id="email" type="email" required :tabindex="2" autocomplete="email"
                                v-model="form.email" placeholder="email@example.com" />
                            <InputError :message="form.errors.email" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="password">Password</Label>
                            <Input id="password" type="password" required :tabindex="3" autocomplete="new-password"
                                v-model="form.password" placeholder="Password" />
                            <InputError :message="form.errors.password" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="password_confirmation">Confirm password</Label>
                            <Input id="password_confirmation" type="password" required :tabindex="4"
                                autocomplete="new-password" v-model="form.password_confirmation"
                                placeholder="Confirm password" />
                            <InputError :message="form.errors.password_confirmation" />
                        </div>

                        <Button type="submit" class="mt-2 w-full bg-primary hover:bg-primary" tabindex="5" :disabled="form.processing">
                            <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                            Create account
                        </Button>
                    </div>

                    <div class="text-center text-sm text-muted-foreground">
                        Already have an account?
                        <TextLink :href="route('login')" class="underline-offset-4 text-primary" :tabindex="6">Log in
                        </TextLink>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- </AuthBase> -->
</template>
