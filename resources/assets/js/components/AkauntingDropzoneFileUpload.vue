<template>
    <div :id="'dropzone-' + _uid" class="dropzone mb-3 dz-clickable" :class="[preview == 'single' ? 'dropzone-single': 'dropzone-multiple']">
        <div class="fallback">
            <div class="custom-file">
                <input type="file" class="custom-file-input" :id="'projectCoverUploads' + _uid" :multiple="multiple">

                <label class="custom-file-label" :for="'projectCoverUploads' + _uid">{{ textChooseFile }}</label>
            </div>
        </div>

        <div v-if="preview == 'single'" class="dz-preview dz-preview-single" :class="previewClasses" ref="previewSingle">
            <div class="dz-preview-cover">
                <img class="dz-preview-img" data-dz-thumbnail>
                <i class="fas fa-file-image display-3 fa-2x mt-2 d-none" data-dz-thumbnail-image></i>
                <i class="far fa-file-pdf display-3 fa-2x mt-2 d-none" data-dz-thumbnail-pdf></i>
                <i class="far fa-file-word fa-2x mt-2 d-none" data-dz-thumbnail-word></i>
                <i class="far fa-file-excel fa-2x mt-2 d-none" data-dz-thumbnail-excel></i>
                <span class="mb-1 d-none" data-dz-name>...</span>
            </div>
        </div>

        <ul v-else class="dz-preview dz-preview-multiple list-group list-group-lg list-group-flush" :class="previewClasses" ref="previewMultiple">
            <li class="list-group-item px-0">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="avatar">
                            <img class="avatar-img rounded" data-dz-thumbnail>
                            <i class="fas fa-file-image display-3 d-none" data-dz-thumbnail-image></i>
                            <i class="far fa-file-pdf display-3 d-none" data-dz-thumbnail-pdf></i>
                            <i class="far fa-file-word d-none" data-dz-thumbnail-word></i>
                            <i class="far fa-file-excel d-none" data-dz-thumbnail-excel></i>
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
                        <a href="#" type="button" class="btn btn-sm btn-info text-white d-none" data-dz-download>
                            <i class="fas fa-file-download"></i>
                        </a>
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
        textDropFile: {
            type: String,
            default: 'Drop files here to upload',
            description: 'Drop file text'
        },
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
        preview: {
            type: String,
            default: function () {
                return this.multiple ? 'multiple' : 'single'
            },
        },
        attachments: {
            type: Array,
            default: () => ([])
        },
    },

    model: {
        prop: 'value',
        event: 'change'
    },

    data() {
        return {
            files: [],
            configurations: this.options,
        }
    },

    methods: {
        async initDropzone() {
            let self = this;
            let preview = this.preview == 'single' ? this.$refs.previewSingle : this.$refs.previewMultiple;

            if (this.configurations.maxFiles === undefined && this.multiple == false) {
                this.configurations.maxFiles = 1;
            }

            if (this.configurations.acceptedFiles === undefined) {
                this.configurations.acceptedFiles = 'image/*';
            }

            let finalOptions = {
                ...self.configurations,
                url: this.url,
                previewsContainer: preview,
                previewTemplate: preview.innerHTML,
                dictDefaultMessage: this.textDropFile,
                autoProcessQueue: false,

                init: function () {
                    let dropzone = this;

                    dropzone.on('addedfile', function (file) {
                        self.files.push(file);

                        if (self.configurations.maxFiles == 1) {
                            self.$emit('change', file);
                        } else {
                            self.$emit('change', self.files);
                        }

                        if (file.type.indexOf("image") == -1) {
                            let ext = file.name.split('.').pop();

                            file.previewElement.querySelector("[data-dz-thumbnail]").classList.add("d-none");
                            file.previewElement.querySelector("[data-dz-name]").classList.remove("d-none");

                            if (ext == "pdf") {
                                file.previewElement.querySelector("[data-dz-thumbnail-pdf]").classList.remove("d-none");
                            } else if ((ext.indexOf("doc") != -1) || (ext.indexOf("docx") != -1)) {
                                file.previewElement.querySelector("[data-dz-thumbnail-word]").classList.remove("d-none");
                            } else if ((ext.indexOf("xls") != -1) || (ext.indexOf("xlsx") != -1)) {
                                file.previewElement.querySelector("[data-dz-thumbnail-excel]").classList.remove("d-none");
                            } else {
                                file.previewElement.querySelector("[data-dz-thumbnail-image]").classList.remove("d-none");
                            }
                        }
                    }),
 
                    dropzone.on('removedfile', function (file) {
                        let index = self.files.findIndex(f => f.name === file.name)

                        if (index !== -1) {
                            self.files.splice(index, 1);
                        }

                        self.$emit('change', self.files);

                        if (self.multiple) {
                            this.enable();
                        }
                    }),

                    dropzone.on('maxfilesexceeded', function(file) {
                        this.removeAllFiles('notCancel');
                        this.addFile(file);
                    }),

                    dropzone.on('maxfilesreached', function(file) {
                        if (self.multiple) {
                            this.disable();
                        }
                    })

                    if (self.attachments.length) {
                        setTimeout(() => {
                            self.attachments.forEach(async (attachment) => {
                                var mockFile = {
                                    id: attachment.id,
                                    name: attachment.name,
                                    size: attachment.size,
                                    type: attachment.type,
                                    download: attachment.downloadPath,
                                    dropzone: 'edit',
                                };

                                dropzone.emit("addedfile", mockFile);
                                dropzone.options.thumbnail.call(dropzone, mockFile, attachment.path);

                                // Make sure that there is no progress bar, etc...
                                dropzone.emit("complete", mockFile);
                            });

                            self.files.forEach(async (attachment) => {
                                if (attachment.download) {
                                    attachment.previewElement.querySelector("[data-dz-download]").href = attachment.download;
                                    attachment.previewElement.querySelector("[data-dz-download]").classList.remove("d-none");
                                }
                            });

                            if (self.preview == 'single' && self.attachments.length == 1) {
                                self.$nextTick(() => {
                                    document.querySelector("#dropzone-" + self._uid).classList.add("dz-max-files-reached");
                                });
                            }
                        }, 100);
                    }
                }
            };

            this.dropzone = new Dropzone(this.$el, finalOptions);

            preview.innerHTML = '';
        },
    },

    async mounted() {
        this.initDropzone();
    },
}
</script>

<style>
</style>
