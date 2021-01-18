<template>
    <div class="dropzone mb-3 dz-clickable" :class="[isPreviewSingle ? 'dropzone-single': 'dropzone-multiple']">
        <div class="fallback">
            <div class="custom-file">
                <input type="file" class="custom-file-input" :id="'projectCoverUploads' + _uid" :multiple="multiple">

                <label class="custom-file-label" :for="'projectCoverUploads' + _uid">{{ textChooseFile }}</label>
          </div>
        </div>

        <div v-if="isPreviewSingle" class="dz-preview dz-preview-single" :class="previewClasses" ref="previewSingle">
            <div class="dz-preview-cover">
                <img class="dz-preview-img" data-dz-thumbnail>
            </div>
        </div>

        <ul v-else class="dz-preview dz-preview-multiple list-group list-group-lg list-group-flush" :class="previewClasses" ref="previewMultiple">
            <li class="list-group-item px-0">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="avatar">
                            <img class="avatar-img rounded" data-dz-thumbnail>
                        </div>
                    </div>

                    <div class="col ml--3">
                        <h4 class="mb-1" data-dz-name>...</h4>
                        <p class="small text-muted mb-0" data-dz-size>...</p>
                    </div>

                    <div class="col-auto">
                        <button data-dz-remove="true" class="btn btn-danger btn-sm">
                          <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</template>

<script>
import Dropzone from 'dropzone';

Dropzone.autoDiscover = false;

export default {
    name: 'akaunting-dropzone-file-upload',

    props: {
        textChooseFile: {
            type: String,
            default: 'Choose File',
            description: 'Choose file text'
        },
        options: {
            type: Object,
            default: () => ({})
        },
        value: [String, Object, Array, File],
        url: {
            type: String,
            default: 'http:'
        },
        multiple: {
            type: Boolean,
            default: false,
            description: 'Multiple file Upload'
        },
        previewClasses: [String, Object, Array],
        isPreviewSingle: {
            type: Boolean,
            default: true
        },
    },

    model: {
        prop: 'value',
        event: 'change'
    },

    data() {
        return {
            currentFile: null,
            files: [],
            showList: false,
        }
    },

    methods: {
        async initDropzone() {
            let self = this;
            let preview = this.isPreviewSingle ? this.$refs.previewSingle : this.$refs.previewMultiple;

            let finalOptions = {
              ...this.options,
              url: this.url,
              thumbnailWidth: null,
              thumbnailHeight: null,
              previewsContainer: preview,
              previewTemplate: preview.innerHTML,
              init: function () {
                this.on("addedfile", function (file) {
                    self.files.push(file);

                    if (self.options.maxFiles == 1) {
                        self.$emit('change', file);
                    } else {
                        self.$emit('change', this.files);
                    }
                }),
                this.on("maxfilesexceeded", function(file) {
                    this.removeAllFiles('notCancel');
                    this.addFile(file);
                });
              }
            };

            this.dropzone = new Dropzone(this.$el, finalOptions);

            preview.innerHTML = '';

            let evtList = [
              'drop',
              'dragstart',
              'dragend',
              'dragenter',
              'dragover',
              'addedfile',
              'removedfile',
              'thumbnail',
              'error',
              'processing',
              'uploadprogress',
              'sending',
              'success',
              'complete',
              'canceled',
              'maxfilesreached',
              'maxfilesexceeded',
              'processingmultiple',
              'sendingmultiple',
              'successmultiple',
              'completemultiple',
              'canceledmultiple',
              'totaluploadprogress',
              'reset',
              'queuecomplete',
            ];

            evtList.forEach(evt => {
                this.dropzone.on(evt, (file) => {
                    this.$emit(evt, file);
                    /*
                    if (evt === 'addedfile') {
                        if (self.multiple) {
                            this.files.push(file);

                            this.$emit('change', this.files);
                        } else {
                            this.file = file;
                            this.$emit('change', file);
                        }
                    }
                    */
                    
                    if (evt === 'removedfile') {
                        //if (self.multiple) {
                            let index = this.files.findIndex(f => f.upload.uuid === file.upload.uuid);

                            if (index !== -1) {
                                this.files.splice(index, 1);
                            }

                            this.$emit('change', this.files);
                        //} else {
                        //    this.file = '';

                        //    this.$emit('change', '');
                        //}
                    }
                })
            }, this);
        }
    },

    async mounted() {
        this.initDropzone();
    },
}
</script>

<style>
</style>
