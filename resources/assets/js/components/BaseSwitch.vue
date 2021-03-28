<template>
  <label class="custom-toggle" :class="switchClass">
    <input type="checkbox" v-model="model">
    <span class="custom-toggle-slider rounded-circle"
          :data-label-off="offText"
          :data-label-on="onText">
    </span>
  </label>
</template>
<script>
  export default {
    name: 'base-switch',
    props: {
      value: [Array, Boolean],
      type: String,
      onText: {
        type: String,
        default: 'Yes'
      },
      offText: {
        type: String,
        default: 'No'
      }
    },
    computed: {
      switchClass() {
        let baseClass = 'custom-toggle-';
        if (this.type) {
          return baseClass + this.type
        }
        return ''
      },
      model: {
        get() {
          return this.value;
        },
        set(value) {
          this.$emit('input', value);
        }
      }
    },
    methods: {
      triggerToggle() {
        this.model = !this.model;
      }
    }
  };
</script>
<style></style>
