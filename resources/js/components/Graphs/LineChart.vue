<template>

  <div class="flex flex-col justify-between gap-4 bg-white rounded-md border border-border-light shadow-sm overflow-hidden">

    <div class="leading-none flex justify-between items-center px-6 pt-6">
      <div>
        <h1 class="text-base font-medium">{{ title }}</h1>
        <h2 class="text-subtitle text-xs">
            {{ subtitle }}
        </h2>
      </div>

    </div>

    <div class="w-full h-fit grid place-items-center">

        <Toggle v-model="itemCount" :labels="['By Item Count', 'By Receipt']"/>

    </div>

    <div class="relative flex grow max-h-[50%] -m-2 h-[8rem] relative">

        <canvas v-if="! itemCount" class="absolute max-w-full max-h-full" ref="canvas" id="canvas" />
        <canvas v-else class="absolute max-w-full max-h-full" ref="canvasTwo" id="canvasTwo" />

    </div>

  </div>

</template>

<script setup>

    import { ref, watch, computed, nextTick } from "vue";
    import Chart from "chart.js/auto"
    import Toggle from "../Toggle.vue";

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
            type: [Object, null],
            required: true
        },
        loading: {
            type: Boolean,
            default: false
        },
        backgroundColor: {
            type: String,
            default: '#FF810020'
        },
        displayYGrid: {
            type: Boolean,
            default: false
        }
    });

    const fetching = computed(() => props.loading)
    const data = computed(() => props.data)
    const values = computed(() => {
        if (props.data) {

            return Object.values(data.value).map((value) => {
                return (value / Math.max(...Object.values(data.value))) * 100
            })

        }

        return null
    })

    const canvas = ref(null)
    const canvasTwo = ref(null)
    const context = ref(null)
    const contextTwo = ref(null)
    const myChart = ref(null)

    const itemCount = ref(true)

    function renderCanvas(canvas, context, attribute) {

        context.value = canvas.value.getContext('2d')

        myChart.value = new Chart(context.value, {
            type: 'line',
            data: {
                labels: Object.keys(data.value[attribute]),
                datasets: [{
                    data: data.value[attribute],
                    borderColor: '#FF8100',
                    borderWidth: 3,
                    borderJoinStyle: 'round',
                    borderCapStyle: 'round',
                    tension: 0,
                    cubicInterpolationMode: 'default',
                    fill: true,
                    backgroundColor: props.backgroundColor,
                    pointRadius: 1.5
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

    watch(fetching, () => {

        if (! fetching.value) {

            if (! itemCount.value) {

                renderCanvas(canvas, context, 'by_receipts')

            } else {

                renderCanvas(canvasTwo, contextTwo, 'by_items')

            }

        }

    })

    watch(itemCount, () => {

        if (! itemCount.value) {

            nextTick(() => {
                renderCanvas(canvas, context, 'by_receipts')
            })

        } else {

            nextTick(() => {
                renderCanvas(canvasTwo, contextTwo, 'by_items')
            })

        }

    })

</script>

<style scoped>

</style>
