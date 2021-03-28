<template>
  <div class="wrapper">
    <div :class="`progress-${type}`" v-if="showLabel">
      <div class="progress-label">
        <slot name="label">
          <span>{{label}}</span>
        </slot>
      </div>
      <div class="progress-percentage">
        <slot>
          <span>{{value}}%</span>
        </slot>
      </div>
    </div>
    <div class="progress"
         :class="[{[`progress-${size}`]: size}, progressClasses]"
         :style="`height: ${height}px`">
      <div class="progress-bar"
           :class="computedClasses"
           role="progressbar"
           :aria-valuenow="value"
           aria-valuemin="0"
           aria-valuemax="100"
           :style="`width: ${value}%;`">
      </div>
    </div>
  </div>
</template>
<script>
  export default {
    name: "base-progress",
    props: {
      striped: {
        type: Boolean,
        description: "Whether progress is striped"
      },
      animated: {
        type: Boolean,
        description:
          "Whether progress is animated (works only with `striped` prop together)"
      },
      label: {
        type: String,
        description: "Progress label (shown on the left above progress)"
      },
      height: {
        type: Number,
        default: 3,
        description: "Progress line height"
      },
      type: {
        type: String,
        default: "default",
        description: "Progress type (e.g danger, primary etc)"
      },
      showLabel: {
        type: Boolean,
        default: false
      },
      progressClasses: {
        type: [Array, String],
        default: '',
        description: 'Progress css classes'
      },
      size: {
        type: String,
        default: ''
      },
      value: {
        type: Number,
        default: 0,
        validator: value => {
          return value >= 0 && value <= 100;
        },
        description: "Progress value"
      }
    },
    computed: {
      computedClasses() {
        return [
          { "progress-bar-striped": this.striped },
          { "progress-bar-animated": this.animated },
          { [`bg-${this.type}`]: this.type }
        ];
      }
    }
  };
</script>
<style>
</style>
