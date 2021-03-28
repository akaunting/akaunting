<template>
  <div class="tags-input__wrapper">
    <el-tag
      v-for="(tag, index) in dynamicTags"
      :key="tag + index"
      size="small"
      :type="tagType"
      :closable="true"
      :close-transition="false"
      @close="handleClose(tag)"
    >
      {{ tag }}
    </el-tag>

    <input
      type="text"
      placeholder="Add new tag"
      class="form-control"
      v-model="inputValue"
      ref="saveTagInput"
      size="mini"
      @input="onInput"
      @keyup.enter="handleInputConfirm"
      @blur="handleInputConfirm"
    />
  </div>
</template>

<script>
import { Tag } from 'element-ui';

export default {
  name: 'tags-input',
  components: {
    [Tag.name]: Tag
  },
  props: {
    value: {
      type: Array,
      default: () => [],
      description: 'List of tags'
    },
    tagType: {
      type: String,
      default: 'primary',
      description: 'Tag type (primary|danger etc)'
    }
  },
  model: {
    prop: 'value',
    event: 'change'
  },
  data() {
    return {
      dynamicTags: [],
      inputVisible: false,
      inputValue: ''
    };
  },
  methods: {
    handleClose(tag) {
      this.dynamicTags.splice(this.dynamicTags.indexOf(tag), 1);
      this.$emit('change', this.dynamicTags);
    },
    showInput() {
      this.inputVisible = true;
      this.$nextTick(() => {
        this.$refs.saveTagInput.$refs.input.focus();
      });
    },

    handleInputConfirm() {
      let inputValue = this.inputValue;
      if (inputValue) {
        this.dynamicTags.push(inputValue);
        this.$emit('change', this.dynamicTags);
      }
      this.inputVisible = false;
      this.inputValue = '';
    },
    onInput(evt) {
      this.$emit('input', evt.target.value);
    }
  },
  created() {
    this.$watch(
      'value',
      newVal => {
        this.dynamicTags = [...newVal];
      },
      { immediate: true }
    );
  }
};
</script>
