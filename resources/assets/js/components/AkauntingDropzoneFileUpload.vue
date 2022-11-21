<template>
    <div :id="'dropzone-' + _uid" class="dropzone dz-clickable" :class="[preview == 'single' ? 'dropzone-single': 'dropzone-multiple', singleWidthClasses ? 'w-full': 'sm:w-37']">
        <div class="fallback">
            <div class="custom-file">
                <input type="file" class="custom-file-input" :id="'projectCoverUploads' + _uid" :multiple="multiple">

                <label class="custom-file-label" :for="'projectCoverUploads' + _uid">{{ textChooseFile }}</label>
            </div>
        </div>

        <div v-if="preview == 'single'" class="dz-preview dz-preview-single" :class="previewClasses" ref="previewSingle">
            <div class="dz-preview-cover">
                <img class="dz-preview-img" data-dz-thumbnail>
                <span class="material-icons hidden" data-dz-thumbnail-image>crop_original</span>
                <span class="material-icons-outlined avatar hidden">file_present</span>
                <span class="material-icons-outlined avatar hidden" data-dz-thumbnail-pdf>picture_as_pdf</span>
                <span class="material-icons-outlined avatar hidden" data-dz-thumbnail-word>content_paste</span>
                <span class="material-icons-outlined avatar hidden" data-dz-thumbnail-excel>table_chart</span>
                <span class="mb-1 text-sm ml-3 text-gray-500 hidden" data-dz-name>...</span>

                <div class="gap-x-1 relative">
                    <button data-dz-remove="true" class="absolute group right-0">
                        <span class="material-icons-outlined text-base text-gray-300 px-1.5 py-1 rounded-lg group-hover:bg-gray-100">delete</span>
                    </button>
                </div>
            </div>
        </div>

        <ul v-else class="dz-preview dz-preview-multiple list-group list-group-lg list-group-flush" :class="previewClasses" ref="previewMultiple">
            <li class="list-group-item border-b py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="avatar">
                            <img class="avatar-img h-full rounded object-cover" data-dz-thumbnail>
                            <span class="material-icons hidden" data-dz-thumbnail-image>crop_original</span>
                            <span class="material-icons-outlined display-3 hidden" data-dz-thumbnail-pdf>picture_as_pdf</span>
                            <span class="material-icons-outlined hidden" data-dz-thumbnail-word>content_paste</span>
                            <span class="material-icons-outlined hidden" data-dz-thumbnail-excel>table_chart</span>  
                        </div>

                        <div class="col text-gray-500 ml-3">
                            <h4 class="w-56 lg:w-96 text-sm mb-1 truncate" data-dz-name>...</h4>

                            <p class="text-xs text-muted mb-0" data-dz-size>...</p>
                        </div>
                    </div>

                    <div class="flex flex-col gap-x-1">
                        <button data-dz-remove="true" class="group">
                            <span class="material-icons-outlined text-base text-gray-300 px-1.5 py-1 rounded-lg group-hover:bg-gray-100">delete</span>
                        </button>
                        <a href="#" type="button" class="group hidden" data-dz-download>
                            <span class="material-icons text-base text-gray-300 px-1.5 py-1 rounded-lg group-hover:bg-gray-100">download</span>
                        </a>
                    </div>
                </div>

                <div class="text-red text-sm mt-1 block" data-dz-errormessage></div>
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
            type: [Object, Array],
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
        singleWidthClasses: {
            type: [Boolean, String],
            default: false
        },
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

                            file.previewElement.querySelector("[data-dz-thumbnail]").classList.add("hidden");
                            file.previewElement.querySelector("[data-dz-name]").classList.remove("hidden");

                            if (ext == "pdf") {
                                file.previewElement.querySelector("[data-dz-thumbnail-pdf]").classList.remove("hidden");
                            } else if ((ext.indexOf("doc") != -1) || (ext.indexOf("docx") != -1)) {
                                file.previewElement.querySelector("[data-dz-thumbnail-word]").classList.remove("hidden");
                            } else if ((ext.indexOf("xls") != -1) || (ext.indexOf("xlsx") != -1)) {
                                file.previewElement.querySelector("[data-dz-thumbnail-excel]").classList.remove("hidden");
                            } else {
                                file.previewElement.querySelector("[data-dz-thumbnail-image]").classList.remove("hidden");
                            }
                        }

                        self.onMaxFilesReached(self);
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
                                    attachment.previewElement.querySelector("[data-dz-download]").classList.remove("hidden");
                                }
                            });

                            self.onMaxFilesReached(self);
                        }, 100);
                    }
                }
            };

            this.dropzone = new Dropzone(this.$el, finalOptions);

            preview.innerHTML = '';
        },

        onMaxFilesReached(arg) {
            if (arg.preview == 'single' || arg.attachments.length == 1) {
                arg.$nextTick(() => {
                    document.querySelector("#dropzone-" + arg._uid).classList.add("dz-max-files-reached");
                });
            }
        }
    },

    async mounted() {
        this.initDropzone();
    },

    watch: {
        attachments: function (attachments) {
            attachments.forEach((attachment) => {
                if (attachment.length != undefined) {
                    var mockFile = {
                        id: attachment[0].id,
                        name: attachment[0].name,
                        size: attachment[0].size,
                        type: attachment[0].type,
                        download: attachment[0].downloadPath,
                        dropzone: 'edit',
                    };

                    this.dropzone.emit("addedfile", mockFile);
                    this.dropzone.options.thumbnail.call(this.dropzone, mockFile, attachment[0].path);

                    // Make sure that there is no progress bar, etc...
                    this.dropzone.emit("complete", mockFile);

                    this.files.forEach(async (attachment) => {
                        if (attachment.download) {
                            attachment.previewElement.querySelector("[data-dz-download]").href = attachment.download;
                            attachment.previewElement.querySelector("[data-dz-download]").classList.remove("hidden");
                        }
                    });

                    this.onMaxFilesReached(this);
                }
            }, this);
        },
    },
}
</script>

<style>
    .avatar.hidden {
        display: none;
    }
</style>
