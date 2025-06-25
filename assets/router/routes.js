// assets/router/routes.js
const routes = [
  { path: '/', component: () => import('@/views/index.vue'), meta: { requiresAuth: true }, },
  { path: '/profile', component: () => import('@/views/User/profile.vue') },
  { path: '/personalityTest', component: () => import('@/views/Test/testPage.vue') },
  {
    path: '/personalityTest/result',
    component: () => import('@/views/Test/TestResults.vue'),
  },
  { path: '/MonPlan', component: () => import('@/views/User/MonPlan.vue') },

]

export default routes;
