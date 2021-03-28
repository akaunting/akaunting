<template>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb"
        :class="[{[`bg-${type}`]: type}, listClasses]">
      <slot></slot>
    </ol>
  </nav>
</template>
<script>
export default {
  name: 'breadcrumb',
  props: {
    type: {
      type: String,
      default: '',
      description: 'Breadcrumb background type'
    },
    listClasses: {
      type: [String, Object],
      default: '',
      description: 'Breadcrumb list classes'
    }
  }
};
</script>
<style></style>
