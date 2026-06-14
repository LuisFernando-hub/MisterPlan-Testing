<script setup>
import { computed, onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';
import { storeToRefs } from 'pinia';
import { useReservationsStore } from '../stores/reservations';
import { useToastStore } from '../stores/toast';
import LoadingBlock from '../components/LoadingBlock.vue';
import StatusBadge from '../components/StatusBadge.vue';

const props = defineProps({
    id: {
        type: [String, Number],
        required: true,
    },
});

const router = useRouter();
const reservationsStore = useReservationsStore();
const reservation = ref();
const toastStore = useToastStore();
const { selected, loading } = storeToRefs(reservationsStore);

const currencyFormatter = new Intl.NumberFormat('pt-BR', {
    style: 'currency',
    currency: 'USD',
});

const formatDate = (value) => {
    if (!value) {
        return '-';
    }

    return new Intl.DateTimeFormat('pt-BR', {
        dateStyle: 'full',
        timeStyle: 'short',
    }).format(new Date(value));
};

const amount = computed(() => currencyFormatter.format(Number(reservation.value?.amount || 0)));

onMounted(async () => {
    try {
        reservation.value = await reservationsStore.fetchReservationById(props.id);
    } catch (error) {
        toastStore.error(error.message);
        router.push('/reservations');
    }
});
</script>

<template>
    <section class="space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <RouterLink to="/reservations" class="text-sm font-semibold text-teal-700 hover:text-teal-900">
                    Back to list
                </RouterLink>
                <h2 class="mt-2 text-3xl font-semibold text-zinc-950">Booking details</h2>
            </div>
            <RouterLink to="/reservations/create" class="w-fit rounded-md bg-zinc-950 px-4 py-2 text-sm font-semibold text-white hover:bg-zinc-800">
                New booking
            </RouterLink>
        </div>

        <LoadingBlock v-if="loading" />

        <div v-else-if="reservation" class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
            <article class="rounded-lg border border-zinc-200 bg-white p-6">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <p class="text-sm text-zinc-500">Guest</p>
                        <h3 class="mt-1 text-2xl font-semibold text-zinc-950">{{ reservation.guest_name }}</h3>
                        <p class="mt-1 text-sm text-zinc-600">{{ reservation.guest_email }}</p>
                    </div>
                    <StatusBadge :status="reservation.status" />
                </div>

                <dl class="mt-8 grid gap-5 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-zinc-500">Property</dt>
                        <dd class="mt-1 text-base font-semibold text-zinc-950">{{ reservation.property_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-zinc-500">Price</dt>
                        <dd class="mt-1 text-base font-semibold text-zinc-950">{{ amount }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-zinc-500">Check-in</dt>
                        <dd class="mt-1 text-base font-semibold text-zinc-950">{{ formatDate(reservation.check_in) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-zinc-500">Check-out</dt>
                        <dd class="mt-1 text-base font-semibold text-zinc-950">{{ formatDate(reservation.check_out) }}</dd>
                    </div>
                </dl>
            </article>

            <aside class="rounded-lg border border-zinc-200 bg-white p-6">
                <h3 class="text-lg font-semibold text-zinc-950">Notes</h3>
                <p class="mt-3 min-h-32 whitespace-pre-line rounded-md bg-zinc-50 p-4 text-sm leading-6 text-zinc-700">
                    {{ reservation.notes || 'No notes registered.' }}
                </p>
            </aside>

            <div class="grid gap-12 lg:col-span-2">
                <aside class="rounded-lg border border-zinc-200 bg-white p-6">
                    <h3 class="text-lg font-semibold text-zinc-950">Reservation Events</h3>
                    <p v-if="reservation.events" v-for="event in reservation.events" class="mt-3 min-h-2 whitespace-pre-line rounded-md bg-zinc-50 p-4 text-sm leading-6 text-zinc-700">
                       {{ event.type }} - {{ event.description }}
                    </p>
                    <p v-else class="mt-3 min-h-5 whitespace-pre-line rounded-md bg-zinc-50 p-4 text-sm leading-6 text-zinc-700">
                        {{ 'No events registered.' }}
                    </p>
                </aside>
            </div>
        </div>
    </section>
</template>
