import {
    createRouter,
    createWebHistory,
    type Router,
    type RouteRecordRaw,
} from 'vue-router';

// Components
import HomeView from '@/views/Home.vue';


/** Router Rules */
const routes: RouteRecordRaw[] = [
    {
        path: '/',
        name: 'Home',
        component: HomeView,
    }
];

/** Vue Router */
const router: Router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL), // createWebHashHistory(import.meta.env.BASE_URL)
    routes,
});


export default router;
