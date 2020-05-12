/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('cutter-code', require('./components/books/CutterCode.vue').default);
Vue.component('calendar-availability', require('./components/calendar/CalendarAvailability.vue').default);
Vue.component('calendar-borrow', require('./components/calendar/CalendarBorrow.vue').default);
Vue.component('admin-calendar-availability', require('./components/calendar/AdminCalendarAvailability.vue').default);

// Vue.component('camera', require('./components/camera/WebCam.vue').default);


//Admin
Vue.component('admin-login', require('./components/admin/AdminLogin.vue').default);
Vue.component('admin-register', require('./components/admin/AdminRegister.vue').default);
Vue.component('admin-dashboard', require('./components/admin/AdminDashboard.vue').default);

const app = new Vue({
    el: '#app',
});
