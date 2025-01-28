<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
  order: {
    type: Array,
    default: () => [],
  },
});
</script>

<template>
  <Head title="Show Order" />

  <AuthenticatedLayout>
    <template #header>
      <h2
          class="text-xl font-semibold leading-tight text-gray-800"
      >
        {{ order.number }}
      </h2>
    </template>

    
  </AuthenticatedLayout>
</template>
