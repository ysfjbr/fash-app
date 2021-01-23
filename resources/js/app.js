/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

import VueRouter from 'vue-router'

Vue.use(VueRouter)

/**
 * Registering of Public Components
 */
Vue.component('app', require('./components/App.vue').default);              // Main SPA
Vue.component('navbar', require('./components/Navbar.vue').default);        // Main Application Navbar
Vue.component('show-items', require('./components/ShowsItems.vue').default);   // Group of TV-Show Items
Vue.component('show-item', require('./components/ShowItem.vue').default);   // One TV-Show Item

/**
 * Pages to routes
 */
import Home from './Pages/HomePage.vue'
import Show from './Pages/ShowPage.vue'


/**
 * Define Routers
 */
const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'home',
            component: Home
        },
        {
            path: '/show',
            name: 'show',
            component: Show,
            meta:{
                title:"- Login"
            }
        }
    ]
});

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

//Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router
});
