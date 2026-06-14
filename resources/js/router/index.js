import { createRouter, createWebHashHistory } from 'vue-router';
import ReservationList from '../views/ReservationList.vue';
import ReservationDetails from '../views/ReservationDetails.vue';
import ReservationCreate from '../views/ReservationCreate.vue';
import ReservationUpdate from '../views/ReservationUpdate.vue';

const routes = [
    {
        path: '/',
        redirect: '/reservations',
    },
    {
        path: '/reservations',
        name: 'reservations.index',
        component: ReservationList,
    },
    {
        path: '/reservations/create',
        name: 'reservations.create',
        component: ReservationCreate,
    },
    {
        path: '/reservations/update/:id',
        name: 'reservations.update',
        component: ReservationUpdate,
        props: true,
    },
    {
        path: '/reservations/:id',
        name: 'reservations.show',
        component: ReservationDetails,
        props: true,
    },
];

export default createRouter({
    history: createWebHashHistory(),
    routes,
});
