import Vue from "vue";
import VueRouter from "vue-router";
import App from "./App";
import Home from "./views/Home";
import Config from "./views/Config";

Vue.use(VueRouter);
Vue.config.productionTip = false;

const routes = [
  { path: "/", component: Home },
  { path: "/config", component: Config }
];

const router = new VueRouter({
  routes,
  mode: "history"
});

new Vue({
  router,
  render: (h) => h(App)
}).$mount("#app");
