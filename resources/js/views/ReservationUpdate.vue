<script setup>
import { onMounted, reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import { storeToRefs } from 'pinia';
import { useReservationsStore } from '../stores/reservations';
import { useToastStore } from '../stores/toast';

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
const { saving } = storeToRefs(reservationsStore);

const form = reactive({
    guest_name: '',
    guest_email: '',
    property_name: '',
    check_in: '',
    check_out: '',
    status: '',
    amount: '',
    notes: '',
});


const toDatabaseDate = (value) => {
    if (!value) {
        return '';
    }

    return value.replace('T', ' ') + ':00';
};


onMounted(async () => {
    try {
        const reservation = await reservationsStore.fetchReservationById(props.id);

        console.log(reservation.amount);
        form.guest_name = reservation.guest_name
        form.guest_email = reservation.guest_email
        form.property_name = reservation.property_name
        form.check_in = reservation.check_in ? reservation.check_in.slice(0, 16) : ''
        form.check_out = reservation.check_out ? reservation.check_out.slice(0, 16) : ''
        form.status = reservation.status
        form.amount = reservation.amount
        form.notes = reservation.notes
    } catch (error) {
        toastStore.error(error.message);
        router.push('/reservations');
    }
});

const submit = async () => {
    try {
        const updated = await reservationsStore.updatedReservation(props.id, {
            ...form,
            check_in: toDatabaseDate(form.check_in),
            check_out: toDatabaseDate(form.check_out),
            amount: Number(form.amount),
        });

        toastStore.success('Reservation successfully updated.');
        router.push(updated?.id ? `/reservations/${updated.id}` : '/reservations');
    } catch (error) {
        toastStore.error(error.message);
    }
};
</script>

<template>
    <section class="space-y-6">
        <div>
            <RouterLink to="/reservations" class="text-sm font-semibold text-teal-700 hover:text-teal-900">
                Back to list
            </RouterLink>
            <h2 class="mt-2 text-3xl font-semibold text-zinc-950">Update reservation</h2>
        </div>

        <form class="rounded-lg border border-zinc-200 bg-white p-6" @submit.prevent="submit">
            <div class="grid gap-5 md:grid-cols-2">
                <label>
                    <span class="text-sm font-medium text-zinc-700">Guest's name</span>
                    <input
                        v-model="form.guest_name"
                        required
                        maxlength="100"
                        type="text"
                        class="mt-1 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm outline-none focus:border-teal-700 focus:ring-2 focus:ring-teal-100"
                    >
                </label>

                <label>
                    <span class="text-sm font-medium text-zinc-700">Email</span>
                    <input
                        v-model="form.guest_email"
                        required
                        type="email"
                        class="mt-1 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm outline-none focus:border-teal-700 focus:ring-2 focus:ring-teal-100"
                    >
                </label>

                <label>
                    <span class="text-sm font-medium text-zinc-700">Property</span>
                    <input
                        v-model="form.property_name"
                        required
                        maxlength="100"
                        type="text"
                        class="mt-1 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm outline-none focus:border-teal-700 focus:ring-2 focus:ring-teal-100"
                    >
                </label>

                <label>
                    <span class="text-sm font-medium text-zinc-700">Status</span>
                    <select
                        v-model="form.status"
                        class="mt-1 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm outline-none focus:border-teal-700 focus:ring-2 focus:ring-teal-100"
                    >
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </label>

                <label>
                    <span class="text-sm font-medium text-zinc-700">Check-in</span>
                    <input
                        v-model="form.check_in"
                        required
                        type="datetime-local"
                        class="mt-1 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm outline-none focus:border-teal-700 focus:ring-2 focus:ring-teal-100"
                    >
                </label>

                <label>
                    <span class="text-sm font-medium text-zinc-700">Check-out</span>
                    <input
                        v-model="form.check_out"
                        required
                        type="datetime-local"
                        class="mt-1 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm outline-none focus:border-teal-700 focus:ring-2 focus:ring-teal-100"
                    >
                </label>

                <label>
                    <span class="text-sm font-medium text-zinc-700">Price</span>
                    <input
                        v-model="form.amount"
                        required
                        min="0"
                        step="any"
                        type="number"
                        class="mt-1 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm outline-none focus:border-teal-700 focus:ring-2 focus:ring-teal-100"
                    >
                </label>

                <label class="md:col-span-2">
                    <span class="text-sm font-medium text-zinc-700">Notes</span>
                    <textarea
                        v-model="form.notes"
                        maxlength="500"
                        rows="4"
                        class="mt-1 w-full rounded-md border border-zinc-300 px-3 py-2 text-sm outline-none focus:border-teal-700 focus:ring-2 focus:ring-teal-100"
                    ></textarea>
                </label>
            </div>

            <div class="mt-6 flex flex-wrap gap-2">
                <button
                    type="submit"
                    class="rounded-md bg-teal-700 px-4 py-2 text-sm font-semibold text-white hover:bg-teal-800 disabled:cursor-not-allowed disabled:opacity-70"
                    :disabled="saving"
                >
                    {{ saving ? 'Saving...' : 'Save reservation' }}
                </button>
                <RouterLink to="/reservations" class="rounded-md border border-zinc-300 px-4 py-2 text-sm font-semibold text-zinc-700 hover:bg-zinc-100">
                    Cancel
                </RouterLink>
            </div>
        </form>
    </section>
</template>
