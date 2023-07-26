<template>
    <div :id="'dropzone-' + _uid" class="dropzone dropzone-single dz-clickable group w-full">
        <div class="fallback">
            <div class="custom-file">
                <input type="file" class="custom-file-input" :id="'projectCoverUploads' + _uid">

                <label class="custom-file-label" :for="'projectCoverUploads' + _uid">
                    {{ textChooseFile }}
                </label>
            </div>
        </div>

        <div class="dz-preview dz-preview-single" :class="previewClasses" ref="previewSingle">
            <div class="dz-preview-cover dz-default dz-preview-message flex flex-col">
                <div class="dz-import">
                    <div class="dz-import-icon relative">
                        <span class="material-icons-outlined text-default text-7xl">file_copy</span>

                        <button data-dz-remove="true" class="absolute z-10 right-18 top-0 hidden group-hover:flex bg-white">
                            <span class="material-icons-outlined text-gray-300 text-8xl">cancel</span>
                        </button>
                    </div>

                    <div class="relative h-12">
                        <p class="absolute dz-import-upload z-10" data-dz-name>...</p>

                        <p class="dz-import-text pt-6" v-html="textExtensionAndLimitationFile"></p>
                    </div>
                </div>

                <div class="w-full h-2 bg-purple-100 rounded-b-md -bottom-1 absolute group-hover:hidden">
                    <div class="w-0 h-2 bg-default rounded-b-md progress"></div>
                </div>
            </div>
        </div>

        <div class="dz-default dz-message flex flex-col" style="background-color: white;">
            <div class="dz-import">
                <div class="dz-import-icon">
                    <span class="material-icons-outlined text-7xl" :class="(files.length) ? 'text-default opacity-0' : ''">file_copy</span>
                </div>

                <div>
                    <p class="dz-import-upload" :class="(files.length) ? 'opacity-0' : ''" v-html="textDropFile"></p>

                    <p class="dz-import-text" v-html="textExtensionAndLimitationFile"></p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Dropzone from 'dropzone';

Dropzone.autoDiscover = false;

export default {
    name: 'akaunting-import',

    props: {
        textDropFile: {
            type: String,
            default: '<span style="color: #006EA6;">Upload a file</span> or drag and drop',
            description: 'Drop file text'
        },

        textExtensionAndLimitationFile: {
            type: String,
            default: '<span>XLS or XLSX</span> up to <span>500 rows</span>',
            description: 'Drop message text'
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

        previewClasses: [String, Object, Array],

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
            progress:0,
        }
    },

    methods: {
        async initDropzone() {
            let self = this;
            let preview = this.$refs.previewSingle;

            if (this.configurations.maxFiles === undefined) {
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
                        self.pushProgressbar();

                        self.files.push(file);

                        if (self.configurations.maxFiles == 1) {
                            self.$emit('change', file);
                        } else {
                            self.$emit('change', self.files);
                        }

                        self.onMaxFilesReached(self);
                    }),
 
                    dropzone.on('removedfile', function (file) {
                        let index = self.files.findIndex(f => f.name === file.name)

                        if (index !== -1) {
                            self.files.splice(index, 1);
                        }

                        self.$emit('change', self.files);

                        self.resetProgressBar();
                    }),

                    dropzone.on('maxfilesexceeded', function(file) {
                        this.removeAllFiles('notCancel');
                        this.addFile(file);
                    }),

                    dropzone.on('maxfilesreached', function(file) {
                        //
                    })
                }
            };

            this.dropzone = new Dropzone(this.$el, finalOptions);

            preview.innerHTML = '';
        },

        onMaxFilesReached(arg) {
            if (arg.attachments.length == 1) {
                arg.$nextTick(() => {
                    document.querySelector("#dropzone-" + arg._uid).classList.add("dz-max-files-reached");
                });
            }
        },

        async pushProgressbar() {
            if (this.progress == 0) {
                var elem = document.querySelector(".progress");
                var width = 1;
                var id = setInterval(frame, 2);
                var self = this;

                function frame() {
                    if (width >= 100) {
                        self.progress = 1;

                        clearInterval(id);
                    } else {
                        width++;

                        elem.style.width = width + "%";
                    }
                }
            }
        },

        async resetProgressBar() {
            this.progress = 0;
        }
    },

    async mounted() {
        this.initDropzone();
    },

    watch: {
        progress: function(new_value) {
            this.progress = new_value;
        },

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