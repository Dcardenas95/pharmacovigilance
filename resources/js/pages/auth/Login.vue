<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

const form = useForm({
    name: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Login" />
    
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100">
        <div class="w-full max-w-md">
            <div class="bg-white rounded-lg shadow-xl overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-[#2c4a6f] to-[#3d5a7f] py-8 px-6">
                    <h1 class="text-3xl font-bold text-white text-center tracking-wide">
                        Pharmacovigilance
                    </h1>
                </div>

                <!-- Form Container -->
                <div class="p-8">
                    <div v-if="status" class="mb-6 px-4 py-3 rounded-md bg-green-50 border border-green-200">
                        <p class="text-sm text-green-800 text-center">{{ status }}</p>
                    </div>

                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Username Field -->
                        <div>
                            <input
                                id="name"
                                type="text"
                                required
                                autofocus
                                placeholder="Username"
                                autocomplete="username"
                                v-model="form.name"
                                class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#2c4a6f] focus:border-transparent outline-none transition-all duration-200 text-gray-900 placeholder-gray-400"
                                :class="{ 'border-red-500 focus:ring-red-500': form.errors.name }"
                            />
                            <InputError :message="form.errors.name" class="mt-1" />
                        </div>

                        <!-- Password Field -->
                        <div>
                            <input
                                id="password"
                                type="password"
                                placeholder="Password"
                                required
                                autocomplete="current-password"
                                v-model="form.password"
                                class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#2c4a6f] focus:border-transparent outline-none transition-all duration-200 text-gray-900 placeholder-gray-400"
                                :class="{ 'border-red-500 focus:ring-red-500': form.errors.password }"
                            />
                            <InputError :message="form.errors.password" class="mt-1" />
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="flex items-center justify-between">
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input
                                    type="checkbox"
                                    v-model="form.remember"
                                    class="w-4 h-4 text-[#2c4a6f] border-gray-300 rounded focus:ring-[#2c4a6f] focus:ring-2"
                                />
                                <span class="text-sm text-gray-600">Remember me</span>
                            </label>
                            <a
                                v-if="canResetPassword"
                                :href="route('password.request')"
                                class="text-sm text-[#2c4a6f] hover:text-[#3d5a7f] transition-colors duration-200"
                            >
                                Forgot password?
                            </a>
                        </div>

                        <!-- Login Button -->
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full bg-gradient-to-r from-[#2c4a6f] to-[#3d5a7f] text-white font-semibold py-3.5 px-4 rounded-md hover:from-[#3d5a7f] hover:to-[#2c4a6f] focus:outline-none focus:ring-2 focus:ring-[#2c4a6f] focus:ring-offset-2 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center space-x-2"
                        >
                            <LoaderCircle v-if="form.processing" class="h-5 w-5 animate-spin" />
                            <span class="text-lg tracking-wide">{{ form.processing ? 'Logging in...' : 'Login' }}</span>
                        </button>
                    </form>

                    <!-- Additional Links -->
                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-600">
                            Don't have an account?
                            <a
                                :href="route('register')"
                                class="text-[#2c4a6f] hover:text-[#3d5a7f] font-medium transition-colors duration-200"
                            >
                                Sign up
                            </a>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-8 text-center">
                <p class="text-sm text-gray-500">
                    &copy; {{ new Date().getFullYear() }} Pharmacovigilance System. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</template>

<style scoped>
input:-webkit-autofill,
input:-webkit-autofill:hover,
input:-webkit-autofill:focus {
    -webkit-box-shadow: 0 0 0px 1000px white inset;
    -webkit-text-fill-color: #111827;
}
</style>
