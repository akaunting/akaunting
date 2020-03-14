<template>
    <div class="quill">
        <div :id="toolbarId">
            <div class="ql-formats">
                <button class="ql-bold"></button>
                <button class="ql-italic"></button>
                <button class="ql-underline"></button>
                <button class="ql-link"></button>
                <button class="ql-blockquote"></button>
                <button type="button" class="ql-list" value="ordered"></button>
                <button type="button" class="ql-list" value="bullet"></button>
            </div>
        </div>
        <div :id="editorId" :name="name" class="" ref="editor">
        </div>
    </div>
</template>
<script>
  export default {
    name: 'akaunting-html-editor',
    props: {
      value: {
        type: String,
        default: ''
      },
      name: String
    },
    data () {
      return {
        editor: null,
        content: null,
        lastHtmlValue: '',
        editorId: null,
        toolbarId: null
      }
    },
    methods: {
      initialize (Quill) {
        this.editor = new Quill(`#${this.editorId}`, {
          theme: 'snow',
          modules: {
            toolbar: `#${this.toolbarId}`
          }
        })

        if (this.value.length > 0) {
          this.editor.pasteHTML(this.value)
        }

        let editorRef = this.$refs.editor;
        let node = editorRef.children[0];
        this.editor.on('text-change', () => {
          let html = node.innerHTML
          if (html === '<p><br></p>') {
            html = '';
          }
          this.content = html
          this.$emit('input', this.content);
        })
      },
      pasteHTML () {
        if (!this.editor) {
          return
        }
        this.editor.pasteHTML(this.value)
      },
      randomString() {
        let text = "";
        let possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

        for (let i = 0; i < 5; i++)
          text += possible.charAt(Math.floor(Math.random() * possible.length));

        return text;
      }
    },
    async mounted () {
      let Quill = await import('quill')
      Quill = Quill.default || Quill
      this.editorId = this.randomString();
      this.toolbarId = this.randomString();
      this.$nextTick(() => {
        this.initialize(Quill)
      });
    },
    watch: {
      value (newVal) {
        if (newVal !== this.content) {
          this.pasteHTML(newVal);
        }
      }
    }
  }
</script>
