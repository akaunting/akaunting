<template>
  <div class="card">
    <div role="tab" class="card-header" :aria-expanded="active">
      <a
        data-toggle="collapse"
        data-parent="#accordion"
        :href="`#${itemId}`"
        @click.prevent="activate"
        :aria-controls="`content-${itemId}`"
      >
        <slot name="title"> {{ title }} </slot>
        <i class="tim-icons icon-minimal-down"></i>
      </a>
    </div>
    <collapse-transition :duration="animationDuration">
      <div
        v-show="active"
        :id="`content-${itemId}`"
        role="tabpanel"
        :aria-labelledby="title"
        class="collapsed"
      >
        <div class="card-body"><slot></slot></div>
      </div>
    </collapse-transition>
  </div>
</template>
<script>
import { CollapseTransition } from 'vue2-transitions';

export default {
  name: 'collapse-item',
  components: {
    CollapseTransition
  },
  props: {
    title: {
      type: String,
      default: '',
      description: 'Collapse item title'
    },
    id: String
  },
  inject: {
    animationDuration: {
      default: 250
    },
    multipleActive: {
      default: false
    },
    addItem: {
      default: () => {}
    },
    removeItem: {
      default: () => {}
    },
    deactivateAll: {
      default: () => {}
    }
  },
  computed: {
    itemId() {
      return this.id || this.title;
    }
  },
  data() {
    return {
      active: false
    };
  },
  methods: {
    activate() {
      let wasActive = this.active;
      if (!this.multipleActive) {
        this.deactivateAll();
      }
      this.active = !wasActive;
    }
  },
  mounted() {
    this.addItem(this);
  },
  destroyed() {
    if (this.$el && this.$el.parentNode) {
      this.$el.parentNode.removeChild(this.$el);
    }
    this.removeItem(this);
  }
};
</script>
<style></style>
