<template>

    <div class="flex flex-col gap-1 relative">
        <label class="text-subtitle font-medium">
            {{ label }}
            <span v-if="required" class="text-red-600">*</span>
        </label>
        <input
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            :required="required" type="text"
            :placeholder="placeholder"
            class="peer focus:outline-brand-primary sm:focus:outline-none focus:outline focus:outline-2 focus:outline-offset-2 h-10 w-full p-2 border border-border-light rounded-md" />
        <ul v-if="filteredTerms.length > 0" ref="searchOptions" class="scrollbar-brand peer-focus:inline hidden active:inline absolute w-full max-h-[12.5rem] overflow-auto rounded-md border border-border-light shadow-md bg-white bottom-0 translate-y-full -mb-2 z-20">
            <template v-for="search in filteredTerms">
                <li tabindex="0" @click="selectSearchResult(search)" class="flex cursor-pointer hover:text-brand-primary w-full h-10 items-center px-2 hover:bg-brand-secondary">
                    {{ search }}
                </li>
            </template>
        </ul>
    </div>

</template>

<script setup>

    import { ref, watch, computed } from "vue";

    const props = defineProps({
        label: {
            type: String,
            required: true
        },
        placeholder: {
            type: String,
            required: true
        },
        required: {
            type: Boolean,
            default: false
        },
        modelValue: {
            type: String
        },
        searchList: {
            type: Array,
            required: true
        }
    })
    const emit = defineEmits(['update:modelValue'])

    const searchOptions = ref(null)

    function selectSearchResult(selected) {
        emit('update:modelValue', selected)
    }

    const filteredTerms = ref(props.searchList)
    const searchTerm = computed(() => props.modelValue)
    watch(searchTerm, () => {

        filteredTerms.value = props.searchList.filter((item) => {

            return item.toLowerCase().includes(searchTerm.value.toLowerCase())

        })

    })

</script>

<style scoped>

    /*.scrollbar::-webkit-scrollbar {*/
    /*    width: 8px;*/
    /*}*/
    /*.scrollbar::-webkit-scrollbar-corner {*/
    /*    background: #ff8100;*/
    /*}*/
    /*.scrollbar::-webkit-scrollbar-thumb {*/
    /*    background-color: #D7DBE0;*/
    /*    border-radius: 6px;*/
    /*    border: 1px solid rgba(0,0,0,0);*/
    /*    background-clip: content-box;*/
    /*    transition: all;*/
    /*    transition-duration: 1s;*/
    /*}*/

    /*.scrollbar::-webkit-scrollbar-track {*/
    /*    background-color: rgba(0,0,0,0);*/
    /*}*/

</style>
