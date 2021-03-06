window._ = require('lodash');

 import VueInstantSearch from 'vue-instantsearch';

// import Vue from 'vue';
// import App from './App.vue';
// import InstantSearch from 'vue-instantsearch';
//
// Vue.use(InstantSearch);

// new Vue({
//     el: '#app',
//     render: h => h(App),
// });

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {}

//
window.Vue = require('vue');

Vue.use(VueInstantSearch);
let authorizations = require('./authorizations');

window.Vue.prototype.authorize = function (...params){
    // let user = window.App.user;
    if(! window.App.signedIn) return false;

    if(typeof params[0] === 'string'){
      return authorizations[params[0]](params[1]);
    }

    return params[0](window.App.user);
    //return user ? handler(user) :false;

    window.Vue.prototype.signedIn = window.App.signedIn;

};

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

//
window.axios.defaults.headers.common = {
    'X-CSRF-TOKEN': window.App.csrfToken,
    'X-Requested-With': 'XMLHttpRequest'
};

window.events = new Vue();
window.flash = function (message, level = 'success') {
     window.events.$emit('flash', {message, level});
};
