<script>
import TestRenderer from "@/components/tests/TestRenderer.vue";
export default {
  name: "testPage",
  page: {
    title: "testPage",
  },
  components: {
    TestRenderer
  },
  data() {
    return {
      title: "Questionnaire",
    };
  },
};
</script>

<template>
  <TestRenderer testType="Personality" />
</template>

<style scoped>

</style>