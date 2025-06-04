// assets/router/routes.js
const routes = [
  { path: '/', component: () => import('@/views/index.vue'), meta: { requiresAuth: true }, },
  { path: '/profile', component: () => import('@/views/profile.vue'), meta: { requiresAuth: true }, },


]

export default routes;
