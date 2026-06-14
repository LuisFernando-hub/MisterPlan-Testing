<script setup>
import { reactive } from 'vue';
import { useRouter } from 'vue-router';
import { storeToRefs } from 'pinia';
import { useReservationsStore } from '../stores/reservations';
import { useToastStore } from '../stores/toast';

const router = useRouter();
const reservationsStore = useReservationsStore();
const toastStore = useToastStore();
const { saving } = storeToRefs(reservationsStore);

const form = reactive({
    guest_name: '',
    guest_email: '',
    property_name: '',
    check_in: '',
    check_out: '',
    status: 'pending',
    amount: '',
    notes: '',
});

const toDatabaseDate = (value) => {
    if (!value) {
        return '';
    }

    return value.replace('T', ' ') + ':00';
};

const submit = async () => {
    try {
        const created = await reservationsStore.createReservation({
            ...form,
            check_in: toDatabaseDate(form.check_in),
            check_out: toDatabaseDate(form.check_out),
            amount: Number(form.amount),
        });

        toastStore.success('Reservation successfully created.');
        router.push(created?.id ? `/reservations/${created.id}` : '/reservations');
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
            <h2 class="mt-2 text-3xl font-semibold text-zinc-950">Create reservation</h2>
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
