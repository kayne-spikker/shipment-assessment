<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import PrimaryButton from "@/Components/PrimaryButton.vue";
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Dashboard
            </h2>
        </template>

        <div class="py-12 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900">
                      <Link :href="route('orders')" class="uppercase w-full flex items-center justify-center bg-blue-500 text-white py-2 px-4 rounded">
                        Orders
                      </Link></div>
                </div>

                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900">
                      <PrimaryButton class="w-full flex items-center justify-center">Addresses</PrimaryButton>
                    </div>
                </div>

                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                  <div class="p-6 text-gray-900">
                    <PrimaryButton class="w-full flex items-center justify-center">Order Lines</PrimaryButton>
                  </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
