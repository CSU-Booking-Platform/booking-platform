<template>
    <!-- component -->
    <div class="bg-white p7 rounded mx-auto">
        <div class="relative flex flex-col p-4 text-gray-400 border border-gray-200 rounded">
            <div ref="dnd" class="relative flex flex-col text-gray-400 border border-gray-200 border-dashed rounded cursor-pointer">
                <input :id="id" :accept="accept" type="file" :multiple="multiple"
                       class="absolute inset-0 z-50 w-full h-full p-0 m-0 outline-none opacity-0 cursor-pointer"
                       @change="addFiles($event)" @dragover="dragAddStyle" @dragleave="dragRemoveStyle" @drop="dragRemoveStyle"
                />

                <div class="flex flex-col items-center justify-center py-10 text-center">
                    <slot #icon>
                        <svg class="w-10 h-10 mr-1 text-current-50" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </slot>
                    <label class="block font-medium text-sm text-gray-700">
                        <span v-if="message" class="m-0">{{ message }}</span>
                        <span v-else class="m-0">
                            <slot>
                                Drag your files here or click in this area
                            </slot>
                        </span>
                    </label>
                </div>
            </div>

            <template v-if="files.length > 0 || storedFiles.length > 0">
                <div class="grid grid-cols-2 gap-4 mt-4 md:grid-cols-6" @drop.prevent="drop($event)"
                     @dragover.prevent="$event.dataTransfer.dropEffect = 'move'">

                    <div v-for="(file, index) in files" :key="index"
                         @dragstart="dragstart($event)" @dragend="fileDragging = null"
                         class="relative flex flex-col items-center overflow-hidden text-center bg-gray-100 border rounded cursor-move select-none"
                         style="padding-top: 100%;"
                         :class="{'border-blue-600': fileDragging === index}" draggable="true"
                    >

                        <button class="absolute top-0 right-0 z-50 p-1 bg-white rounded-bl focus:outline-none" type="button" @click="remove(index)">
                            <svg class="w-4 h-4 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>


                        <svg v-if="file.type.includes('audio/')"
                            class="absolute w-12 h-12 text-gray-400 transform top-1/2 -translate-y-2/3"
                             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                        </svg>

                        <svg v-if="file.type.includes('application/') || file.type === ''"
                            class="absolute w-12 h-12 text-gray-400 transform top-1/2 -translate-y-2/3"
                             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>

                        <img v-if="file.type.includes('image/')"
                             class="absolute inset-0 z-0 object-cover w-full h-full border-4 border-white preview"
                             :src="loadFile(file)" :alt="file.name"/>

                        <div class="absolute bottom-0 left-0 right-0 flex flex-col p-2 text-xs bg-white bg-opacity-50">
                        <span class="w-full font-bold text-gray-900 truncate">{{file.name}}</span>
                            <span class="text-xs text-gray-900">{{humanFileSize(file.size)}}</span>
                        </div>

                        <div class="absolute inset-0 z-40 transition-colors duration-300"
                             :class="{'bg-blue-200 bg-opacity-80': fileDropping === index && fileDragging !== index}"
                             @dragenter="dragenter($event)" @dragleave="fileDropping = null">
                        </div>
                    </div>

                    <div v-if="storedFiles.length > 0"
                         v-for="(storedFile, index) in storedFiles" :key="index"
                         class="relative flex flex-col items-center overflow-hidden text-center bg-gray-100 border rounded cursor-move select-none"
                         style="padding-top: 100%;"
                         :class="{'border-blue-600': fileDragging === index}" draggable="true"
                    >

                        <button class="absolute top-0 right-0 z-50 p-1 bg-white rounded-bl focus:outline-none" type="button" @click="removeStoredFile(index)">
                            <svg class="w-4 h-4 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>

                        <div class="absolute bottom-0 left-0 right-0 flex flex-col p-2 text-xs bg-white bg-opacity-50">
                            <span class="w-full font-bold text-gray-900 truncate">{{storedFile.substr(32, storedFile.length)}}</span>
                            <span class="text-xs text-gray-900">uploaded</span>
                        </div>

                        <div class="absolute inset-0 z-40 transition-colors duration-300"
                             :class="{'bg-blue-200 bg-opacity-80': fileDropping === index && fileDragging !== index}"
                        >
                        </div>
                    </div>

                </div>
            </template>
        </div>
    </div>
</template>

<script>
import createFileList from "create-file-list";

    export default {
        props: {
            id: {
                type: String,
                default() {
                    return `sortable-upload-${this._uid}`
                },
            },
            accept: {
                type: String,
                default: '*',
            },
            multiple: {
                type: Boolean,
                default: true,
            },
            message: {
                type: String,
                required: false,
            },
            existingFiles: {
                type: Array,
                default: function() {
                  return [];
                },
            },
        },
        mounted() {
            this.storedFiles = this.existingFiles;
        },
        data() {
            return {
                storedFiles: [],
                files: [],
                fileDragging: null,
                fileDropping: null,
            }
        },
        filters: {

        },
        methods: {
            dragAddStyle() {
                this.$refs.dnd.classList.add('border-blue-400');
                this.$refs.dnd.classList.add('ring-4');
                this.$refs.dnd.classList.add('ring-inset');
            },
            dragRemoveStyle() {
                this.$refs.dnd.classList.remove('border-blue-400');
                this.$refs.dnd.classList.remove('ring-4');
                this.$refs.dnd.classList.remove('ring-inset');
            },
            remove(index) {
                let files = [...this.files];
                files.splice(index, 1);

                this.files = createFileList(files);
                if (!this.files?.length) {
                    this.files = [];
                }
                this.$emit("change", this.files);
            },
            removeStoredFile(index) {
                let removed = this.storedFiles.splice(index, 1);
                this.$emit("remove-existing-file", removed);
            },
            drop(e) {
                let removed;
                let files = [...this.files];

                removed = files.splice(this.fileDragging, 1);
                files.splice(this.fileDropping, 0, ...removed);

                this.files = createFileList(files);
                this.$emit("change", this.files);

                this.fileDropping = null;
                this.fileDragging = null;
            },
            dragenter(e) {
                let targetElem = e.target.closest("[draggable]");
                this.fileDropping = targetElem.getAttribute("data-index");
            },
            dragstart(e) {
                this.fileDragging = e.target.closest("[draggable]").getAttribute("data-index");
                e.dataTransfer.effectAllowed = "move";
            },
            loadFile(file) {
                const preview = document.querySelectorAll(".preview");
                const blobUrl = URL.createObjectURL(file);

                preview.forEach(elem => {
                    elem.onload = () => {
                        URL.revokeObjectURL(elem.src); // free memory
                    };
                });

                return blobUrl;
            },
            addFiles(e) {
                this.files = createFileList([...this.files], [...e.target.files]);
                this.$emit("change", this.files);
            },
            humanFileSize(size) {
                const i = Math.floor(Math.log(size) / Math.log(1024));
                return (
                    (size / Math.pow(1024, i)).toFixed(2) * 1 +
                    " " +
                    ["B", "kB", "MB", "GB", "TB"][i]
                );
            },
        },
    }
</script>
