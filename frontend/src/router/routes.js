

export default [
  

    {
        path: "/",
        name: "Dashboard",
        meta: {
            title: "Dashboard",
        },
        props: true,
        component: () => import("../views/Dashboard.vue"),
    },
    {
        path: "/contrat",
        name: "contrat",
        meta: {
            title: "contrat",
        },
        props: true,
        component: () => import("../views/contrat.vue"),
    },
    {
        path: "/Convocation",
        name: "Convocation",
        meta: {
            title: "Convocation",
        },
        props: true,
        component: () => import("../views/Convocation.vue"),
    },

];
