import { createRouter, createWebHistory } from "vue-router";
import Home from "@/views/Home.vue";
import Users from "@/views/Users.vue";
import Books from "@/views/Books.vue";
import Loans from "@/views/Loans.vue";
import Reports from "@/views/Reports.vue";

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: "/",
      name: "home",
      component: Home,
    },
    {
      path: "/users",
      name: "users",
      component: Users,
    },
    {
      path: "/books",
      name: "books",
      component: Books,
    },
    {
      path: "/loans",
      name: "loans",
      component: Loans,
    },
    {
      path: "/reports",
      name: "reports",
      component: Reports,
    },
  ],
});

export default router;
