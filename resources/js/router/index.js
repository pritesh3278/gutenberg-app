import { createRouter, createWebHistory } from 'vue-router';

const routes = [
    {
        path: '/',
        name: 'home',
        component: () => import('../components/Home.vue')
    },
    {
        path: '/books',
        name: 'books', 
        component: () => import('../components/Books.vue')
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;