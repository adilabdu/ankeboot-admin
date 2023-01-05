<template>

    <div class="flex flex-col bg-white rounded-md border border-border-light shadow-sm min-h-[16rem]">

        <div class="leading-none flex justify-between items-center px-6 pt-6">
            <div>
                <h1 class="text-base font-medium">{{ title }}</h1>
                <h2 class="text-subtitle text-xs">{{ subtitle }}</h2>
            </div>
        </div>

        <div ref="canvasWrapper" class="grow w-full p-4 flex items-center justify-start gap-5 sm:gap-0">

            <div class="relative w-fit h-fit chart">
                <canvas class="top-0 left-0 absolute -rotate-90" width="150" height="150" ref="fullDonutCanvas" />
                <label class="w-[150px] h-[150px] text-border-light font-semibold text-2xl grid place-items-center">
                    <span v-if="! fetching">{{ start }}</span>
                    <span v-else> N/A </span>
                </label>
                <canvas class="top-0 left-0 absolute -rotate-90" width="150" height="150" ref="donutCanvas" />
            </div>

            <div class="grow h-full p-4 flex flex-col gap-4">

                <div class="sm:hidden flex gap-3">

                    <div class="min-w-[1rem] h-4 mt-1 rounded-md bg-border-dark" />

                    <p class="text-subtitle text-xs">{{
                            legends['first']
                        }}</p>

                </div>

                <div class="flex gap-3">

                    <div class="min-w-[1rem] h-4 mt-1 rounded-md bg-brand-primary" />

                    <p class="text-subtitle text-xs">{{ legends['second'] }}</p>

                </div>


            </div>

        </div>

    </div>

</template>

<script setup>

    import { ref, computed, onMounted, watch } from "vue"
    import { delay } from "../../utils";

    const props = defineProps({
        title: {
            type: String,
            required: true
        },
        subtitle: {
            type: String,
            required: true
        },
        ratio: {
            type: Number,
            required: true
        },
        loading: {
            type: Boolean,
            default: true
        },
        legends: {
            type: Object,
            required: true
        }
    })

    const data = computed(() => props.ratio)
    const fetching = computed(() => props.loading)

    const donutCanvas = ref(null)
    const fullDonutCanvas = ref(null)
    const context = ref(null)
    const endAngle = ref(0)

    watch(data, () => {

        animate()
        riseToNumber((data.value * 100).toFixed(2))

    })

    const start = ref(0)
    function riseToNumber(end) {

        delay(1)
            .then(() => {

                if (start.value < end) {
                    start.value += 1
                    riseToNumber(end, start)
                } else {

                    start.value += Math.abs(end) - Math.floor(end)

                }

            })

    }

    function animate() {

        context.value.clearRect(0, 0, 150, 150)

        if (endAngle.value < data.value * (Math.PI * 2)) {

            endAngle.value += Math.PI / 20;

        }

        drawArc(endAngle.value)
        requestAnimationFrame(animate)

    }

    function drawArc(endAngle) {

        context.value.beginPath()
        context.value.arc(75, 75, 50, 0, endAngle);
        context.value.lineWidth = 16;
        context.value.lineCap = endAngle === 0 ? '' : 'round';
        context.value.strokeStyle = '#FF8100';
        context.value.stroke();

    }

    function drawFullArc() {

        const context = fullDonutCanvas.value.getContext('2d');
        context.arc(75, 75, 50, 0, Math.PI * 2);
        context.lineWidth = 15;
        context.strokeStyle = '#D7DBE0';
        context.stroke();

    }

    onMounted(() => {

        context.value = donutCanvas.value.getContext('2d');
        drawFullArc()

        if (! fetching.value) {
            requestAnimationFrame(animate)
            riseToNumber((data.value * 100).toFixed(2))
        }

    })

</script>

<style scoped>

</style>
