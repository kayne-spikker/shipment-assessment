<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import {ref, watchEffect} from 'vue';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";

const props = defineProps({
  order: {
    type: Object,
    default: () => [],
  },
  Error: {
    type: String,
  }
});

const confirmingShipmentCreation = ref(false);
const orderId = ref(null);

watchEffect(() => {
  orderId.value = props.order.id;
});

const form = useForm({
  orderId: orderId,
});

const confirmShipmentCreation = () => {
  confirmingShipmentCreation.value = true;
};

const createShipment = async () => {
  form.post(route('orders.shipment.create', {order: orderId.value}), {
    preserveScroll: true,
    onSuccess: () => closeModal(),
    onFinish: () => form.reset(),
  });
};

const closeModal = () => {
  confirmingShipmentCreation.value = false;

  form.clearErrors();
  form.reset();
};

const downloadPDF = async () => {
  // Construct the URL for downloading the PDF
  const url = route('shipment.download', { order: orderId.value });

  try {
    // Use `window.open` to trigger the download
    window.open(url, '_blank');
  } catch (error) {
    console.error('Error downloading PDF:', error);
  }
};
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

    <div class="py-12 mx-auto max-w-7xl sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 gap-6">
        <div
            class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
        >
          <div class="p-6 text-gray-900">
            <PrimaryButton @click="confirmShipmentCreation" class="w-full flex items-center justify-center">Create shipment</PrimaryButton>
          </div>

          <Modal :show="confirmingShipmentCreation" @close="closeModal">
            <div class="p-6">
              <h2
                  class="text-lg font-medium text-gray-900"
              >
                Are you sure you want to create the shipment?
              </h2>

              <p class="mt-1 text-sm text-gray-600">
                Once the shipment is created, stuff will be happening woo!
              </p>

              <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeModal">
                  Cancel
                </SecondaryButton>

                <DangerButton
                    class="ms-3"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="createShipment"
                >
                  Create Shipment
                </DangerButton>
              </div>
            </div>
          </Modal>
        </div>
      </div>

      <div class="grid grid-cols-1 gap-6 mt-6">
        <div
            class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
        >
          <div class="p-6 text-gray-900">
            <!-- If a shipment exists, show the shipment details -->
            <div v-if="order.shipment">
              <h3 class="mb-3">Shipment Details:</h3>
              <p><strong>Tracking URL:</strong> <a :href="order.shipment.tracking_url" target="_blank" class="text-yellow-500 hover:text-yellow-600 underline">Track Shipment</a></p>
              <div v-if="order.shipment.complete_label" class="mt-6 text-gray-900">
                <SecondaryButton @click="downloadPDF" class="flex items-center justify-center text-yellow-500 hover:text-yellow-600">Download Ship-Slip</SecondaryButton>
              </div>
              <div v-else class="mt-6 text-gray-900">
                Ship-Slip queued to generate. Come back later.
              </div>
            </div>
            <!-- If no shipment exists, show a message -->
            <div v-else>
              <p class="w-full flex items-center justify-center">No shipment has been created</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
