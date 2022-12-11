import Vue from 'vue'
import Vuex from 'vuex';
import VueRouter from 'vue-router';
import App from './App.vue';
import 'bootstrap/dist/css/bootstrap.css';
import 'font-awesome/css/font-awesome.css';
import lunch from './store/modules/lunch';
import router from './router/index';

Vue.config.productionTip = false;

Vue.use(Vuex);
Vue.use(VueRouter);

const store = new Vuex.Store({
  modules: {
    lunch,
  }
});

new Vue({
  store,
  router,
  render: h => h(App),
}).$mount('#app');