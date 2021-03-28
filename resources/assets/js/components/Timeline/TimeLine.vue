<template>
  <div class="timeline" :class="{[`timeline-${type}`]: type}">
    <slot></slot>
  </div>
</template>
<script>
  export default {
    name: 'time-line',
    props: {
      type: {
        type: String,
        default: ''
      }
    }
  };
</script>
<style></style>
