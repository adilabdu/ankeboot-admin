<template>

  <div class="flex flex-col gap-4 bg-white rounded-md border border-border-light shadow-sm overflow-hidden">

    <div class="leading-none flex justify-between items-center px-6 pt-6">
      <div>
        <h1 class="text-base font-medium">{{ title }}</h1>
        <h2 class="text-subtitle text-xs">
            {{ subtitle }}
        </h2>
      </div>

    </div>

    <div ref="canvasWrapper" class="relative flex grow max-h-[50%] -m-2 min-h-[8rem]">

        <canvas v-if="optionA" class="max-w-full max-h-full" ref="canvas" />

    </div>

  </div>

</template>

<script setup>

    import { ref, onMounted, watch } from "vue";
    import Chart from "chart.js/auto"

    const props = defineProps({
        title: {
            type: String,
            default: 'Monthly Sales'
        },
        subtitle: {
            type: String,
            default: 'Sales made within the previous 12 months'
        },
        data: {
            type: Array,
            required: true
        },
        backgroundColor: {
            type: String,
            default: '#FF810000'
        },
        displayYGrid: {
            type: Boolean,
            default: false
        }
    });

    const optionA = ref(true)
    function toggleOption(value) {
        optionA.value = value
    }

    const canvas = ref(null)
    const canvasTwo = ref(null)
    const context = ref(null)
    const canvasWrapper = ref(null)
    const myChart = ref(null)
    const ratios = [5550, 3911, 4390, 5591, 9100, 4911, 3192, 8912, 8310, 6381, 3984, 5299]
    const months = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]

    function renderCanvas(renderedCanvas) {

        context.value = renderedCanvas.getContext('2d')

        myChart.value = new Chart(context.value, {
            type: 'line',
            data: {
                labels: [
                    'Jan', 'Feb', 'Mar',
                    'Apr', 'May', 'Jun',
                    'Jul', 'Aug', 'Sep',
                    'Oct', 'Nov', 'Dec'
                ],
                datasets: [{
                    data: props.data,
                    borderColor: '#FF8100',
                    borderWidth: 3,
                    tension: 0.3,
                    cubicInterpolationMode: 'monotone',
                    fill: true,
                    backgroundColor: props.backgroundColor,
                    pointRadius: 2
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false,
                        position: 'bottom'
                    },
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            display: false
                        }
                    },
                    y: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    onMounted(() => {
        renderCanvas(canvas.value)
    })

</script>

<style scoped>

</style>
