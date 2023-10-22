<template>

    <div class="col-span-1 group border shadow-sm rounded-t-md overflow-hidden h-44 bg-white flex flex-col">

        <div
            class="grow h-24 flex justify-center w-full bg-gray-200 relative pt-2 px-2"
        >
            <template v-if="! (formattedFileType(file['mimeType']) === 'folder')">
                <img class="pt-2 transition-all duration-300 group-hover:pt-0 object-cover object-top rounded-t" :src="file['thumbnailLink']" alt="image-alt" />
                <img class="min-h-[1rem] w-4 min-w-[1rem] h-4 absolute bottom-2 left-2" :src="file['iconLink'].replace('16', '64')" alt="icon-link" />
            </template>
            <img class="object-cover scale-50" v-else :src="file['iconLink'].replace('16', '128')" alt="folder-icon-link" />
        </div>
        <a target="_blank" :href="file['webViewLink']" class="h-20 border-t flex-col truncate justify-center px-2 w-full flex">

            <div class="flex flex-col max-w-full w-fit">
                <p class="inline w-fit text-xs font-medium truncate max-w-full whitespace-nowrap">{{ file['name'] }}</p>
                <hr class="w-0 group-hover:w-full -translate-y-px transition-all duration-50 h-px border-t border-t-gray-500 border-dotted" />
            </div>
            <p class="text-xs text-subtitle truncate whitespace-nowrap">
                Modified {{ format(file['modifiedTime']) }}
            </p>
            <p class="text-xs text-subtitle whitespace-nowrap truncate capitalize">
                {{ formattedFileType(file['mimeType']) }}
            </p>

        </a>

    </div>

</template>

<script setup>

    import { format } from "timeago.js";

    const props = defineProps({
        file: {
            type: Object,
            required: true
        }
    })

    function formattedFileType(mimeType) {
        return mimeType.split('.')[mimeType.split('.').length - 1]
    }

</script>

<style scoped>

</style>
