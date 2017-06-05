import Vue from 'vue';
import VueRouter from 'vue-router';

// components.
import AuthList from './component/pc/AuthList.vue';
import Report from './component/report/Report.vue';

Vue.use(VueRouter);

const router = new VueRouter({
  mode: 'hash',
  routes: [
    // root.
    {
      path: '/',
      redirect: 'authlist'
    },
    // Setting router.
    {
      path: '/authlist',
      component: AuthList
    },{
      path: '/report',
      component: Report
    }]
});

export default router;
