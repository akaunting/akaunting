<template>
  <component
    :is="tag"
    :type="tag === 'button' ? nativeType : ''"
    :disabled="disabled || loading"
    @click="handleClick"
    class="btn"
    :class="[
      { 'rounded-circle': round },
      { 'btn-block': block },
      { 'btn-wd': wide },
      { 'btn-icon btn-fab': icon },
      { [`btn-${type}`]: type && !outline },
      { [`btn-${size}`]: size },
      { [`btn-outline-${type}`]: outline && type },
      { 'btn-link': link },
      { disabled: disabled && tag !== 'button' }
    ]"
  >
    <slot name="loading">
      <i v-if="loading" class="fas fa-spinner fa-spin"></i>
    </slot>
    <slot></slot>
  </component>
</template>
<script>
export default {
  name: 'base-button',
  props: {
    tag: {
      type: String,
      default: 'button',
      description: 'Button html tag'
    },
    round: Boolean,
    icon: Boolean,
    block: Boolean,
    loading: Boolean,
    wide: Boolean,
    disabled: Boolean,
    type: {
      type: String,
      default: 'default',
      description: 'Button type (primary|secondary|danger etc)'
    },
    nativeType: {
      type: String,
      default: 'button',
      description: 'Button native type (e.g button, input etc)'
    },
    size: {
      type: String,
      default: '',
      description: 'Button size (sm|lg)'
    },
    outline: {
      type: Boolean,
      description: 'Whether button is outlined (only border has color)'
    },
    link: {
      type: Boolean,
      description: 'Whether button is a link (no borders or background)'
    }
  },
  methods: {
    handleClick(evt) {
      this.$emit('click', evt);
    }
  }
};
</script>