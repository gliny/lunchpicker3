import Vue from 'vue'
import VueRouter from 'vue-router'
import HomeView from '../views/HomeView.vue'


Vue.use(VueRouter)

const router = new VueRouter({
  mode: 'history',
  base: import.meta.env.BASE_URL,
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView
    },
    {
      path: '/about',
      name: 'about',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/AboutView.vue')
    },
    {
      name: "LunchList",
      path: "/lunches/",
      component: () => import("../components/lunch/List.vue"),
    },
    {
      name: "LunchCreate",
      path: "/lunches/create",
      component: () => import("../components/lunch/Create.vue"),
    },
    {
      name: "LunchUpdate",
      path: "/lunches/edit/:id",
      component: () => import("../components/lunch/Update.vue"),
    },
    {
      name: "LunchShow",
      path: "/lunches/show/:id",
      component: () => import("../components/lunch/Show.vue"),
    },
  ]
})

export default router
