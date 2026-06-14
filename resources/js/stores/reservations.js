import { defineStore } from 'pinia';
import { useApi } from '../composables/useApi';

const api = useApi();

const emptyPagination = () => ({
    current_page: 1,
    last_page: 1,
    per_page: 20,
    total: 0,
    from: 0,
    to: 0,
});

export const useReservationsStore = defineStore('reservations', {
    state: () => ({
        items: [],
        selected: null,
        loading: false,
        saving: false,
        error: null,
        pagination: emptyPagination(),
        filters: {
            search: '',
            status: '',
            check_in: '',
            check_out: '',
            per_page: 20,
        },
    }),
    getters: {
        summary(state) {
            return {
                total: state.pagination.total || state.items.length,
                pending: state.items.filter((item) => item.status === 'pending').length,
                confirmed: state.items.filter((item) => item.status === 'confirmed').length,
                cancelled: state.items.filter((item) => item.status === 'cancelled').length,
            };
        },
    },
    actions: {
        normalizePaginator(payload) {
            const paginator = payload.data || {};

            this.items = paginator.data || [];
            this.pagination = {
                current_page: paginator.current_page || 1,
                last_page: paginator.last_page || 1,
                per_page: Number(paginator.per_page || this.filters.per_page || 20),
                total: paginator.total || this.items.length,
                from: paginator.from || 0,
                to: paginator.to || this.items.length,
            };
        },
        async fetchReservations(page = 1) {
            this.loading = true;
            this.error = null;

            try {
                const payload = await api.get('/api/reservations', {
                    ...this.filters,
                    page,
                });

                this.normalizePaginator(payload);
            } catch (error) {
                this.error = error.message;
                throw error;
            } finally {
                this.loading = false;
            }
        },
        async fetchReservationById(id) {
            this.loading = true;
            this.error = null;

            try {
                // const cached = this.items.find((item) => String(item.id) === String(id));

                // if (cached) {
                //     this.selected = cached;
                //     return cached;
                // }


                const payload = await api.get('/api/reservations/'+id);

                const reservation = payload.data || null;

                if (!reservation) {
                    throw new Error('Reserva nao encontrada.');
                }

                return reservation;
            } catch (error) {
                this.error = error.message;
                throw error;
            } finally {
                this.loading = false;
            }
        },
        async createReservation(data) {
            this.saving = true;
            this.error = null;

            try {
                const payload = await api.post('/api/reservations/create', data);
                return payload.data;
            } catch (error) {
                this.error = error.message;
                throw error;
            } finally {
                this.saving = false;
            }
        },
        async updatedReservation(id, data) {
            this.saving = true;
            this.error = null;

            try {
                const payload = await api.put('/api/reservations/update/'+id, data);
                return payload.data;
            } catch (error) {
                this.error = error.message;
                throw error;
            } finally {
                this.saving = false;
            }
        },
        setFilters(filters) {
            this.filters = {
                ...this.filters,
                ...filters,
            };
        },
        resetFilters() {
            this.filters = {
                search: '',
                status: '',
                check_in: '',
                check_out: '',
                per_page: 20,
            };
        },
    },
});
