<template>

    <div class="flex flex-col gap-1 relative">
        <div class="flex justify-between items-center">
            <label class="text-subtitle font-medium">
                {{ label }}
                <span v-if="required" class="text-red-600">*</span>
            </label>
            <button v-if="action" @click="$emit('click')" type="button" class="px-2 text-brand-primary text-xs rounded-md focus:outline-none hover:bg-border-light/25 transition-colors duration-300">+ Create</button>
        </div>
        <div v-if="selected" class="peer flex items-center justify-between focus:outline-brand-primary sm:focus:outline-none focus:outline focus:outline-2 focus:outline-offset-2 h-10 w-full p-2 border border-border-light rounded-md">
            <label class="">{{ selected }}</label>
            <svg @click="resetSelected" class="cursor-pointer scale-[0.8] origin-right" width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12.1723 10.7574L10.7581 12.1716L13.5865 15L10.7581 17.8284L12.1723 19.2426L15.0008 16.4142L17.8292 19.2426L19.2434 17.8284L16.415 15L19.2434 12.1716L17.8292 10.7574L15.0008 13.5858L12.1723 10.7574Z" fill="#FF8100"/>
                <path d="M7.92849 7.92893C4.0295 11.8279 4.0295 18.1721 7.92849 22.0711C11.8275 25.9701 18.1716 25.9701 22.0706 22.0711C25.9696 18.1721 25.9696 11.8279 22.0706 7.92893C18.1716 4.02995 11.8275 4.02995 7.92849 7.92893ZM20.6564 20.6569C17.5374 23.7759 12.4618 23.7759 9.3427 20.6569C6.22366 17.5378 6.22366 12.4622 9.3427 9.34315C12.4618 6.2241 17.5374 6.2241 20.6564 9.34315C23.7755 12.4622 23.7755 17.5378 20.6564 20.6569Z" fill="#FF8100"/>
            </svg>
        </div>
        <input
            v-else
            v-model="searchQuery"
            :required="required" type="text"
            :placeholder="placeholder"
            class="peer focus:outline-brand-primary sm:focus:outline-none focus:outline focus:outline-2 focus:outline-offset-2 h-10 w-full p-2 border border-border-light rounded-md" />
        <ul v-if="searchResults.length > 0" ref="searchOptions" class="scrollbar peer-focus:inline hidden active:inline absolute w-full max-h-[12.5rem] overflow-auto rounded-md border border-border-light shadow-md bg-white bottom-0 translate-y-full -mb-2 z-20">
            <template v-for="search in searchResults">
                <li tabindex="0" @click="selectSearchResult(search)" class="h-16 flex cursor-pointer hover:text-brand-primary w-full h-10 items-center px-4 hover:bg-brand-secondary">
                    <div class="flex flex-col items-start justify-between">
                        <p>{{ search[displayData[0]] }}</p>
                        <p class="text-subtitle">
                            {{ search[displayData[1]] }}
                        </p>
                    </div>
                </li>
            </template>
        </ul>
    </div>

</template>

<script setup>

    import { ref, watch, onMounted, computed } from "vue"

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
        formData: {
            type: [ String, Number ]
        },
        selectable: {
            type: String,
            required: true
        },
        displayData: {
            type: Array,
            default: ['title', 'code']
        },
        searchLogic: {
            type: Function,
            required: true
        },
        selected: {
            type: String
        },
        action: {
            type: Boolean,
            default: true
        }
    })
    const emit = defineEmits(['click', 'update:formData', 'update:selected'])

    const searchResults = ref([])
    const searchQuery = ref('')

    function selectSearchResult(search) {
        emit('update:formData', search[props.selectable])
        emit('update:selected', search[props.displayData[0]])
    }

    function resetSelected() {
        emit('update:selected', '')
        searchQuery.value = ''
        emit('update:formData', null)
    }

    const computedSelected = computed(() => props.selected)
    const computedFormData = computed(() => props.formData)
    watch(computedSelected, () => {

        if (!! ! computedSelected.value && !! ! computedFormData.value) {
            searchQuery.value = ''
        }

    })

    watch(searchQuery, () => {

        if (searchQuery.value) {
            props.searchLogic(searchQuery.value).then((response) => {
                searchResults.value = response['results']
            })
        } else {
            searchResults.value = []
        }
    })

    onMounted(() => {

        if (!! props.formData) {
            props.searchLogic(props.formData, '/api/books', 'id').then((response) => {
                emit('update:selected', response[props.displayData[0]])
            })
        }

    })

</script>

<style scoped>

</style>
