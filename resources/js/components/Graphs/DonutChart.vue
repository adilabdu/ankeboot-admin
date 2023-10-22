<template>

    <div id="donutChart" class="chart-child snap-center min-w-[362.68px] flex flex-col bg-white rounded-md border border-border-light shadow-sm gap-4 min-h-[16rem] max-h-[256px]">

        <div class="leading-none flex justify-between items-center px-6 pt-6">
            <div>
                <h1 class="text-base font-medium">{{ title }}</h1>
                <h2 class="text-subtitle text-xs">{{ subtitle }}</h2>
            </div>
        </div>

        <div ref="canvasWrapper" class="relative grow w-full flex items-center justify-start sm:gap-0">

            <div class="ml-4 relative w-fit h-fit chart">
                <canvas class="top-0 left-0 absolute -rotate-90" width="125" height="125" ref="fullDonutCanvas" />
                <label class="w-[125px] h-[125px] text-border-light font-semibold text-2xl grid place-items-center">
                    <span v-if="! fetching">{{ start }}</span>
                    <span v-else> N/A </span>
                </label>
                <canvas class="top-0 left-0 absolute -rotate-90" width="125" height="125" ref="donutCanvas" />
            </div>

            <div ref="legendWrapper" class="grow relative scrollbar-chart snap-x snap-mandatory h-full pt-8 pb-4 px-4 flex overflow-auto gap-4">

                <div
                    :class="{ 'order-2': legends['first'].length < legends['second'].length }"
                    class="snap-center w-full min-w-full flex gap-3">

                    <div class="min-w-[1rem] h-4 mt-1 rounded-md bg-border-dark" />

                    <p class="text-subtitle text-xs">
                        {{ legends['first'] }}
                    </p>

                </div>

                <div class="snap-center flex min-w-full gap-3">

                    <div class="min-w-[1rem] h-4 mt-1 rounded-md bg-brand-primary" />

                    <p class="text-subtitle text-xs">{{ legends['second'] }}</p>

                </div>

            </div>

            <div class="absolute bottom-0 left-[125px] py-2 w-[calc(100%-125px)] flex items-center justify-center gap-2">
                <div :class="[ activeLegend === 'left' ? 'bg-subtitle': 'bg-border-dark' ]" @click="scroll" class="hover:scale-125 cursor-pointer transition-transform duration-75 h-2 w-2 mt-1 rounded-full" />
                <div :class="[ activeLegend === 'right' ? 'bg-subtitle': 'bg-border-dark' ]" @click="scroll" class="hover:scale-125 cursor-pointer transition-transform duration-75 h-2 w-2 mt-1 rounded-full" />
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
        context.value.arc(62.5, 62.5, 50, 0, endAngle);
        context.value.lineWidth = 16;
        context.value.lineCap = endAngle === 0 ? '' : 'round';
        context.value.strokeStyle = '#FF8100';
        context.value.stroke();

    }

    function drawFullArc() {

        const context = fullDonutCanvas.value.getContext('2d');
        context.arc(62.5, 62.5, 50, 0, Math.PI * 2);
        context.lineWidth = 15;
        context.strokeStyle = '#D7DBE0';
        context.stroke();

    }

    const legendWrapper = ref(null)
    const activeLegend = ref('left')

    function scroll() {

        if (activeLegend.value === 'left') {
            legendWrapper.value.scrollTo({
                left: legendWrapper.value.scrollLeft + 1000,
                behavior: 'smooth'
            })
            activeLegend.value = 'right'
        } else {
            legendWrapper.value.scrollTo({
                left: legendWrapper.value.scrollLeft - 1000,
                behavior: 'smooth'
            })
            activeLegend.value = 'left'
        }

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

    .scrollbar-chart::-webkit-scrollbar {
        width: 0;
    }

</style>
