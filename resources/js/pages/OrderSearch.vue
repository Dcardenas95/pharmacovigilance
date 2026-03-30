<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import Pagination from '@/components/Pagination.vue';
import Modal from '@/components/Modal.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Order Search',
        href: '/order-search',
    },
];

// Form data
const lotNumber = ref('');
const startDate = ref('');
const endDate = ref('');
const orders = ref<any[]>([]);
const loading = ref(false);
const selectedOrder = ref<any>(null);
const showAlertModal = ref(false);
const alertMessage = ref('');

// Modal states
const showOrderModal = ref(false);
const showBuyerModal = ref(false);
const orderDetails = ref<any>(null);
const buyerDetails = ref<any>(null);

// Pagination
const currentPage = ref(1);
const itemsPerPage = 10;

const paginatedOrders = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return orders.value.slice(start, end);
});

// Load orders on mount
onMounted(() => {
    searchOrders();
});

// Search orders
const searchOrders = async () => {
    loading.value = true;
    try {
        const params: any = {};
        if (lotNumber.value) params.lot = lotNumber.value;
        if (startDate.value) params.start_date = startDate.value;
        if (endDate.value) params.end_date = endDate.value;

        const response = await axios.get('/api/orders', { params });
        console.log(response.data);
        orders.value = response.data.data;
        currentPage.value = 1;
    } catch (error) {
        console.error('Error searching orders:', error);
        alert('Error searching orders');
    } finally {
        loading.value = false;
    }
};

// View order details
const viewOrder = async (orderId: number) => {
    try {
        const response = await axios.get(`/api/orders/${orderId}`);
        orderDetails.value = response.data.data;
        showOrderModal.value = true;
    } catch (error) {
        console.error('Error viewing order:', error);
        alert('Error loading order details');
    }
};

// View customer/buyer details
const viewBuyer = async (customerId: number) => {
    try {
        const response = await axios.get(`/api/customers/${customerId}`);
        buyerDetails.value = response.data.data;
        showBuyerModal.value = true;
    } catch (error) {
        console.error('Error viewing customer:', error);
        alert('Error loading customer details');
    }
};

// Show alert modal
const showAlert = (order: any) => {
    selectedOrder.value = order;
    alertMessage.value = `Warning: Important recall notice for medication Lot #${lotNumber.value}. Please contact the pharmacy immediately.`;
    showAlertModal.value = true;
};

// Send alert to customer
const sendAlert = async () => {
    if (!selectedOrder.value) return;

    try {
        const response = await axios.post(`/api/orders/${selectedOrder.value.id}/alert`, {
            message: alertMessage.value,
            lot_number: lotNumber.value
        });
        
        if (response.data.success) {
            alert('✅ Alert sent successfully!\n\nEmail delivered to: ' + selectedOrder.value.customer.email);
            showAlertModal.value = false;
        }
    } catch (error: any) {
        console.error('Error sending alert:', error);
        const errorMsg = error.response?.data?.message || 'Failed to send alert email';
        alert('❌ Error: ' + errorMsg);
    }
};

// Format date
const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('en-US', {
        month: '2-digit',
        day: '2-digit',
        year: 'numeric'
    });
};
</script>

<template>
    <Head title="Order Search" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6">
            <!-- Header -->
            <div class="rounded-lg bg-slate-700 p-4 text-white">
                <h1 class="text-xl font-semibold">Order Search</h1>
            </div>

            <!-- Search Form -->
            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">
                    Medication Search
                </h2>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Lot Number
                        </label>
                        <input
                            v-model="lotNumber"
                            type="text"
                            placeholder="951357"
                            class="w-full rounded-md border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                        />
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Start Date
                        </label>
                        <input
                            v-model="startDate"
                            type="date"
                            class="w-full rounded-md border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                        />
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            End Date
                        </label>
                        <input
                            v-model="endDate"
                            type="date"
                            class="w-full rounded-md border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                        />
                    </div>

                    <div class="flex items-end">
                        <button
                            @click="searchOrders"
                            :disabled="loading"
                            class="w-full rounded-md bg-teal-600 px-4 py-2 font-medium text-white transition hover:bg-teal-700 disabled:opacity-50"
                        >
                            {{ loading ? 'Searching...' : 'Search' }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Order Results -->
            <div class="rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="border-b border-gray-200 bg-gray-50 px-6 py-3 dark:border-gray-700 dark:bg-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Order Results
                    </h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-700 text-xs uppercase text-white">
                            <tr>
                                <th class="px-6 py-3">Order ID</th>
                                <th class="px-6 py-3">Customer</th>
                                <th class="px-6 py-3">Date</th>
                                <th class="px-6 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-if="orders.length === 0"
                                class="border-b dark:border-gray-700"
                            >
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                    {{ loading ? 'Loading...' : 'No orders found. Use the search form above.' }}
                                </td>
                            </tr>
                            <tr
                                v-for="order in paginatedOrders"
                                :key="order.id"
                                class="border-b bg-white hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700"
                            >
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                    {{ String(order.id).padStart(7, '0') }}
                                </td>
                                <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                    {{ order.customer.name }}
                                </td>
                                <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                    {{ formatDate(order.purchase_date) }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        <button
                                            @click="viewOrder(order.id)"
                                            class="rounded bg-blue-600 px-3 py-1 text-xs font-medium text-white hover:bg-blue-700"
                                        >
                                            📄 View Order
                                        </button>
                                        <button
                                            @click="showAlert(order)"
                                            class="rounded bg-amber-600 px-3 py-1 text-xs font-medium text-white hover:bg-amber-700"
                                        >
                                            ⚠️ Alert Buyer
                                        </button>
                                        <button
                                            @click="viewBuyer(order.customer.id)"
                                            class="rounded bg-slate-600 px-3 py-1 text-xs font-medium text-white hover:bg-slate-700"
                                        >
                                            👤 View Buyer
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <Pagination
                    :total-items="orders.length"
                    :items-per-page="itemsPerPage"
                    :current-page="currentPage"
                    @update:current-page="currentPage = $event"
                />
            </div>
        </div>

        <!-- Alert Modal -->
        <Modal
            :show="showAlertModal"
            title="Send Alert to Customer"
            @close="showAlertModal = false"
        >
            <div class="mb-4">
                <p class="mb-2 text-sm text-gray-600 dark:text-gray-400">
                    <strong>Customer:</strong> {{ selectedOrder?.customer.name }}
                </p>
                <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                    <strong>Email:</strong> {{ selectedOrder?.customer.email }}
                </p>

                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Message
                </label>
                <textarea
                    v-model="alertMessage"
                    rows="4"
                    class="w-full rounded-md border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                ></textarea>
            </div>

            <template #footer>
                <div class="flex justify-end gap-3">
                    <button
                        @click="showAlertModal = false"
                        class="rounded-md bg-gray-500 px-4 py-2 text-white transition hover:bg-gray-600"
                    >
                        Cancel
                    </button>
                    <button
                        @click="sendAlert"
                        class="rounded-md bg-teal-600 px-4 py-2 text-white transition hover:bg-teal-700"
                    >
                        Send Email
                    </button>
                </div>
            </template>
        </Modal>

        <!-- Order Details Modal -->
        <Modal
            :show="showOrderModal"
            title="Order Details"
            max-width="lg"
            @close="showOrderModal = false"
        >
            <div v-if="orderDetails" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Order ID</p>
                        <p class="text-base font-semibold text-gray-900 dark:text-white">
                            {{ String(orderDetails.id).padStart(7, '0') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Purchase Date</p>
                        <p class="text-base font-semibold text-gray-900 dark:text-white">
                            {{ formatDate(orderDetails.purchase_date) }}
                        </p>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-4 dark:border-gray-700">
                    <h4 class="mb-3 text-lg font-semibold text-gray-900 dark:text-white">Customer Information</h4>
                    <div class="space-y-2">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</p>
                            <p class="text-base text-gray-900 dark:text-white">{{ orderDetails.customer.name }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</p>
                            <p class="text-base text-gray-900 dark:text-white">{{ orderDetails.customer.email }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone</p>
                            <p class="text-base text-gray-900 dark:text-white">{{ orderDetails.customer.phone }}</p>
                        </div>
                    </div>
                </div>

                <div v-if="orderDetails.items && orderDetails.items.length > 0" class="border-t border-gray-200 pt-4 dark:border-gray-700">
                    <h4 class="mb-3 text-lg font-semibold text-gray-900 dark:text-white">Order Items</h4>
                    <div class="space-y-3">
                        <div
                            v-for="item in orderDetails.items"
                            :key="item.id"
                            class="rounded-lg bg-gray-50 p-3 dark:bg-gray-700"
                        >
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900 dark:text-white">
                                        {{ item.medication?.name || 'N/A' }}
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        Lot: {{ item.medication?.lot_number || 'N/A' }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        Qty: {{ item.quantity }}
                                    </p>
                                    <p class="font-medium text-gray-900 dark:text-white">
                                        ${{ item.price }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <template #footer>
                <div class="flex justify-end">
                    <button
                        @click="showOrderModal = false"
                        class="rounded-md bg-gray-500 px-4 py-2 text-white transition hover:bg-gray-600"
                    >
                        Close
                    </button>
                </div>
            </template>
        </Modal>

        <!-- Buyer Details Modal -->
        <Modal
            :show="showBuyerModal"
            title="Customer Details"
            max-width="md"
            @close="showBuyerModal = false"
        >
            <div v-if="buyerDetails" class="space-y-4">
                <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-700">
                    <div class="mb-3 flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-slate-600 text-xl text-white">
                            {{ buyerDetails.name.charAt(0).toUpperCase() }}
                        </div>
                        <div>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ buyerDetails.name }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Customer ID: {{ buyerDetails.id }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="space-y-3">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Email Address</p>
                        <p class="flex items-center gap-2 text-base text-gray-900 dark:text-white">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            {{ buyerDetails.email }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone Number</p>
                        <p class="flex items-center gap-2 text-base text-gray-900 dark:text-white">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            {{ buyerDetails.phone }}
                        </p>
                    </div>
                    <div v-if="buyerDetails.address">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Address</p>
                        <p class="flex items-start gap-2 text-base text-gray-900 dark:text-white">
                            <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>{{ buyerDetails.address }}</span>
                        </p>
                    </div>
                </div>
            </div>

            <template #footer>
                <div class="flex justify-end">
                    <button
                        @click="showBuyerModal = false"
                        class="rounded-md bg-gray-500 px-4 py-2 text-white transition hover:bg-gray-600"
                    >
                        Close
                    </button>
                </div>
            </template>
        </Modal>
    </AppLayout>
</template>
