<script setup>
import ToastStack from './components/ToastStack.vue';
</script>

<template>
    <div class="min-h-screen bg-stone-50 text-zinc-950">
        <header class="border-b border-zinc-200 bg-white">
            <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-5 sm:flex-row sm:items-center sm:justify-between lg:px-8">
                <RouterLink to="/reservations" class="w-fit">
                    <p class="text-xs font-semibold uppercase tracking-normal text-teal-700">Reservations</p>
                    <h1 class="text-2xl font-semibold text-zinc-950">Reservation panel</h1>
                </RouterLink>

                <nav class="flex flex-wrap gap-2">
                    <RouterLink
                        to="/reservations"
                        class="rounded-md px-3 py-2 text-sm font-medium hover:bg-zinc-100"
                        active-class="bg-zinc-950 text-white hover:bg-zinc-950"
                    >
                        List
                    </RouterLink>
                    <RouterLink
                        to="/reservations/create"
                        class="rounded-md px-3 py-2 text-sm font-semibold hover:bg-teal-700 hover:text-white"
                        active-class="bg-teal-700 text-white hover:bg-teal-700"
                    >
                        New booking
                    </RouterLink>
                </nav>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-4 py-8 lg:px-8">
            <RouterView />
        </main>

        <ToastStack />
    </div>
</template>
