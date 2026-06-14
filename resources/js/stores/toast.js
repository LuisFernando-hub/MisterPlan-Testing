import { defineStore } from 'pinia';

export const useToastStore = defineStore('toast', {
    state: () => ({
        messages: [],
    }),
    actions: {
        push(message, type = 'success') {
            const toast = {
                id: Date.now() + Math.random(),
                message,
                type,
            };

            this.messages.push(toast);
            window.setTimeout(() => this.remove(toast.id), 4500);
        },
        success(message) {
            this.push(message, 'success');
        },
        error(message) {
            this.push(message, 'error');
        },
        remove(id) {
            this.messages = this.messages.filter((message) => message.id !== id);
        },
    },
});
