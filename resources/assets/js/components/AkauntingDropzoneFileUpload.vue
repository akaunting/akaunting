<template>
    <div :id="'dropzone-' + _uid" class="dropzone dz-clickable" :class="[preview == 'single' ? 'dropzone-single': 'dropzone-multiple', singleWidthClasses ? 'w-full': 'sm:w-37', {'crop-active': cropVisible}]">
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
                <span class="mb-1 text-sm ltr:ml-3 rtl:mr-3 text-gray-500 hidden" data-dz-name>...</span>

                <div class="gap-x-1 relative">
                    <button data-dz-remove="true" class="absolute group ltr:right-0 rtl:left-0">
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

                        <div class="col text-gray-500 ltr:ml-3 rtl:mr-3">
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

        <div v-if="cropVisible" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">
            <div class="w-full max-w-3xl rounded-lg bg-white p-4 shadow-xl">
                <div class="mb-4 flex h-96 items-center justify-center overflow-hidden bg-gray-100">
                    <img ref="cropImage" :src="cropUrl" class="block max-h-full max-w-full">
                </div>

                <div class="flex items-center justify-end space-x-2 rtl:space-x-reverse">
                    <button type="button" class="px-6 py-1.5 hover:bg-gray-200 rounded-lg disabled:opacity-50" :disabled="cropProcessing" @click="cancelCrop">{{ textCancelCrop }}</button>
                    <button type="button" class="relative flex items-center justify-center bg-green hover:bg-green-700 text-white px-6 py-1.5 text-base rounded-lg disabled:bg-green-100" :disabled="cropProcessing" @click="applyCrop">{{ textApplyCrop }}</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Dropzone from 'dropzone';
import Cropper from 'cropperjs';
import 'cropperjs/dist/cropper.css';

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
        textCancelCrop: {
            type: String,
            default: 'Cancel',
            description: 'Cancel crop text'
        },
        textApplyCrop: {
            type: String,
            default: 'Apply',
            description: 'Apply crop text'
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
        resizeOptions: {
            type: Object,
            default: null
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
            cropper: null,
            cropFile: null,
            cropUrl: '',
            cropVisible: false,
            cropProcessing: false,
        }
    },

    methods: {
        addFile(file) {
            if (this.resizeOptions && this.resizeOptions.crop && file instanceof File && file.type.startsWith('image/') && !file.isCropped) {
                if (this.cropVisible || this.cropProcessing) {
                    this.dropzone.removeFile(file);

                    return;
                }

                this.openCropper(file);

                return;
            }

            this.processFile(file);
        },

        removeExistingCropFiles(file) {
            this.files.slice().forEach((existingFile) => {
                if (existingFile === file) {
                    return;
                }

                if (this.dropzone.files.includes(existingFile)) {
                    this.dropzone.removeFile(existingFile);

                    return;
                }

                if (existingFile.previewElement) {
                    existingFile.previewElement.remove();
                }
            });

            this.files = [];
            this.$emit('change', this.configurations.maxFiles == 1 ? null : []);
        },

        openCropper(file) {
            this.cropFile = file;
            this.cropUrl = URL.createObjectURL(file);
            this.cropVisible = true;
            this.$emit('crop-state', true);

            this.$nextTick(() => {
                this.cropper = new Cropper(this.$refs.cropImage, {
                    aspectRatio: this.resizeOptions.aspectRatio || 1,
                    autoCropArea: 1,
                    responsive: true,
                    viewMode: 1,
                });
            });
        },

        cancelCrop() {
            if (this.cropProcessing) {
                return;
            }

            const file = this.cropFile;

            this.closeCropper(true);
            this.$emit('crop-state', false);

            if (file && this.dropzone) {
                this.dropzone.removeFile(file);
            }
        },

        closeCropper(clearFile = false) {
            if (this.cropper) {
                this.cropper.destroy();
                this.cropper = null;
            }

            if (this.cropUrl) {
                URL.revokeObjectURL(this.cropUrl);
            }

            this.cropUrl = '';
            this.cropVisible = false;

            if (clearFile) {
                this.cropFile = null;
            }
        },

        applyCrop() {
            if (!this.cropper || !this.cropFile) {
                return;
            }

            const file = this.cropFile;
            const canvas = this.cropper.getCroppedCanvas();
            const outputType = file.type === 'image/png' ? 'image/png' : 'image/jpeg';
            const extension = outputType === 'image/png' ? 'png' : 'jpg';
            const filename = file.name.replace(/\.[^/.]+$/, '') + '.' + extension;
            this.cropProcessing = true;

            canvas.toBlob((blob) => {
                if (!blob) {
                    this.cropProcessing = false;
                    this.closeCropper(true);
                    this.$emit('crop-state', false);

                    return;
                }

                const croppedFile = new File([blob], filename, {
                    type: outputType,
                    lastModified: file.lastModified,
                });

                croppedFile.isCropped = true;

                if (this.dropzone) {
                    this.removeExistingCropFiles(file);
                    this.dropzone.removeFile(file);
                    this.dropzone.addFile(croppedFile);
                }
            }, outputType, this.resizeOptions.quality || 0.9);
        },

        async resizeFile(file) {
            if (!this.resizeOptions || !(file instanceof File) || !file.type.startsWith('image/')) {
                return file;
            }

            const maxWidth = this.resizeOptions.maxWidth || 1000;
            const maxHeight = this.resizeOptions.maxHeight || 1000;
            const quality = this.resizeOptions.quality || 0.9;
            const imageUrl = URL.createObjectURL(file);

            try {
                const image = await new Promise((resolve, reject) => {
                    const element = new Image();

                    element.onload = () => resolve(element);
                    element.onerror = reject;
                    element.src = imageUrl;
                });

                if (image.width <= maxWidth && image.height <= maxHeight) {
                    return file;
                }

                const scale = Math.min(maxWidth / image.width, maxHeight / image.height);
                const canvas = document.createElement('canvas');
                canvas.width = Math.round(image.width * scale);
                canvas.height = Math.round(image.height * scale);

                const context = canvas.getContext('2d');
                context.drawImage(image, 0, 0, canvas.width, canvas.height);

                const blob = await new Promise((resolve) => {
                    canvas.toBlob(resolve, file.type, quality);
                });

                if (!blob) {
                    return file;
                }

                return new File([blob], file.name, {
                    type: file.type,
                    lastModified: file.lastModified,
                });
            } catch (error) {
                return file;
            } finally {
                URL.revokeObjectURL(imageUrl);
            }
        },

        async processFile(file) {
            try {
                const finalFile = await this.resizeFile(file);

                this.files.push(finalFile);

                if (this.configurations.maxFiles == 1) {
                    this.$emit('change', finalFile);
                } else {
                    this.$emit('change', this.files);
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

                this.onMaxFilesReached(this);
            } finally {
                if (file.isCropped) {
                    this.closeCropper(true);
                    this.cropProcessing = false;
                    this.$emit('crop-state', false);
                }
            }
        },

        async initDropzone() {
            let self = this;
            let preview = this.preview == 'single' ? this.$refs.previewSingle : this.$refs.previewMultiple;

            if (this.configurations.maxFiles === undefined && this.multiple == false) {
                this.configurations.maxFiles = 1;
            }

            if (this.resizeOptions && this.resizeOptions.crop) {
                this.configurations.acceptedFiles = 'image/jpeg,image/png';
            } else if (this.configurations.acceptedFiles === undefined) {
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
                        self.addFile(file);
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
                        if (self.resizeOptions && self.resizeOptions.crop) {
                            return;
                        }

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

        beforeDestroy() {
            this.closeCropper(true);
            this.$emit('crop-state', false);
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

    .dropzone.crop-active .dz-preview [data-dz-remove] {
        visibility: hidden;
        pointer-events: none;
    }

    .dropzone.crop-active .dz-preview {
        z-index: 0;
    }
</style>
