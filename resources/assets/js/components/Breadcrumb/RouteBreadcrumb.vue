<template>
  <bread-crumb list-classes="breadcrumb-links breadcrumb-dark">
    <BreadCrumbItem>
      <li class="breadcrumb-item">
        <router-link to="/">
          <i class="fas fa-home"></i>
        </router-link>
      </li>
    </BreadCrumbItem>
    <BreadCrumbItem
      v-for="(route, index) in $route.matched.slice()"
      :key="route.name"
      :active="index === $route.matched.length - 1"
      style="display:inline-block"
    >
      <router-link
        :to="{ name: route.name }"
        v-if="index < $route.matched.length - 1"
      >
        {{ route.name }}
      </router-link>
      <span v-else>{{ route.name }}</span>
    </BreadCrumbItem>
  </bread-crumb>
</template>

<script>
  import BreadCrumb from './Breadcrumb';
  import BreadCrumbItem from './BreadcrumbItem';

  export default {
    name: 'route-breadcrumb',
    components: {
      BreadCrumb,
      BreadCrumbItem
    },
    methods: {
      getBreadName(route) {
        return route.name;
      }
    }
  };
</script>

<style scoped></style>
