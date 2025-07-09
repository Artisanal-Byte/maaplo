<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import GuestLayout from '@/layouts/GuestLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import { Icon } from '@iconify/vue';
import { ref } from 'vue';

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();
const showPassword = ref(false)
function togglePassword() {
    showPassword.value = !showPassword.value
}
const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<!-- <template>
    <AuthBase title="Log in to your account" description="Enter your email and password below to log in">
        <Head title="Log in" />

        <div v-if="status" class="mb-4 text-center text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="email">Email address</Label>
                    <Input
                        id="email"
                        type="email"
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="email"
                        v-model="form.email"
                        placeholder="email@example.com"
                    />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="grid gap-2">
                    <div class="flex items-center justify-between">
                        <Label for="password">Password</Label>
                        <TextLink v-if="canResetPassword" :href="route('password.request')" class="text-sm" :tabindex="5">
                            Forgot password?
                        </TextLink>
                    </div>
                    <Input
                        id="password"
                        type="password"
                        required
                        :tabindex="2"
                        autocomplete="current-password"
                        v-model="form.password"
                        placeholder="Password"
                    />
                    <InputError :message="form.errors.password" />
                </div>

                <div class="flex items-center justify-between" :tabindex="3">
                    <Label for="remember" class="flex items-center space-x-3">
                        <Checkbox id="remember" v-model="form.remember" :tabindex="4" />
                        <span>Remember me</span>
                    </Label>
                </div>

                <Button type="submit" class="mt-4 w-full" :tabindex="4" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    Log in
                </Button>
            </div>

            <div class="text-center text-sm text-muted-foreground">
                Don't have an account?
                <TextLink :href="route('register')" :tabindex="5">Sign up</TextLink>
            </div>
        </form>
    </AuthBase>
</template> -->

<template>
    <GuestLayout>
    
        <div
            class="relative max-w-7xl bg-[#FBFBFB] flex items-center justify-center px-4 py-8 lg:px-8 lg:py-12 mx-auto w-full max-h-7xl mt-0 lg:mt-0">
            <!-- Container for form and black background side-by-side -->
            <div class="flex flex-col lg:flex-row items-center justify-between w-full max-w-[1400px] mx-auto">

                <!-- Left Side: Login Form -->
                <div class="w-full lg:w-1/2 flex justify-center">
                    <div class="relative z-10">
                        <form @submit.prevent="submit" class="flex flex-col gap-6 w-full max-w-sm">
                            <div>
                                <h1
                                    class="font-lato font-bold text-[40px] leading-[100%] tracking-[0%] text-center text-gray-800">
                                    Welcome Back
                                </h1>

                                <p
                                    class="mt-5 font-lato font-normal text-[20px] leading-[100%] tracking-[0%] text-center">
                                    Please login details below!
                                </p>

                                <div class="grid gap-2 mt-6">
                                    <!-- Email -->
                                    <div class="grid gap-2">
                                        <!-- <Label for="email">Email address</Label> -->
                                        <Input id="email" type="email" required autofocus :tabindex="1"
                                            autocomplete="email" v-model="form.email" placeholder="email"
                                            class="border border-black bg-white h-12" />
                                        <InputError :message="form.errors.email" />
                                    </div>

                                    <!-- Password -->
                                    <div class="grid gap-2 mt-4">
                                        <div class="relative w-full">
                                            <!-- Password Input -->
                                            <Input :type="showPassword ? 'text' : 'password'" id="password" required
                                                :tabindex="3" autocomplete="current-password" v-model="form.password"
                                                placeholder="Password" class="border border-black h-12 bg-white" />
                                            <InputError :message="form.errors.password" />

                                            <!-- Toggle Icon -->
                                            <span
                                                class="absolute top-1/2 right-4 transform -translate-y-1/2 cursor-pointer"
                                                @click="togglePassword">
                                                <Icon v-if="showPassword" icon="mdi:hide" width="20" height="20" class="text-gray-500" />
                                                <Icon v-else icon="zondicons:view-show" width="20" height="20" class="text-gray-500" />
                                            </span>
                                        </div>

                                        <div class="text-right">
                                            <TextLink v-if="canResetPassword" :href="route('password.request')"
                                                class="text-sm text-black" :tabindex="5">
                                                Forgot password?
                                            </TextLink>
                                        </div>
                                    </div>

                                    <!-- Remember me -->
                                    <!-- <div class="flex items-center justify-between" :tabindex="3">
                                    <Label for="remember" class="flex items-center space-x-3">
                                        <Checkbox id="remember" v-model="form.remember" :tabindex="4" />
                                        <span>Remember me</span>
                                    </Label>
                                </div> -->

                                    <!-- Submit button -->
                                    <Button type="submit" class="w-full bg-[#263238] text-white hover:bg-[#263238] rounded-md"
                                        :tabindex="4" :disabled="form.processing">
                                        <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                                        Log in
                                    </Button>
                                </div>

                                <div class="flex items-center justify-center gap-4 my-6 w-full">
                                    <div class="flex-grow border-t border-gray-400"></div>
                                    <h1 class="whitespace-nowrap text-sm text-gray-700 font-medium">Or Continue</h1>
                                    <div class="flex-grow border-t border-gray-400"></div>
                                </div>

                                <a  :href="route('auth.google')"
                                    class="w-full bg-transparent rounded-md text-black border border-gray-300 hover:bg-gray-100 flex items-center justify-center gap-2 py-5">
                                    <img src="/images/google-icon.svg" alt="Google Icon" class="w-4 h-4" />
                                    Log in with Google
                                </a>

                                <!-- Register link -->
                                <div class="mt-5 text-center text-sm text-muted-foreground">
                                    Don't have an account?
                                    <TextLink :href="route('register')" class="text-primary" :tabindex="5">
                                        Sign up
                                    </TextLink>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Side: Black background shape -->
                <div class="hidden lg:block w-full lg:w-1/2 flex items-center justify-center mt-10 lg:mt-0">
                    <div class="w-[500px] h-[500px]
                        rounded-tl-[20px] rounded-tr-[20px] rounded-br-[20px] rounded-bl-[50px]
                        bg-black shadow-[0px_4px_75.3px_0px_#FFFFFF]">
                        <img src="/images/loginpage.svg" alt="Login Illustration"
                            class="w-full h-full object-cover rounded-tl-[20px] rounded-tr-[20px] rounded-br-[20px] rounded-bl-[50px]" />
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
