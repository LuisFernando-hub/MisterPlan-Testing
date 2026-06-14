<script setup>
import { useToastStore } from '../stores/toast';

const toastStore = useToastStore();
</script>

<template>
    <div class="fixed right-4 top-4 z-50 flex w-[min(24rem,calc(100vw-2rem))] flex-col gap-3">
        <div
            v-for="toast in toastStore.messages"
            :key="toast.id"
            class="rounded-lg border bg-white px-4 py-3 text-sm shadow-lg"
            :class="toast.type === 'error' ? 'border-red-200 text-red-800' : 'border-teal-200 text-teal-900'"
            role="status"
        >
            <div class="flex items-start justify-between gap-3">
                <p>{{ toast.message }}</p>
                <button
                    type="button"
                    class="rounded p-1 text-zinc-500 hover:bg-zinc-100"
                    aria-label="Fechar notificacao"
                    @click="toastStore.remove(toast.id)"
                >
                    x
                </button>
            </div>
        </div>
    </div>
</template>
