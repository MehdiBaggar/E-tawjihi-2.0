import { createApp } from 'vue';
import App from './App.vue';
import router from "./router";
import AOS from 'aos';
import 'aos/dist/aos.css';
import i18n from './i18n';

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

import BootstrapVueNext from 'bootstrap-vue-next';
import vClickOutside from "click-outside-vue3";
import VueApexCharts from "vue3-apexcharts";
import { vMaska } from "maska";

import VueFeather from 'vue-feather';


import '@/styles/scss/config/interactive/app.scss';
import '@/styles/scss/mermaid.min.css';
import 'bootstrap/dist/js/bootstrap.bundle';
import store from "./state/store";

// Init AOS animations
AOS.init({
    easing: 'ease-out-back',
    duration: 1000
});

// Crée l'application Vue sans `store`
createApp(App)
    .use(store)
    .use(router)
    .use(VueApexCharts)
    .use(BootstrapVueNext)
    .use(VueSweetalert2)
    .use(vClickOutside).mount('#app');
window.process = {
    env: {
        NODE_ENV: 'development' // ou 'production' selon ton cas
    }
};