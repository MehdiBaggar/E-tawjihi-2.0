<script>
export default {
  components: {},
  props: {
    title: {
      type: String,
      default: "",
    },
    pageTitle: {
      type: String,
      default: "",
    },
    pageRoute: {
      type: String,
      default: "", // Add a route prop if you want to dynamically change the route
    },
  },
};
</script>

<template>
  <BRow>
    <BCol cols="12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0">{{ title }}</h4>
        <div class="page-title-right">
          <ol class="breadcrumb m-0">
            <li class="breadcrumb-item">
              <!-- Use router-link for pageTitle navigation -->
              <router-link :to="pageRoute || '#'" class="breadcrumb-item-link">{{ pageTitle }}</router-link>
            </li>
            <li class="breadcrumb-item active">{{ title }}</li>
          </ol>
        </div>
      </div>
    </BCol>
  </BRow>
</template>
