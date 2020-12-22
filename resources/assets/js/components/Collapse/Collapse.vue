<template>
  <div
    id="accordion"
    role="tablist"
    aria-multiselectable="true"
    class="accordion"
  >
    <slot></slot>
  </div>
</template>

<script>
export default {
  name: 'collapse',
  props: {
    animationDuration: {
      type: Number,
      default: 250,
      description: 'Collapse animation duration'
    },
    multipleActive: {
      type: Boolean,
      default: true,
      description: 'Whether you can have multiple collapse items opened at the same time'
    },
    activeIndex: {
      type: Number,
      default: -1,
      description: 'Active collapse item index'
    }
  },
  provide() {
    return {
      animationDuration: this.animationDuration,
      multipleActive: this.multipleActive,
      addItem: this.addItem,
      removeItem: this.removeItem,
      deactivateAll: this.deactivateAll
    };
  },
  data() {
    return {
      items: []
    };
  },
  methods: {
    addItem(item) {
      const index = this.$slots.default.indexOf(item.$vnode);
      if (index !== -1) {
        this.items.splice(index, 0, item);
      }
    },
    removeItem(item) {
      const items = this.items;
      const index = items.indexOf(item);
      if (index > -1) {
        items.splice(index, 1);
      }
    },
    deactivateAll() {
      this.items.forEach(item => {
        item.active = false;
      });
    },
    activateItem() {
      if (this.activeIndex !== -1) {
        this.items[this.activeIndex].active = true;
      }
    }
  },
  mounted() {
    this.$nextTick(() => {
      this.activateItem();
    });
  },
  watch: {
    activeIndex() {
      this.activateItem();
    }
  }
};
</script>

<style scoped></style>
