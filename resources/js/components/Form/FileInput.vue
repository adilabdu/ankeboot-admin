<template>

  <div
      @dragenter="toggleRegionActivation($event, true)"
      @drop="getFileFromDrop"
      @dragover="(event) => event.preventDefault()"
      @dragleave="toggleRegionActivation($event, false)"
      class="dropzone relative flex flex-col items-center justify-center gap-4 h-36 w-full border-2 border-dashed border-border-light rounded-md p-4"
  >

    <div :class="[ dragRegionActivated ? 'bg-white/75' : 'bg-white/0 z-0' ]" class="absolute flex flex-row-reverse rounded-md h-full w-full transition duration-300">
      <p @click="toggleRegionActivation(false)" :class="[ dragRegionActivated ? '' : 'hidden' ]" class="h-fit px-2 py-0.5 rotate-45 text-xl text-subtitle">+</p>
    </div>

    <svg width="18" height="22.5" viewBox="0 0 16 20" class="fill-subtitle z-10" xmlns="http://www.w3.org/2000/svg">
      <path d="M2 20H14C14.5304 20 15.0391 19.7893 15.4142 19.4142C15.7893 19.0391 16 18.5304 16 18V6L10 0H2C1.46957 0 0.960859 0.210714 0.585786 0.585786C0.210714 0.960859 0 1.46957 0 2V18C0 18.5304 0.210714 19.0391 0.585786 19.4142C0.960859 19.7893 1.46957 20 2 20ZM9 2L14 7H9V2ZM4 12H7V9H9V12H12V14H9V17H7V14H4V12Z" />
    </svg>

    <div class="flex flex-col items-center justify-center gap-0.5 z-10">
      <div :class="[file ? 'opacity-50' : '']" class="text-subtitle flex">
        <label>
          <span class="font-semibold text-brand-primary ">Upload a file</span>
          <input @change="getFileFromUpload" id="file-upload" name="file-upload" type="file" accept="text/csv" class="sr-only" />
        </label>
        <p class="font-medium">&nbsp;or drag and drop</p>
      </div>
      <p v-if="!file" class="text-subtitle"> Only <span class="font-mono">CSV</span> file types are supported. </p>
      <div v-else class="flex items-center gap-1.5">
        <p class="text-subtitle font-medium"> <span class="font-mono text-brand-primary">{{ file.name }}</span> uploaded </p>
        <button @click="resetFile" class="z-10 bg-subtitle p-1 rounded-md cursor-pointer">
          <svg width="10" height="10" viewBox="0 0 12 12" fill="white" xmlns="http://www.w3.org/2000/svg">
            <path d="M10.192 0.343994L5.94903 4.58599L1.70703 0.343994L0.29303 1.75799L4.53503 5.99999L0.29303 10.242L1.70703 11.656L5.94903 7.41399L10.192 11.656L11.606 10.242L7.36403 5.99999L11.606 1.75799L10.192 0.343994Z" fill="white"/>
          </svg>
        </button>
      </div>
    </div>

  </div>

</template>

<script setup>

  import { ref } from "vue";

  const dragRegionActivated = ref(false)
  const file = ref(null)

  function toggleRegionActivation(event, activate) {
    dragRegionActivated.value = activate
    event.preventDefault()
  }

  function getFileFromDrop(event) {

    if (event.dataTransfer.items[0].getAsFile().type === 'text/csv') {
      dragRegionActivated.value = false
      file.value = event.dataTransfer.items[0].getAsFile()
      console.log('file is ', file.value)

    } else {
      console.log('invalid file type: ', event.dataTransfer.items[0].getAsFile().type)
    }

    event.preventDefault()
  }

  function getFileFromUpload(event) {
    file.value = event.target.files[0]
    console.log('file is ', file.value)
  }

  function resetFile() {
    console.log('reset file')
    file.value = null
  }

</script>

<style scoped>

</style>