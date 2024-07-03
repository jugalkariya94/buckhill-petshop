import './bootstrap';
import vuetify from "@/vuetify.ts";
import { createApp } from 'vue'

import App from '@/layout/App.vue'
import router from "@/router";
import pinia from "@/store";


createApp(App)
    .use(vuetify)
    .use(router)
    .use(pinia)
    .mount("#app");
