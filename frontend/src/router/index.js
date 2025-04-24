import { createWebHistory, createRouter } from "vue-router";
import axios from 'axios';
import routes from './routes';
import appConfig from "../../app.config";

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition;
    } else {
      return { top: 0, left: 0 };
    }
  },
});

// Disable all authentication checks
router.beforeEach((routeTo, routeFrom, next) => {
  next();
});

export default router;
