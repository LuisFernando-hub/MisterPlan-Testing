<script setup>
import { computed, onMounted, reactive } from 'vue';
import { storeToRefs } from 'pinia';
import { useReservationsStore } from '../stores/reservations';
import { useToastStore } from '../stores/toast';
import LoadingBlock from '../components/LoadingBlock.vue';
import StatusBadge from '../components/StatusBadge.vue';

const reservationsStore = useReservationsStore();
const toastStore = useToastStore();
const { items, filters, loading, pagination, summary } = storeToRefs(reservationsStore);

const localFilters = reactive({ ...filters.value });

const currencyFormatter = new Intl.NumberFormat('pt-BR', {
    style: 'currency',
    currency: 'USD',
});

const rangeText = computed(() => {
    if (!pagination.value.total) {
        return 'Nenhum resultado';
    }

    return `${pagination.value.from}-${pagination.value.to} de ${pagination.value.total}`;
});

const formatDate = (value) => {
    if (!value) {
        return '-';
    }

    return new Intl.DateTimeFormat('pt-BR', {
        dateStyle: 'medium',
        timeStyle: 'short',
    }).format(new Date(value));
};

const loadReservations = async (page = 1) => {
    try {
        await reservationsStore.fetchReservations(page);
    } catch (error) {
        toastStore.error(error.message);
    }
};

const applyFilters = () => {
    reservationsStore.setFilters(localFilters);
    loadReservations(1);
};

const clearFilters = () => {
    reservationsStore.resetFilters();
    Object.assign(localFilters, filters.value);
    loadReservations(1);
};

onMounted(() => loadReservations());
</script>

<template>
    <section class="space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-medium text-teal-700">Management</p>
                <h2 class="text-3xl font-semibold text-zinc-950">Reservation list</h2>
            </div>
            <RouterLink to="/reservations/create" class="w-fit rounded-md bg-zinc-950 px-4 py-2 text-sm font-semibold text-white hover:bg-zinc-800">
                Create reservation
            </RouterLink>
        </div>

        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-lg border border-zinc-200 bg-white p-4">
                <p class="text-sm text-zinc-500">Total listed</p>
                <p class="mt-2 text-2xl font-semibold">{{ summary.total }}</p>
            </div>
            <div class="rounded-lg border border-zinc-200 bg-white p-4">
                <p class="text-sm text-zinc-500">Pendents</p>
                <p class="mt-2 text-2xl font-semibold text-amber-700">{{ summary.pending }}</p>
            </div>
            <div class="rounded-lg border border-zinc-200 bg-white p-4">
                <p class="text-sm text-zinc-500">Confirmed</p>
                <p class="mt-2 text-2xl font-semibold text-teal-700">{{ summary.confirmed }}</p>
            </div>
            <div class="rounded-lg border border-zinc-200 bg-white p-4">
                <p class="text-sm text-zinc-500">Canceled</p>
                <p class="mt-2 text-2xl font-semibold text-red-700">{{ summary.cancelled }}</p>
            </div>
        </div>

        <form class="rounded-lg border border-zinc-200 bg-white p-4" @submit.prevent="applyFilters">
            <div class="grid gap-4 md:grid-cols-5">
                <label class="md:col-span-2">
                    <span class="text-sm font-medium text-zinc-700">Search</span>
                    <input
                        v-model="localFilters.search"
                        type="search"
                        class="mt-1 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm outline-none focus:border-teal-700 focus:ring-2 focus:ring-teal-100"
                        placeholder="Nome, email ou propriedade"
                    >
                </label>

                <label>
                    <span class="text-sm font-medium text-zinc-700">Status</span>
                    <select
                        v-model="localFilters.status"
                        class="mt-1 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm outline-none focus:border-teal-700 focus:ring-2 focus:ring-teal-100"
                    >
                        <option value="">All</option>
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </label>

                <label>
                    <span class="text-sm font-medium text-zinc-700">Check-in</span>
                    <input
                        v-model="localFilters.check_in"
                        type="date"
                        class="mt-1 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm outline-none focus:border-teal-700 focus:ring-2 focus:ring-teal-100"
                    >
                </label>

                <label>
                    <span class="text-sm font-medium text-zinc-700">Check-out</span>
                    <input
                        v-model="localFilters.check_out"
                        type="date"
                        class="mt-1 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm outline-none focus:border-teal-700 focus:ring-2 focus:ring-teal-100"
                    >
                </label>
            </div>

            <div class="mt-4 flex flex-wrap gap-2">
                <button type="submit" class="rounded-md bg-teal-700 px-4 py-2 text-sm font-semibold text-white hover:bg-teal-800 disabled:cursor-not-allowed disabled:opacity-70" :disabled="loading">
                    {{ loading ? 'Filtering...' : 'Apply filters' }}
                </button>
                <button type="button" class="rounded-md border border-zinc-300 px-4 py-2 text-sm font-semibold text-zinc-700 hover:bg-zinc-100" @click="clearFilters">
                    Clean Filter
                </button>
            </div>
        </form>

        <LoadingBlock v-if="loading" />

        <div v-else class="overflow-hidden rounded-lg border border-zinc-200 bg-white">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-zinc-200">
                    <thead class="bg-zinc-100">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-normal text-zinc-600">Guest</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-normal text-zinc-600">Property</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-normal text-zinc-600">Period</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-normal text-zinc-600">Price</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-normal text-zinc-600">Status</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-normal text-zinc-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        <tr v-if="!items.length">
                            <td colspan="6" class="px-4 py-10 text-center text-sm text-zinc-500">
                                No reservations found.
                            </td>
                        </tr>
                        <tr v-for="reservation in items" :key="reservation.id" class="hover:bg-zinc-50">
                            <td class="whitespace-nowrap px-4 py-4">
                                <p class="font-medium text-zinc-950">{{ reservation.guest_name }}</p>
                                <p class="text-sm text-zinc-500">{{ reservation.guest_email }}</p>
                            </td>
                            <td class="whitespace-nowrap px-4 py-4 text-sm text-zinc-700">{{ reservation.property_name }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-700">
                                <p>{{ formatDate(reservation.check_in) }}</p>
                                <p class="text-zinc-500">{{ formatDate(reservation.check_out) }}</p>
                            </td>
                            <td class="whitespace-nowrap px-4 py-4 text-sm font-medium">{{ currencyFormatter.format(Number(reservation.amount || 0)) }}</td>
                            <td class="whitespace-nowrap px-4 py-4"><StatusBadge :status="reservation.status" /></td>
                            <td class="whitespace-nowrap px-4 py-4 text-right">
                                <RouterLink :to="`/reservations/${reservation.id}`" class="rounded-md border border-zinc-300 px-3 py-2 text-sm font-semibold text-zinc-700 hover:bg-zinc-100">
                                    Details
                                </RouterLink>
                                <RouterLink :to="`/reservations/update/${reservation.id}`" class="rounded-md border border-zinc-300 px-3 py-2 text-sm font-semibold text-zinc-700 hover:bg-zinc-100">
                                    Update
                                </RouterLink>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex flex-col gap-3 border-t border-zinc-200 px-4 py-3 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm text-zinc-600">{{ rangeText }}</p>
                <div class="flex gap-2">
                    <button
                        type="button"
                        class="rounded-md border border-zinc-300 px-3 py-2 text-sm font-semibold text-zinc-700 hover:bg-zinc-100 disabled:cursor-not-allowed disabled:opacity-50"
                        :disabled="pagination.current_page <= 1"
                        @click="loadReservations(pagination.current_page - 1)"
                    >
                        Previous
                    </button>
                    <button
                        type="button"
                        class="rounded-md border border-zinc-300 px-3 py-2 text-sm font-semibold text-zinc-700 hover:bg-zinc-100 disabled:cursor-not-allowed disabled:opacity-50"
                        :disabled="pagination.current_page >= pagination.last_page"
                        @click="loadReservations(pagination.current_page + 1)"
                    >
                        Next
                    </button>
                </div>
            </div>
        </div>
    </section>
</template>
