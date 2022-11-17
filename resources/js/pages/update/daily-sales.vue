<template>

    <Teleport to="#top-view">

        <Modal @close="$emit('close')">

            <div id="formModal" class="form-modal w-[45%] animate-scale-up">

                <Form @cancel="$emit('close')" :submit="submitUpdate" class="h-full w-full" title="Update Daily Sale" title-layout="contained">

                    <div ref="formContent" class="flex flex-col gap-6">

                        <div class="flex items-center justify-center w-full gap-2">
                            <TextInput class="w-2/5" disabled label="Date of Sale" v-model="data.date" placeholder="Name of cashier on call" />
                            <TextInput class="w-3/5" required label="Cashier" v-model="data.cashier" placeholder="Name of cashier on call" />
                        </div>

                        <!-- Sale receipts -->
                        <div class="gap-6 flex flex-col">

                            <div class="flex items-center justify-start w-full gap-2 px-2">
                                <p class="text-xs text-subtitle font-medium whitespace-nowrap">Sales receipts</p>
                                <div class="border-b border-border-light py-1 mb-2 w-full m-auto"></div>
                            </div>

                            <template v-for="(sale, index) in data.sales" :key="index">

                                <div class="flex items-start justify-center w-full gap-2">
                                    <SwitchInput v-if="index !== 0" v-model="sale.is_manual" label-location="top" class="px-2" label="Manual receipt" />
                                    <TextInput
                                        class="w-2/5"
                                        required
                                        :label="
                                            index === 0 ?
                                            'Z-Report amount (ETB)' :
                                            'Sale amount (ETB)'
                                        "
                                        v-model="sale.amount"
                                        placeholder="Total sale amount in ETB"
                                    />
                                    <TextInput class="w-3/5" required label="Receipt No." v-model="sale.receipt" :placeholder="index === 0 ? 'Receipt of Z-Report (Z:****)' : 'Sale receipt no.'" />
                                    <button type="button" @click="removeSales(index)" v-if="index !== 0" class="self-end rounded-md min-h-[2.5rem] min-w-[2.5rem] flex items-center justify-center">
                                        <svg width="15" height="17" viewBox="0 0 15 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M1.6 14.7333C1.6 15.1577 1.76857 15.5646 2.06863 15.8647C2.36869 16.1647 2.77565 16.3333 3.2 16.3333H11.2C11.6243 16.3333 12.0313 16.1647 12.3314 15.8647C12.6314 15.5646 12.8 15.1577 12.8 14.7333V5.13331H14.4V3.53331H11.2V1.93331C11.2 1.50897 11.0314 1.102 10.7314 0.801942C10.4313 0.501884 10.0243 0.333313 9.6 0.333313H4.8C4.37565 0.333313 3.96869 0.501884 3.66863 0.801942C3.36857 1.102 3.2 1.50897 3.2 1.93331V3.53331H0V5.13331H1.6V14.7333ZM4.8 1.93331H9.6V3.53331H4.8V1.93331ZM4 5.13331H11.2V14.7333H3.2V5.13331H4Z" fill="#FF8100"/>
                                            <path d="M4.80078 6.73334H6.40078V13.1333H4.80078V6.73334ZM8.00078 6.73334H9.60078V13.1333H8.00078V6.73334Z" fill="#FF8100"/>
                                        </svg>
                                    </button>
                                </div>

                            </template>

                            <button type="button" @click="addSales" class="-mt-2 px-4 py-2 gap-2 rounded-full bg-brand-secondary w-fit m-auto flex items-center justify-center">
                                <svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10.7301 4.95667H8.93008V8.55667H5.33008V10.3567H8.93008V13.9567H10.7301V10.3567H14.3301V8.55667H10.7301V4.95667Z" fill="#FF8100"/>
                                    <path d="M9.82813 0.455872C4.86553 0.455872 0.828125 4.49327 0.828125 9.45587C0.828125 14.4185 4.86553 18.4559 9.82813 18.4559C14.7907 18.4559 18.8281 14.4185 18.8281 9.45587C18.8281 4.49327 14.7907 0.455872 9.82813 0.455872ZM9.82813 16.6559C5.85823 16.6559 2.62813 13.4258 2.62813 9.45587C2.62813 5.48597 5.85823 2.25587 9.82813 2.25587C13.798 2.25587 17.0281 5.48597 17.0281 9.45587C17.0281 13.4258 13.798 16.6559 9.82813 16.6559Z" fill="#FF8100"/>
                                </svg>
                                <span class="focus:outline-none text-brand-primary text-xs font-medium">Add</span>
                            </button>

                        </div>

                        <!-- Deposits -->
                        <div class="gap-6 flex flex-col">

                            <div class="flex items-center justify-center w-full gap-2 px-2">
                                <p class="text-xs text-subtitle font-medium whitespace-nowrap">Deposits</p>
                                <div class="border-b border-border-light py-1 mb-2 w-full m-auto"></div>
                            </div>

                            <template v-for="(deposit, index) in data.deposits" :key="index">

                                <div class="flex flex-col w-full gap-2">
                                    <div class="flex items-start justify-start w-full gap-2">
                                        <button type="button" @click="removeDeposit(index)" v-if="index !== 0" class="self-end rounded-md h-10 w-10 flex items-center justify-center">
                                            <svg width="15" height="17" viewBox="0 0 15 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M1.6 14.7333C1.6 15.1577 1.76857 15.5646 2.06863 15.8647C2.36869 16.1647 2.77565 16.3333 3.2 16.3333H11.2C11.6243 16.3333 12.0313 16.1647 12.3314 15.8647C12.6314 15.5646 12.8 15.1577 12.8 14.7333V5.13331H14.4V3.53331H11.2V1.93331C11.2 1.50897 11.0314 1.102 10.7314 0.801942C10.4313 0.501884 10.0243 0.333313 9.6 0.333313H4.8C4.37565 0.333313 3.96869 0.501884 3.66863 0.801942C3.36857 1.102 3.2 1.50897 3.2 1.93331V3.53331H0V5.13331H1.6V14.7333ZM4.8 1.93331H9.6V3.53331H4.8V1.93331ZM4 5.13331H11.2V14.7333H3.2V5.13331H4Z" fill="#FF8100"/>
                                                <path d="M4.80078 6.73334H6.40078V13.1333H4.80078V6.73334ZM8.00078 6.73334H9.60078V13.1333H8.00078V6.73334Z" fill="#FF8100"/>
                                            </svg>
                                        </button>
                                        <TextInput class="w-1/3" required label="Deposit Amount" v-model="deposit.amount" placeholder="Total deposit amount in ETB" />
                                        <DatePicker drop-direction="up" label="Deposit date" required v-model="deposit.deposited_on" class="w-1/3" />
                                        <Dropdown drop-direction="up" required v-model="deposit.type" :list="['cash', 'withholding', 'cheque', 'transfer', 'credit', 'card']" placeholder="Choose the type of Deposit" label-direction="top" label="Deposit type" class="!w-1/3 h-16" />
                                    </div>
                                    <div v-if="deposit.type === 'credit'" class="flex items-start justify-start w-full gap-2">
                                        <div v-if="index !== 0" class="self-end rounded-md h-10 min-w-[2.5rem] flex items-center justify-center" />
                                        <TextInput class="w-full" required label="Credit receipt" v-model="deposit.receipt" placeholder="Receipt of the credit attachment (FS****)" />
                                        <TextInput class="w-full" required label="Credited To" v-model="deposit['client_name']" placeholder="Client name the sale was credited to" />
                                    </div>
                                </div>

                            </template>

                            <button type="button" @click="addDeposit" class="-mt-2 px-4 py-2 gap-2 rounded-full bg-brand-secondary w-fit m-auto flex items-center justify-center">
                                <svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10.7301 4.95667H8.93008V8.55667H5.33008V10.3567H8.93008V13.9567H10.7301V10.3567H14.3301V8.55667H10.7301V4.95667Z" fill="#FF8100"/>
                                    <path d="M9.82813 0.455872C4.86553 0.455872 0.828125 4.49327 0.828125 9.45587C0.828125 14.4185 4.86553 18.4559 9.82813 18.4559C14.7907 18.4559 18.8281 14.4185 18.8281 9.45587C18.8281 4.49327 14.7907 0.455872 9.82813 0.455872ZM9.82813 16.6559C5.85823 16.6559 2.62813 13.4258 2.62813 9.45587C2.62813 5.48597 5.85823 2.25587 9.82813 2.25587C13.798 2.25587 17.0281 5.48597 17.0281 9.45587C17.0281 13.4258 13.798 16.6559 9.82813 16.6559Z" fill="#FF8100"/>
                                </svg>
                                <span class="focus:outline-none text-brand-primary text-xs font-medium">Add</span>
                            </button>

                        </div>

                        <!-- Expenses -->
                        <Collapsable :open="data.expenses.length > 0" collapse-direction="down" :label="['Expenses', 'Expenses']">

                            <div class="gap-6 flex flex-col">

                                <template v-for="(expense, index) in data.expenses" :key="index">

                                    <div class="flex items-start justify-start w-full gap-2">
                                        <button type="button" @click="removeExpense(index)" class="self-end rounded-md h-10 w-10 flex items-center justify-center">
                                            <svg width="15" height="17" viewBox="0 0 15 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M1.6 14.7333C1.6 15.1577 1.76857 15.5646 2.06863 15.8647C2.36869 16.1647 2.77565 16.3333 3.2 16.3333H11.2C11.6243 16.3333 12.0313 16.1647 12.3314 15.8647C12.6314 15.5646 12.8 15.1577 12.8 14.7333V5.13331H14.4V3.53331H11.2V1.93331C11.2 1.50897 11.0314 1.102 10.7314 0.801942C10.4313 0.501884 10.0243 0.333313 9.6 0.333313H4.8C4.37565 0.333313 3.96869 0.501884 3.66863 0.801942C3.36857 1.102 3.2 1.50897 3.2 1.93331V3.53331H0V5.13331H1.6V14.7333ZM4.8 1.93331H9.6V3.53331H4.8V1.93331ZM4 5.13331H11.2V14.7333H3.2V5.13331H4Z" fill="#FF8100"/>
                                                <path d="M4.80078 6.73334H6.40078V13.1333H4.80078V6.73334ZM8.00078 6.73334H9.60078V13.1333H8.00078V6.73334Z" fill="#FF8100"/>
                                            </svg>
                                        </button>
                                        <TextInput class="w-1/3" required label="Expense Amount" v-model="expense.amount" placeholder="Total expense amount in ETB" />
                                        <TextInput class="w-2/3" required label="Expense Receipt" v-model="expense.receipt" placeholder="Payment voucher receipt issued for expense (PV****)" />
                                    </div>
                                    <div class="flex items-start justify-start w-full gap-2">
                                        <div class="self-end rounded-md h-10 min-w-[2.5rem] flex items-center justify-center" />
                                        <TextAreaInput
                                        class="w-full"
                                        required
                                        label="Expense Memo"
                                        v-model="expense.description"
                                        placeholder="Short description about the expense made (recommended information: expense reason, date)
                                        "
                                    />
                                    </div>

                                </template>

                                <button type="button" @click="addExpense" class="-mt-2 px-4 py-2 gap-2 rounded-full bg-brand-secondary w-fit m-auto flex items-center justify-center">
                                    <svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10.7301 4.95667H8.93008V8.55667H5.33008V10.3567H8.93008V13.9567H10.7301V10.3567H14.3301V8.55667H10.7301V4.95667Z" fill="#FF8100"/>
                                        <path d="M9.82813 0.455872C4.86553 0.455872 0.828125 4.49327 0.828125 9.45587C0.828125 14.4185 4.86553 18.4559 9.82813 18.4559C14.7907 18.4559 18.8281 14.4185 18.8281 9.45587C18.8281 4.49327 14.7907 0.455872 9.82813 0.455872ZM9.82813 16.6559C5.85823 16.6559 2.62813 13.4258 2.62813 9.45587C2.62813 5.48597 5.85823 2.25587 9.82813 2.25587C13.798 2.25587 17.0281 5.48597 17.0281 9.45587C17.0281 13.4258 13.798 16.6559 9.82813 16.6559Z" fill="#FF8100"/>
                                    </svg>
                                    <span class="focus:outline-none text-brand-primary text-xs font-medium">Add</span>
                                </button>

                            </div>

                        </Collapsable>

                    </div>


                </Form>

            </div>

        </Modal>

    </Teleport>

</template>

<script setup>

    import { onMounted, ref } from "vue"
    import router from "../../router"
    import store from "../../store"
    import { formatDate } from "../../utils"
    import Modal from "../../components/Modal.vue"
    import Form from "../../components/Form/Form.vue"
    import TextAreaInput from "../../components/Form/TextAreaInput.vue"
    import TextInput from "../../components/Form/TextInput.vue"
    import SwitchInput from "../../components/Form/SwitchInput.vue"
    import DatePicker from "../../components/Form/DatePicker.vue"
    import Dropdown from "../../components/Dropdown.vue"
    import Collapsable from "../../components/Collapsable.vue"

    const props = defineProps({
        dailySale: {
            type: Object,
            required: true
        }
    })

    const emits = defineEmits(['close', 'update'])

    function addSales() {

        data.value.sales.push({
            amount: 0.00,
            receipt: '',
            is_manual: false
        })

        console.log(overflowing(formContent))

    }

    function removeSales(index) {

        data.value.sales.splice(index, 1)

    }

    function addDeposit() {

        data.value.deposits.push({
            amount: '',
            type: '',
            deposited_on: {
                date: '',
                month: '',
                year: ''
            },
            receipt: '',
            client_name: '',
        })

    }

    function removeDeposit(index) {

        data.value.deposits.splice(index, 1)

    }

    function addExpense() {

        data.value.expenses.push({
            receipt: '',
            amount: 0.00,
            description: ''
        })

    }

    function removeExpense(index) {

        data.value.expenses.splice(index, 1)

    }

    const submitting = ref(false)
    const data = ref({
        id: props.dailySale.id,
        cashier: props.dailySale.cashier,
        date: new Date(props.dailySale.date_of).toDateString(),
        sales: props.dailySale.sales_receipts,
        deposits: props.dailySale.deposits.map((deposit) => {
            return {
                amount: deposit.amount,
                type: deposit.type,
                receipt: deposit.credit?.receipt,
                client_name: deposit.credit?.client_name,
                deposited_on: {
                    date: new Date(deposit.deposited_on).getDate(),
                    month: new Date(deposit.deposited_on).getMonth(),
                    year: new Date(deposit.deposited_on).getFullYear()
                }
            }
        }),
        expenses: props.dailySale.expenses
    })
    async function submitUpdate() {

        submitting.value = true

        const recordedDailySale = {
            update_submitted: true,
            cashier: data.value.cashier,
            sales_receipts: data.value.sales,
            deposits: data.value.deposits.filter((deposit) => {
                return deposit.type !== 'credit'
            }).map((deposit) => {
                return {
                    amount: deposit.amount,
                    type: deposit.type,
                    deposited_on: formatDate(deposit.deposited_on)
                }
            }),
            credits: data.value.deposits.filter((deposit) => {
                return deposit.type === 'credit'
            }).map((deposit) => {
                return {
                    amount: deposit.amount,
                    client_name: deposit.client_name,
                    receipt: deposit.receipt
                }
            }),
            expenses: data.value.expenses
        }

        const payload = {
            ... { id: data.value.id },
            ... recordedDailySale
        }

        await axios.post('/api/daily-sales/update', payload)
            .then((response) => {

                router.push({ name: 'DailySales' })
                store.dispatch('pushAlert', {
                    type: 'success',
                    message: 'Daily Sale successfully updated'
                })

            }).catch((error) => {

                alert(error)

            }).finally(() => {
                submitting.value = false
            })

    }

    const formContent = ref(null)
    function overflowing() {
        console.log('inside overflowing function: ', formContent.value.scrollHeight)
        return formContent.value.scrollHeight > formContent.value.clientHeight
    }

    const mounted = ref(false)
    onMounted(() => {
        mounted.value = true
    })

</script>

<style scoped>

  #formModal::-webkit-scrollbar {
    width: 16px;
  }
  #formModal::-webkit-scrollbar-corner {
    background: rgba(0,0,0,0);
  }
  #formModal::-webkit-scrollbar-thumb {
    background-color: rgba(116,116,116,0);
    border-radius: 6px;
    border: 4px solid rgba(0,0,0,0);
    background-clip: content-box;
    transition: all;
    transition-duration: 1s;
  }

  #formModal::-webkit-scrollbar-track {
    background-color: rgba(0,0,0,0);
  }

  .form-modal:hover #formModal::-webkit-scrollbar-thumb {
    background-color: rgba(116,116,116,1) !important;
  }

</style>
