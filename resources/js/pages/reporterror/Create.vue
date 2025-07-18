<script setup>
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, usePage, router, Link } from '@inertiajs/vue3'
import { ref, reactive, computed } from 'vue'
import Input from '@/components/InputWithLabel.vue'
import Button from '@/components/Button.vue'
import Loader from '@/components/Loader.vue'
import { Icon } from '@iconify/vue'

const toast = new ToastMagic()
const loading = ref(false)

const errorTypes = [
    'Functionality Issue',
    'App Crash / Freezing',
    'Slow Performance / Loading Time',
    'ui/ux Issue',
    'Security Issue',
    'Upload/Download Issue',
    'Calculation/Logic Error',
    'Search/Filter Not Working',
    'Others'
]

const form = reactive({
    description: '',
    screenshot: null,
    url: '',
    error_type: '',
    other_error_type: ''
})

const errors = computed(() => usePage().props.errors || {})
const handleScreenshot = (event) => {
    form.screenshot = event.target.files[0]
}

const submitForm = () => {
    loading.value = true

    // Client-side validation
    if (form.error_type === 'Others' && !form.other_error_type.trim()) {
        toast.error('Please specify the error when "Others" is selected.')
        loading.value = false
        return
    }

    const formData = new FormData()
    formData.append('description', form.description)
    if (form.screenshot) formData.append('screenshot', form.screenshot)
    if (form.url) formData.append('url', form.url)
    formData.append('error_type', form.error_type)
    if (form.error_type === 'Others') {
        formData.append('other_error_type', form.other_error_type)
    }

    router.post('/reporterror', formData, {
        onSuccess: () => {
            toast.success('Your error report has been submitted!')
            Object.assign(form, {
                description: '',
                screenshot: null,
                url: '',
                error_type: '',
                other_error_type: ''
            })
            loading.value = false
        },
        onError: () => {
            toast.error('Failed to submit. Please check all fields.')
            loading.value = false
        }
    })
}

</script>

<template>

    <Head title="Report Error" />

    <AppLayout>
        <div class="px-4 py-8 max-w-3xl mx-auto">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-primary flex items-center gap-2 font-[Convergence]">
                    <Icon icon="material-symbols:bug-report-outline" width="28" height="28" />
                    Report an Error
                </h1>
                <Link :href="route('dashboard')" class="flex items-center gap-2 text-gray-600 hover:text-black">
                <Icon icon="material-symbols:arrow-back-rounded" width="24" height="24" />
                <span class="text-md font-medium">Back</span>
                </Link>
            </div>

            <!-- Loader -->
            <Loader v-if="loading" :message="'Submitting your report...'" />

            <!-- Form -->
            <div class="bg-white shadow-md border-t-4 border-primary rounded-lg p-6">
                <div class="grid grid-cols-1 gap-6">
                    <!-- Error Description -->
                    <Input v-model="form.description" label="Error Description" type="textarea"
                        placeholder="Describe the error you're experiencing" :error="errors.description"
                        required="true">
                    <template #icon>
                        <Icon icon="mdi:comment-alert-outline" width="24" height="24" />
                    </template>
                    </Input>

                    <!-- Error Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                            <Icon icon="mdi:alert-circle-outline" width="20" /> Error Type<span
                                class="text-red-500">*</span>
                        </label>
                        <select v-model="form.error_type" required class="border p-2 rounded w-full">
                            <option value="" disabled>Select error type</option>
                            <option v-for="type in errorTypes" :key="type" :value="type">
                                {{ type }}
                            </option>
                        </select>
                        <p v-if="errors.error_type" class="text-red-600 text-sm mt-1">{{ errors.error_type }}</p>
                    </div>

                    <!-- Other Error Type -->
                    <Input v-if="form.error_type === 'Others'" v-model="form.other_error_type"
                        label="Please Specify the Error" placeholder="E.g., PDF not generating properly"
                        :error="errors.other_error_type" :required="true">
                    <template #icon>
                        <Icon icon="mdi:pencil-outline" width="24" />
                    </template>
                    </Input>
                    <p v-if="errors.other_error_type" class="text-red-600 text-sm mt-1">{{ errors.other_error_type }}
                    </p>


                    <!-- Page URL -->
                    <Input v-model="form.url" label="URL of the Page"
                        placeholder="https://yourapp.com/where-error-happened" :error="errors.url">
                    <template #icon>
                        <Icon icon="mdi:web" width="24" />
                    </template>
                    </Input>

                    <!-- Screenshot Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                            <Icon icon="mdi:image-outline" width="20" /> Screenshot (Optional)
                        </label>
                        <input type="file" accept="image/*" @change="handleScreenshot"
                            class="border p-2 rounded w-full" />
                        <p v-if="errors.screenshot" class="text-red-600 text-sm">{{ errors.screenshot }}</p>
                    </div>


                </div>

                <!-- Submit Button -->
                <div class="mt-6">
                    <Button @click="submitForm" :disabled="loading" :color="'primary'"
                        class="w-full flex justify-center items-center hover:scale-105 transition-transform duration-200">
                        <Icon icon="mdi:send" width="20" height="20" class="mr-2" />
                        Submit Error Report
                    </Button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
