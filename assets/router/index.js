import { createWebHistory, createRouter } from "vue-router";
import routes from './routes';
import axios from "axios";

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    return savedPosition || { top: 0, left: 0 };
  },
});
async function checkAuth() {
  try {
    const response = await axios.get('user/api/auth/status', {
      withCredentials: true, // Ensures cookies (sessions) are sent
    });
    return response.data.authenticated;
  } catch (error) {
    return false;
  }
}

router.beforeEach(async (to, from, next) => {
  if (to.meta.requiresAuth) {
    const isAuthenticated = await checkAuth();
    if (!isAuthenticated) {
      // 🔁 Full page reload
      return window.location.href = '/user/login';
    }
  }
  next();
});

export default router;
