<template>
  <div
    class="choice"
    :class="{ active: checked }"
    data-toggle="wizard-checkbox"
    @click="updateValue"
  >
    <input
      type="checkbox"
      :name="name"
      :disabled="disabled"
      :checked="checked"
    />
    <div class="icon">
      <slot name="icon"> <i :class="icon"></i> </slot>
    </div>
    <slot name="title">
      <h6>{{ title }}</h6>
    </slot>
  </div>
</template>
<script>
export default {
  name: 'icon-checkbox',
  model: {
    prop: 'checked'
  },
  props: {
    checked: {
      type: Boolean,
      default: false
    },
    name: String,
    title: String,
    icon: String,
    disabled: Boolean
  },
  methods: {
    updateValue() {
      this.$emit('input', !this.checked);
    }
  }
};
</script>
<style></style>
