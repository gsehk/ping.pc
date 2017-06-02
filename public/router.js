import Vue from 'vue';
import VueRouter from 'vue-router';


// components.
import List from './component/pc/List.vue';

Vue.use(VueRouter);

const router = new VueRouter({
  mode: 'hash',
  routes: [
    // root.
    {
      path: '/',
      redirect: 'list'
    },
    // Setting router.
    {
      path: '/list',
      component: List
    }]
});

export default router;
