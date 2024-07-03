// import './bootstrap';
import vuetify from "@/vuetify.ts";
import { createApp } from 'vue'

import App from '@/layout/App.vue'
import router from "@/router";


createApp(App).use(vuetify).use(router).mount("#app");
