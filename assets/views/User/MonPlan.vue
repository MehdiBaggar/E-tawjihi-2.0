<script>
import Layout from "@/layouts/main.vue";
import FeuilleDeRoute from "@/components/Student/FeuilleDeRoute.vue";

export default {
  name: "MonPlan",
  page: {
    title: "MonPlan",
  },
  components: {
    Layout,
    FeuilleDeRoute,
  },
}

</script>

<template>
  <Layout>
  <FeuilleDeRoute />
  </Layout>
</template>

<style scoped>

</style>