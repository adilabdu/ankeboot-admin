<template>

    <div
        tabindex="0"
        class="animate-pulse border border-border-dark bg-white focus:outline-none focus-visible:outline-2
         focus-visible:outline-brand-primary focus-visible:my-1 focus-visible:first:ml-1
         focus-visible:last:mr-1 transition-colors transition-opacity duration-500
         relative flex flex-col justify-between shrink-0 flex-1
         aspect-square max-w-[212px] max-h-[212px] rounded-xl shadow-sm"
    >

        <!-- Date card content -->
        <div
            class="flex flex-col justify-between rounded-xl p-4 h-full w-full">
            <div class="flex flex-row justify-between items-start">
                <div class="flex flex-col leading-tight">
                    <p class="text-subtitle text">{{}}</p>
                    <p class="font-medium text-2xl text-subtitle">{{}}</p>
                    <p class="text-subtitle text">{{}}</p>
                </div>
            </div>

            <div class="flex flex-col">
                <!-- TODO: add info for deposited amount -->
                <div class="flex flex-col leading-tight text-right">
                    <p class="text-subtitle text-xs"></p>
                    <p class="font-medium text-2xl proportional-nums"></p>
                </div>
            </div>
        </div>

    </div>

</template>

<script setup>

</script>

<style scoped></style>
