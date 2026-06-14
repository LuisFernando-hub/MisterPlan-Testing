<script setup>
defineProps({
    status: {
        type: String,
        default: 'pending',
    },
});

const styles = {
    pending: 'bg-amber-50 text-amber-800 ring-amber-200',
    confirmed: 'bg-teal-50 text-teal-800 ring-teal-200',
    cancelled: 'bg-red-50 text-red-800 ring-red-200',
};
</script>

<template>
    <span class="inline-flex rounded-md px-2 py-1 text-xs font-semibold ring-1 ring-inset" :class="styles[status] || styles.pending">
        {{ status }}
    </span>
</template>
