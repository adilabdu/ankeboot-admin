<template>

    <div class="flex sm:flex-col-reverse w-full gap-4">

        <File class="w-1/2 sm:w-full sm:scale-[.45] sm:origin-top-left" :date="formatDate(form.document_date)" :no="form.ref_no" title="የሰራተኛ ቅጥር ውል ስምምነት">

            <p class="px-4 pt-4">
                አኔ አቶ/ወይዘሪት/ወይዘሮ
                <span v-if="!! form.name" class="px-2 font-semibold">{{ form.name }}</span>
                <div v-else :class="[focused.name ? 'animate-pulse bg-gray-400/75' : '']" class="translate-y-1 w-64 bg-gray-400/25 inline-block h-5" />
                በአንከቡት መጻሕፍት መደብር ውስጥ በ
                <span v-if="!! form.role" class="px-2 font-semibold">{{ form.role }}</span>
                <div v-else :class="[focused.role ? 'animate-pulse bg-gray-400/75' : '']" class="translate-y-1 w-36 bg-gray-400/25 inline-block h-5" />
                ስራ ከ (ቀን)
                <span v-if="!! form.start_date.date" class="px-2 font-semibold">{{ formatDate(form.start_date) }}</span>
                <div v-else :class="[focused.start_date ? 'animate-pulse bg-gray-400/75' : '']" class="translate-y-1 w-36 bg-gray-400/25 inline-block h-5" />
                ጀምሮ በየወሩ ብር
                <span v-if="!! form.salary" class="px-2 font-semibold">{{ formatPrice(parseFloat(form.salary)) }}</span>
                <div v-else :class="[focused.salary ? 'animate-pulse bg-gray-400/75' : '']" class="translate-y-1 w-36 bg-gray-400/25 inline-block h-5" />
                /
                <span v-if="!! form.salary" class="px-2 font-semibold">{{ digitToWritten((form.salary)) }}</span>
                <div v-else :class="[focused.salary ? 'animate-pulse bg-gray-400/75' : '']" class="translate-y-1 w-36 bg-gray-400/25 inline-block h-5" />
                ወርሃዊ ደመወዝ እየተከፈለኝ ድርጅቱ በሚሰራበት ሰዓት ሁሉ ለመስራት፣ የትርፍ ክፍያ ብር
                <span v-if="!! form.overtime" class="px-2 font-semibold">{{ formatPrice(parseFloat(form.overtime)) }}</span>
                <div v-else :class="[focused.overtime ? 'animate-pulse bg-gray-400/75' : '']" class="translate-y-1 w-36 bg-gray-400/25 inline-block h-5" />
                /
                <span v-if="!! form.overtime" class="px-2 font-semibold">{{ digitToWritten((form.overtime)) }}</span>
                <div v-else :class="[focused.overtime ? 'animate-pulse bg-gray-400/75' : '']" class="translate-y-1 w-36 bg-gray-400/25 inline-block h-5" />
                ፣
                በአጠቃላይ ብር
                <span v-if="!! form.overtime || !! form.salary" class="px-2 font-semibold">{{ formatPrice(parseFloat(totalSalary)) }}</span>
                <div v-else class="translate-y-1 w-36 bg-gray-400/25 inline-block h-5" />
                /
                <span v-if="!! form.overtime || !! form.salary" class="px-2 font-semibold">{{ digitToWritten((totalSalary)) }}</span>
                <div v-else class="translate-y-1 w-36 bg-gray-400/25 inline-block h-5" />
                እየተከፈለኝ ለመስራት መስማማቱን በፊርማዬ አረጋግጣለሁ።
            </p>

            <p class="px-4 pt-4">
                በአንከቡት መጻሕፍት መደብር የዚህን መረጃ ትክክለኛነት በተወካዩና በሕጋዊ ማሕተሙ ያረጋግጣል።
            </p>

            <p class="px-4 pt-4">
                <span class="block">ማሳሰቢያ</span>
                <span class="block">
                    1. የድርጅቱ የስራ ሰዓት ከሰኞ እስከ ቅዳሜ ከጠዋት 2:00 ሰዓት እስከ ምሽቱ 12:00 ሰዓት ነው።
                </span>
                <span class="block">
                    2. ሁሉም ስራተኛ ድርጅቱ በሚሰራበት ሰዓት ሁሉ የመስራት ሃላፊነት አለበት።
                </span>
            </p>

            <div class="px-4 pt-12 flex w-full justify-between">

                <div class="flex flex-col">
                    <p>ውል ሰጪ አካል</p>
                    <p>አንከቡት መጻሕፍት መደብር</p>
                    <p class="mt-2">________________________________________</p>
                </div>

                <div class="flex flex-col items-end">
                    <p>ውል ተቀባይ አካል</p>
                    <p>ተቀጣሪ ስም እና ፊርማ</p>
                    <p class="mt-2">________________________________________</p>
                </div>

            </div>

        </File>

        <Form :submit="submitFileForm" class="w-1/2 sm:w-full" title="Fill file information" title-layout="contained">

            <template #subtitle>
                Add in the missing information to complete the file
            </template>

            <div class="flex sm:flex-col w-full gap-2">
                <DatePicker class="grow" v-model="form.document_date" label="Document Dated" />
                <TextInput class="grow" v-model="form.ref_no" label="Document No." placeholder="Document Reference No." />
            </div>

            <TextInput @focusout="toggleFocus('name')" @focusin="toggleFocus('name')" v-model="form.name" label="New Employee Name" placeholder="Provide the name of the new employee to be hired" />

            <div class="flex sm:flex-col w-full gap-2">
                <TextInput @focusout="toggleFocus('role')" @focusin="toggleFocus('role')" class="grow" v-model="form.role" label="Employee Position" placeholder="Position / Responsibility of the new employee" />
                <DatePicker @focusout="toggleFocus('start_date')" @focusin="toggleFocus('start_date')" class="grow" v-model="form.start_date" label="Start Date" placeholder="Select the starting date of the new employee" />
            </div>

            <div class="flex sm:flex-col w-full gap-2">
                <TextInput pattern="^[0-9]+.?[0-9]{0,2}$" @focusout="toggleFocus('salary')" @focusin="toggleFocus('salary')" class="w-1/2 sm:w-full" v-model="form.salary" label="Salary in ETB" placeholder="Salary of new employee in ETB" />
                <TextInput @focusout="toggleFocus('overtime')" @focusin="toggleFocus('overtime')" class="w-1/2 sm:w-full" v-model="form.overtime" label="Overtime Salary in ETB" placeholder="Overtime salary of new employee in ETB" />
            </div>

        </Form>

    </div>

</template>

<script setup>

    import { ref, computed } from "vue"
    import { formatDate, formatPrice, digitToWritten } from "../../utils"
    import File from "../../layouts/file.vue"
    import Form from "../../components/Form/Form.vue"
    import TextInput from "../../components/Form/TextInput.vue"
    import DatePicker from "../../components/Form/DatePicker.vue"

    const focused = ref({
        name: false,
        role: false,
        start_date: false,
        salary: false,
        overtime: false,
        document_date: false,
        ref_no: false,
    })
    function toggleFocus(field) {

        switch (field) {

            case 'name':
                focused.value.name = !focused.value.name
                break;

            case 'role':
                focused.value.role = !focused.value.role
                break;

            case 'start_date':
                focused.value.start_date = !focused.value.start_date
                break;

            case 'salary':
                focused.value.salary = !focused.value.salary
                break;

            case 'overtime':
                focused.value.overtime = !focused.value.overtime
                break;

            case 'document_date':
                focused.value.document_date = !focused.value.document_date
                break;

            case 'ref_no':
                focused.value.ref_no = !focused.value.ref_no
                break;

            default:
                break;

        }

    }

    const form = ref({
        name: "",
        role: "",
        start_date: {
            date: '',
            month: '',
            year: ''
        },
        salary: "",
        overtime: "",
        document_date: {
            date: new Date().getDate(),
            month: new Date().getMonth(),
            year: new Date().getFullYear()
        },
        ref_no: ""
    })

    const totalSalary = computed(() => {

        return parseFloat(form.value.salary === "" ? '0.00' : form.value.salary) + parseFloat(form.value.overtime === "" ? '0.00' : form.value.overtime)
    })

    function submitFileForm() {

        console.log('Submit File form')

    }

</script>

<style scoped></style>
