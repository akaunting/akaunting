<template>
  <div class="custom-file">
    <input type="file"
           class="custom-file-input"
           id="customFileLang"
           lang="en"
           v-bind="$attrs"
           v-on="listeners"
    />
    <label class="custom-file-label" for="customFileLang">
      {{label}}
    </label>
  </div>
</template>
<script>
  export default {
    name: 'file-input',
    inheritAttrs: false,
    props: {
      initialLabel: {
        type: String,
        default: 'Select file'
      }
    },
    data() {
      return {
        files: []
      }
    },
    computed: {
      listeners() {
        return {
          ...this.$listeners,
          change: this.fileChange
        }
      },
      label() {
        let fileNames = [];
        for (let file of this.files) {
          fileNames.push(file.name)
        }
        return fileNames.length ? fileNames.join(', ') : this.initialLabel
      }
    },
    methods: {
      fileChange(evt) {
        this.files = evt.target.files
        this.$emit('change', this.files)
      }
    }
  }
</script>
<style>
</style>
