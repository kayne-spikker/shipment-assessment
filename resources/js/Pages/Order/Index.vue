<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
  orders: {
    type: Array,
    default: () => [],
  },
});
</script>

<template>
  <Head title="Manage Orders" />

  <AuthenticatedLayout>
    <template #header>
      <h2
          class="text-xl font-semibold leading-tight text-gray-800"
      >
        Manage Orders
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <table class="table-auto w-full">
              <thead>
              <tr>
                <th class="border px-4 py-2">Number</th>
                <th class="border px-4 py-2">Delivery housenumber</th>
                <th class="border px-4 py-2">Delivery zipcode</th>
                <th class="border px-4 py-2">Delivery city</th>
                <th class="border px-4 py-2">Action</th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="order in orders" :key="order.id">
                <td class="border px-4 py-2">{{ order.number }}</td>
                <td class="border px-4 py-2">{{ order.delivery_address.housenumber }}</td>
                <td class="border px-4 py-2">{{ order.delivery_address.zipcode }}</td>
                <td class="border px-4 py-2">{{ order.delivery_address.city }}</td>
                <td class="border px-4 py-2">
                  <Link :href="`orders/${order.id}`"><button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full">Show</button></Link>
                </td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
