<template>

    <!-- Toggle // "Title" -->
    <div v-if="chosenDate && chosenDate['is_submitted'] && ! loading" class="relative w-full flex items-center justify-center">
        <Toggle colors="white" class="!bg-white" v-model="toggleState" :labels="['Sales Summary', 'Sales Breakdown']"/>
        <div class="absolute right-2 top-0 bottom-0 flex flex-row-reverse items-center gap-4">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="cursor-pointer stroke-subtitle hover:stroke-red-500 hover:scale-125 hover:stroke-2 w-5 h-5 transition-transform duration-150">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
            </svg>
            <svg @click="updateDailySale" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="stroke-subtitle hover:stroke-black hover:scale-125 cursor-pointer w-5 h-5 transition-transform duration-150">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
            </svg>
        </div>
    </div>

    <!-- List of each transaction -->
    <div v-if="!toggleState && chosenDate && chosenDate['is_submitted'] && ! loading" class="grid grid-cols-8 w-full gap-2">

        <div class="col-span-2 col-start-2 flex flex-col gap-2">

            <p class="text-center font-medium text-subtitle w-full">Sales Receipts</p>

            <template v-for="sales_receipt in chosenDate['sales_receipts']">
                <div class="flex w-full border border-border-light rounded-xl shadow-sm h-fit bg-white/75">

                    <div class="flex flex-col leading-tight items-start p-4 text-lg justify-center grow">
                        <p :class="[sales_receipt['is_manual'] ? '' : '']" class="text-subtitle">{{ sales_receipt['receipt'] }}</p>
                    </div>
                    <div class="flex flex-col gap-0.5 leading-tight items-end p-4 text-lg justify-center grow">
                        <p class="gap-1 w-fit flex items-center justify-center font-medium">
                            <sup class="text-xs">ETB</sup>
                            {{ formatPrice(sales_receipt['amount']) }}
                        </p>
                        <p v-if="sales_receipt['is_manual']" class="text-xs px-2 py-0.5 border border-brand-primary text-brand-primary rounded-full">manual receipt(s)</p>
                    </div>

                </div>
            </template>

        </div>

        <div class="col-span-2 flex flex-col gap-2">

            <p class="text-center font-medium text-subtitle w-full">Deposits</p>
            <template v-for="deposit in chosenDate['deposits']">
                <div class="flex w-full border border-border-light rounded-xl shadow-sm h-fit bg-white/75">

                    <div class="flex flex-col leading-tight items-start p-4 text-lg justify-center grow">
                        <p class="font-medium text-xs">{{ deposit['type'] }}</p>
                        <p v-if="!! ! deposit['credit']" class="text-subtitle text-xs">{{ new Date(deposit['deposited_on']).toDateString() }}</p>
                        <template v-else>
                            <p class="text-subtitle text-xs">{{ deposit['credit']['client_name'] }}</p>
                            <p class="text-subtitle text-xs">{{ deposit['credit']['receipt'] }}</p>
                        </template>
                    </div>
                    <div class="p-4 w-fit h-full flex flex-col leading-tight items-end justify-center text-lg font-medium">
                        <div class="flex items-center justify-center gap-1">
                            <sup class="text-xs">ETB</sup>
                            {{ formatPrice(deposit['amount']) }}
                        </div>
                        <template v-if="!! deposit['credit']">
                            <p v-if="deposit['credit']['submitted_on']" class="text-xs px-2 py-0.5 bg-green-600 text-white rounded-full">paid</p>
                            <p v-else class="text-xs px-2 py-0.5 bg-red-500 text-white rounded-full">unpaid</p>
                        </template>
                    </div>

                </div>
            </template>

        </div>

        <div v-if="chosenDate['expenses'].length > 0" class="col-span-2 flex flex-col gap-2">

            <p class="text-center font-medium text-subtitle w-full">Expenses</p>
            <template v-if="chosenDate['expenses'].length > 0" v-for="expense in chosenDate['expenses']">
                <div class="flex w-full border border-border-light rounded-xl shadow-sm h-fit bg-white/75">

                    <div class="flex flex-col leading-tight items-start p-4 text-lg justify-center grow">
                        <p class="font-medium">
                            <span class="text-subtitle">PV</span>
                            {{ expense['receipt'] }}
                        </p>
                        <p class="text-subtitle text-xs">{{ expense['description'] }}</p>
                    </div>
                    <p class="p-4 gap-1 w-fit h-full flex items-center justify-center text-lg font-medium">
                        <sup class="text-xs">ETB</sup>
                        {{ formatPrice(expense['amount']) }}
                    </p>

                </div>
            </template>
            <div v-else class="flex justify-center items-center text-subtitle text-xs h-12">
                No expenses reported
            </div>

        </div>

    </div>

    <!-- Summary of the Daily Sale -->
    <div v-if="toggleState && chosenDate && chosenDate['is_submitted'] && ! loading" class="w-full flex md:flex-col sm:gap-4 items-start gap-2">

        <ItemCard
            class=""
            :title="'Daily Sales Report'"
            :heading="false"
        >

            <template #rows>
                <ItemDescription ratio="5:7" label="Status">
                    <label class="px-2 py-0.5 rounded-full bg-brand-secondary text-brand-primary text-xs">
                        {{ chosenDate['is_submitted'] ? 'submitted' : 'not submitted' }}
                    </label>
                </ItemDescription>
                <ItemDescription ratio="5:7" :description="new Date(chosenDate['date_of']).toDateString()" label="Daily Sales date" />
                <ItemDescription
                    ratio="5:7"
                    label="Submission date"
                >

                    <label v-if="chosenDate['is_submitted']">
                        {{
                            new Date(chosenDate['updated_at']).toDateString() +
                            ' ' +
                            new Date(chosenDate['updated_at']).getHours() +
                            ':' +
                            formatNumberToTwoIntegerPlaces(new Date(chosenDate['updated_at']).getMinutes()) +
                            ''
                        }}
                    </label>
                    <label v-else class="px-2 py-0.5 rounded-full bg-brand-secondary text-brand-primary text-xs">
                        {{  'not submitted' }}
                    </label>

                </ItemDescription>
                <ItemDescription ratio="5:7" label="Cashier on call">
                    <label v-if="chosenDate['is_submitted']">{{ chosenDate['cashier'] }}</label>
                    <label v-else class="px-2 py-0.5 rounded-full bg-brand-secondary text-brand-primary text-xs">
                        {{  'not submitted' }}
                    </label>
                </ItemDescription>
                <ItemDescription ratio="5:7" label="Sold">
                    <label class="font-medium" v-if="chosenDate['transactions'].length > 0">
                        {{ chosenDate['transactions'].length + ' items' }}
                        <span class="text-xs text-subtitle">
                            ({{
                                chosenDate['transactions']
                                    .reduce((prev, next) => {
                                        return prev + next['quantity']
                                    }, 0)
                            }} copies)
                        </span>
                    </label>
                    <button v-else class="py-0.5 rounded-full text-brand-primary text-xs">
                        {{  'Insert transactions now' }}
                    </button>
                </ItemDescription>
            </template>

        </ItemCard>
        <ItemCard
            class=""
            :title="'Daily Sales Report'"
            :heading="false"
        >

            <template #rows>
                <ItemDescription ratio="5:7" label="Deposit receipts">
                    <label class="font-medium">
                        ETB {{ formatPrice(parseFloat(chosenDate['deposits'].reduce((old, value) => {  return old + value['amount'] }, 0))) }}
                        <span class="text-xs text-subtitle">({{ chosenDate['deposits'].length }})</span>
                    </label>
                </ItemDescription>
                <ItemDescription ratio="5:7" label="Expenses from daily sale">
                    <label class="font-medium">
                        ETB {{ formatPrice(parseFloat(chosenDate['expenses'].reduce((old, value) => {  return old + value['amount'] }, 0))) }}
                        <span class="text-xs text-subtitle">({{ chosenDate['expenses'].length }})</span>
                    </label>
                </ItemDescription>
                <ItemDescription ratio="5:7" label="Sales receipts">
                    <label class="font-medium">
                        ETB {{ formatPrice(parseFloat(chosenDate['sales_receipts'].reduce((old, value) => {  return old + value['amount'] }, 0))) }}
                        <span class="text-xs text-subtitle">({{ chosenDate['sales_receipts'].length }})</span>
                    </label>
                </ItemDescription>
                <ItemDescription ratio="5:7" label="Difference">
                    <label :class="chosenDate['difference'] > 5 ? 'text-red-500' : ''" class="font-medium">
                        <span v-if="chosenDate['difference'] > 0">(+)</span>
                        <span v-else-if="chosenDate">(-)</span>
                        {{ formatPrice(Math.abs(chosenDate['difference'])) }}
                        ETB
                    </label>
                </ItemDescription>
                <ItemDescription ratio="5:7" label="Remark">
                    <p v-if="chosenDate['is_submitted']" class="py-3">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque dui dolor, ornare
                        a iaculis congue, posuere vel mi. Nunc non dui urna. Cras malesuada, tortor.
                    </p>
                    <label v-else class="px-2 py-0.5 rounded-full bg-brand-secondary text-brand-primary text-xs">
                        {{  'not submitted' }}
                    </label>
                </ItemDescription>
            </template>

        </ItemCard>

    </div>

    <Form title-layout="contained" :loading="submitting" v-if="chosenDate && !chosenDate['is_submitted'] && ! loading" :submit="submitDailySale" title="Submit Daily Sale">

        <template #subtitle>
            Daily sales record for
            <span class="text-brand-primary">
                    {{ new Date(chosenDate['date_of']).toDateString() }}
                </span>.
            Record the <b><i>sales receipts</i></b>, <b><i>deposits</i></b>, <b><i>credit sales</i></b>,
            <b><i>expenses</i></b> and other information required for the Daily Sale report.
        </template>

        <div class="flex sm:flex-col items-center justify-center w-full gap-2">
            <TextInput class="w-2/5 sm:w-full" disabled label="Date of Sale" v-model="newDailySale.date" placeholder="Name of cashier on call" />
            <TextInput class="w-3/5 sm:w-full" required label="Cashier" v-model="newDailySale.cashier" placeholder="Name of cashier on call" />
        </div>

        <!-- Sale receipts -->
        <div class="gap-6 flex flex-col">

            <div class="flex items-center justify-start w-full gap-2 px-2">
                <p class="text-xs text-subtitle font-medium whitespace-nowrap">Sales receipts</p>
                <div class="border-b border-border-light w-full py-1 mb-2 w-full m-auto"></div>
            </div>

            <template v-for="(sale, index) in newDailySale['sales']" :key="index">

                <div class="relative flex sm:flex-col items-start justify-center w-full gap-2">
                    <SwitchInput v-if="index !== 0" v-model="sale.is_manual" label-location="top" class="px-2" label="Manual receipt" />
                    <TextInput
                        class="w-2/5 sm:w-full"
                        required
                        :label="
                            index === 0 ?
                            'Z-Report amount (ETB)' :
                            'Sale amount (ETB)'
                        "
                        v-model="sale.amount"
                        placeholder="Total sale amount in ETB"
                    />
                    <TextInput class="w-3/5 sm:w-full" required label="Receipt No." v-model="sale.receipt" :placeholder="index === 0 ? 'Receipt of Z-Report (Z:****)' : 'Sale receipt no.'" />
                    <button type="button" @click="removeSalesReceipt(index)" v-if="index !== 0" class="sm:absolute sm:top-0 sm:mt-6 sm:order-first self-end rounded-md min-h-[2.5rem] min-w-[2.5rem] flex items-center justify-center">
                        <svg width="15" height="17" viewBox="0 0 15 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1.6 14.7333C1.6 15.1577 1.76857 15.5646 2.06863 15.8647C2.36869 16.1647 2.77565 16.3333 3.2 16.3333H11.2C11.6243 16.3333 12.0313 16.1647 12.3314 15.8647C12.6314 15.5646 12.8 15.1577 12.8 14.7333V5.13331H14.4V3.53331H11.2V1.93331C11.2 1.50897 11.0314 1.102 10.7314 0.801942C10.4313 0.501884 10.0243 0.333313 9.6 0.333313H4.8C4.37565 0.333313 3.96869 0.501884 3.66863 0.801942C3.36857 1.102 3.2 1.50897 3.2 1.93331V3.53331H0V5.13331H1.6V14.7333ZM4.8 1.93331H9.6V3.53331H4.8V1.93331ZM4 5.13331H11.2V14.7333H3.2V5.13331H4Z" fill="#FF8100"/>
                            <path d="M4.80078 6.73334H6.40078V13.1333H4.80078V6.73334ZM8.00078 6.73334H9.60078V13.1333H8.00078V6.73334Z" fill="#FF8100"/>
                        </svg>
                    </button>
                </div>

            </template>

            <button type="button" @click="addSalesReceipt" class="-mt-2 px-4 py-2 gap-2 rounded-full bg-brand-secondary w-fit m-auto flex items-center justify-center">
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
                <div class="border-b border-border-light w-full py-1 mb-2 m-auto"></div>
            </div>

            <template v-for="(deposit, index) in newDailySale['deposits']" :key="index">

                <div class="flex flex-col w-full gap-2">
                    <div class="flex sm:flex-col items-start justify-start w-full gap-2">
                        <button type="button" @click="removeDeposit(index)" v-if="index !== 0" class="self-end rounded-md h-10 w-10 flex items-center justify-center">
                            <svg width="15" height="17" viewBox="0 0 15 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M1.6 14.7333C1.6 15.1577 1.76857 15.5646 2.06863 15.8647C2.36869 16.1647 2.77565 16.3333 3.2 16.3333H11.2C11.6243 16.3333 12.0313 16.1647 12.3314 15.8647C12.6314 15.5646 12.8 15.1577 12.8 14.7333V5.13331H14.4V3.53331H11.2V1.93331C11.2 1.50897 11.0314 1.102 10.7314 0.801942C10.4313 0.501884 10.0243 0.333313 9.6 0.333313H4.8C4.37565 0.333313 3.96869 0.501884 3.66863 0.801942C3.36857 1.102 3.2 1.50897 3.2 1.93331V3.53331H0V5.13331H1.6V14.7333ZM4.8 1.93331H9.6V3.53331H4.8V1.93331ZM4 5.13331H11.2V14.7333H3.2V5.13331H4Z" fill="#FF8100"/>
                                <path d="M4.80078 6.73334H6.40078V13.1333H4.80078V6.73334ZM8.00078 6.73334H9.60078V13.1333H8.00078V6.73334Z" fill="#FF8100"/>
                            </svg>
                        </button>
                        <TextInput class="w-1/3 sm:w-full" required label="Deposit Amount" v-model="deposit.amount" placeholder="Total deposit amount in ETB" />
                        <DatePicker label="Deposit date" required v-model="deposit.deposited_on" class="w-1/3 sm:w-full" />
                        <Dropdown :hide-label-on-mobile=false required v-model="deposit.type" :list="['cash', 'withholding', 'cheque', 'transfer', 'credit', 'card']" placeholder="Choose the type of Deposit" label-direction="top" label="Deposit type" class="!w-1/3 sm:!w-full h-16" />
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
        <Collapsable :open="false" collapse-direction="down" @open="toggleExpenses" :label="['Expenses', 'Expenses']">

            <div class="gap-6 flex flex-col">

                <template v-for="(expense, index) in newDailySale['expenses']" :key="index">

                    <div class="flex sm:flex-col items-start justify-start w-full gap-2">
                        <button type="button" @click="removeExpense(index)" v-if="index !== 0" class="self-end rounded-md h-10 w-10 flex items-center justify-center">
                            <svg width="15" height="17" viewBox="0 0 15 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M1.6 14.7333C1.6 15.1577 1.76857 15.5646 2.06863 15.8647C2.36869 16.1647 2.77565 16.3333 3.2 16.3333H11.2C11.6243 16.3333 12.0313 16.1647 12.3314 15.8647C12.6314 15.5646 12.8 15.1577 12.8 14.7333V5.13331H14.4V3.53331H11.2V1.93331C11.2 1.50897 11.0314 1.102 10.7314 0.801942C10.4313 0.501884 10.0243 0.333313 9.6 0.333313H4.8C4.37565 0.333313 3.96869 0.501884 3.66863 0.801942C3.36857 1.102 3.2 1.50897 3.2 1.93331V3.53331H0V5.13331H1.6V14.7333ZM4.8 1.93331H9.6V3.53331H4.8V1.93331ZM4 5.13331H11.2V14.7333H3.2V5.13331H4Z" fill="#FF8100"/>
                                <path d="M4.80078 6.73334H6.40078V13.1333H4.80078V6.73334ZM8.00078 6.73334H9.60078V13.1333H8.00078V6.73334Z" fill="#FF8100"/>
                            </svg>
                        </button>
                        <TextInput class="w-1/3 sm:w-full" required label="Expense Amount" v-model="expense.amount" placeholder="Total expense amount in ETB" />
                        <TextInput class="w-2/3 sm:w-full" required label="Expense Receipt" v-model="expense.receipt" placeholder="Payment voucher receipt issued for expense (PV****)" />
                    </div>
                    <div class="flex items-start justify-start w-full gap-2">
                        <div v-if="index !== 0" class="self-end rounded-md h-10 sm:hidden min-w-[2.5rem] flex items-center justify-center" />
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

        <div class="gap-6 flex flex-col"></div>

    </Form>

    <!-- Daily sale items list -->
    <template v-if="toggleState && chosenDate && ! loading">

        <form v-if="! chosenDate['transactions'].length > 0" @submit.prevent="insertDailyTransaction" title="Insert Daily Transaction" submit="" class="sm:flex sm:flex-col w-full grid grid-cols-12 gap-2">
            <div class="col-span-4 py-2 flex flex-col gap-1">
                <h1 class="text-base">Insert multiple Book records from CSV</h1>
                <slot name="subtitle">
                    <h2 class="text-subtitle">
                        Upload an excel or comma separated value (CSV) file
                        to record the sale transactions for
                        <span class="text-brand-primary">
                            {{ new Date(chosenDate['date_of']).toDateString() }}
                        </span>.
                        Make sure to include all the necessary columns and follow the
                        template to insert the data successfully.
                    </h2>
                </slot>
            </div>
            <div class="flex flex-col col-span-8 items-end gap-6">
                <FileInput class="w-full " v-model="uploadFile" />
                <div class="w-full flex justify-end items-center px-6">
                    <button type="submit" class="px-4 bg-brand-primary w-fit text-white rounded-md py-2">
                        Upload
                    </button>
                </div>
            </div>
        </form>

        <Table
            v-else
            title="Items sold"
            :data="transactions"
            :center="['code', 'quantity']"
            :right="[]"
            :sortable="['sold', 'book_type', 'book_code', 'title']"
            :searchable="[]"
            :hideable="false"
            :hide="['author']"
            :hide-labels="['stock_card']"
            :actions="false"
        >

            <template #description>
                <p>
                    List of items sold on
                    <span class="text-brand-primary">{{ new Date(chosenDate['date_of']).toDateString() }}</span>
                </p>
            </template>

            <template #rows="data">

                <Cell name="book_code" :hide="data.hide" class="w-[2%] text-xs font-semibold text-subtitle text-center">{{ data['record']['book_code'].toLowerCase() }} </Cell>
                <Cell class="w-[90%]" name="title" :main="true" :hide="data.hide">{{ data['record'].title }} </Cell>
                <Cell class="w-[2%]" name="category" :hide="data.hide">{{ data['record']['category'] }} </Cell>
                <Cell class="w-[2%]" name="author" :hide="data.hide">{{ data['record']['author'] }} </Cell>
                <Cell name="sold" :hide="data.hide" class="w-[2%] text-xs font-semibold text-subtitle text-center">{{ data['record']['sold'] }} </Cell>
                <EnumCell class="w-[2%]" name="book_type" :hide="data.hide" :colors="['lime', 'stone']" :options="['consignment', 'cash']" :value="data['record']['book_type']" />
                <LinkCell class="w-[2%] text-xs px-6 text-brand-primary" name="stock_card" :hide="data.hide" :to="'books'" value="books">
                    <RouterLink :to="{ name: 'Book', params: { id: data['record']['stock_card'] } }">
                        {{ 'Stock card' }}
                    </RouterLink>
                </LinkCell>

            </template>

        </Table>

    </template>

    <!-- Update Modal -->
    <UpdateDailySaleModal @close="closeUpdateModal" :daily-sale="chosenDate" v-if="updateFlag" />

    <!-- Loading indicator -->
    <div class="flex w-full items-center justify-center min-h-[6rem] gap-2" v-if="loading">

        <div class="flex gap-2 bg-white/75 rounded-full px-4 py-2">
            <svg class="animate-rotate" width="19" height="20" viewBox="0 0 19 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.4706 5.94533C18.1987 5.55015 18.4769 4.63079 17.9774 3.96992C17.0157 2.69771 15.7589 1.66786 14.3065 0.974827C12.4006 0.0653761 10.2557 -0.217352 8.1792 0.167163C6.10271 0.551679 4.20125 1.5837 2.74734 3.11531C1.29344 4.64692 0.361732 6.59949 0.0857576 8.69317C-0.190217 10.7869 0.203708 12.9142 1.21108 14.7702C2.21844 16.6262 3.78753 18.1157 5.69346 19.0252C7.59939 19.9346 9.74431 20.2174 11.8208 19.8328C13.4032 19.5398 14.8839 18.8708 16.1424 17.8912C16.7961 17.3824 16.7656 16.4223 16.1648 15.852V15.852C15.5639 15.2816 14.621 15.3229 13.9359 15.7887C13.1386 16.3308 12.2332 16.7055 11.2746 16.883C9.82102 17.1521 8.31958 16.9542 6.98542 16.3176C5.65127 15.681 4.55291 14.6384 3.84775 13.3391C3.1426 12.0399 2.86685 10.5508 3.06003 9.08522C3.25321 7.61964 3.90541 6.25284 4.92314 5.18072C5.94087 4.10859 7.27189 3.38618 8.72544 3.11701C10.179 2.84785 11.6804 3.04576 13.0146 3.68238C13.8945 4.10224 14.6718 4.69872 15.3015 5.42894C15.8424 6.05634 16.7425 6.34051 17.4706 5.94533V5.94533Z" fill="#FF8100"/>
            </svg>

            <span class="text-subtitle">Loading</span>
        </div>

    </div>

</template>

<script setup>

    import { onMounted, ref, watch, computed } from "vue";
    import router from "../../router"
    import { useRoute, onBeforeRouteUpdate } from "vue-router";
    import ItemCard from "../../components/ItemCard.vue"
    import ItemDescription from "../../components/ItemDescription.vue"
    import Toggle from "../../components/Toggle.vue"
    import Form from "../../components/Form/Form.vue"
    import FileInput from "../../components/Form/FileInput.vue"
    import SwitchInput from "../../components/Form/SwitchInput.vue"
    import Dropdown from "../../components/Dropdown.vue"
    import DatePicker from "../../components/Form/DatePicker.vue"
    import Collapsable from "../../components/Collapsable.vue"
    import TextAreaInput from "../../components/Form/TextAreaInput.vue"
    import TextInput from "../../components/Form/TextInput.vue";
    import Table from "../../components/Table/Table.vue"
    import Cell from "../../components/Table/Cell.vue"
    import EnumCell from "../../components/Table/EnumCell.vue"
    import LinkCell from "../../components/Table/LinkCell.vue"
    import UpdateDailySaleModal from "../update/daily-sales.vue"
    import { formatDate, formatPrice, formatNumberToTwoIntegerPlaces } from "../../utils";
    import store from "../../store";

    const toggleState = ref(true)
    const chosenDate = ref(null)
    const loading = ref(false)
    const submitting = ref(false)
    const transactions = ref([])
    const updateFlag = ref(false)

    function updateDailySale() {
        updateFlag.value = true
    }

    function closeUpdateModal() {
        updateFlag.value = false
    }

    async function get(parameters) {

        loading.value = true;

        await axios.get('/api/daily-sales', {

            params: parameters

        }).then((response) => {

            console.log('get daily sale ', response.data.data)
            chosenDate.value = response.data.data

            if (! chosenDate.value['is_submitted']) {

                newDailySale.value.date = new Date(chosenDate.value['date_of']).toDateString()

            }

            // console.log({ transactions: chosenDate.value.transactions })

            transactions.value = chosenDate.value.transactions.map((transaction) => {

                return {
                    book_code: transaction.book.code,
                    title: transaction.book.title,
                    category: transaction.book.category,
                    author: transaction.book.author,
                    sold: transaction.quantity,
                    book_type: transaction.book.type,
                    stock_card: transaction.book.id
                }

            })


        }).catch((error) => {

            alert(error)

        })

        loading.value = false

    }

    onBeforeRouteUpdate((to, from, next) => {

        get({ id: to.params.id })
        next()

    })

    onMounted(() => {

        console.log(useRoute().params.id)
        get({ id: useRoute().params.id })

    })

    const newDailySale = ref({
        cashier: '',
        sales: [
            {
                amount: '',
                receipt: '',
                is_manual: false
            }
        ],
        deposits: [
            {
                amount: '',
                type: '',
                deposited_on: {
                    date: '',
                    month: '',
                    year: ''
                }
            }
        ],
        expenses: []
    })
    async function submitDailySale() {

        submitting.value = true

        const recordedDailySale = {
            update_submitted: false,
            cashier: newDailySale.value.cashier,
            sales_receipts: newDailySale.value.sales,
            deposits: newDailySale.value.deposits.filter((deposit) => {
                return deposit.type !== 'credit'
            }).map((deposit) => {
                return {
                    amount: deposit.amount,
                    type: deposit.type,
                    deposited_on: formatDate(deposit.deposited_on)
                }
            }),
            credits: newDailySale.value.deposits.filter((deposit) => {
                return deposit.type === 'credit'
            }).map((deposit) => {
                return {
                    amount: deposit.amount,
                    client_name: deposit.client_name,
                    receipt: deposit.receipt
                }
            }),
            expenses: newDailySale.value.expenses
        }

        const payload = {
            ... { id: chosenDate.value.id },
            ... recordedDailySale
        }

        console.log('payload', payload)


        await axios.post('/api/daily-sales/update', payload)
            .then((response) => {

                router.push({ name: 'DailySales' })
                store.dispatch('pushAlert', {
                    type: 'success',
                    message: 'Daily Sale successfully updated'
                })

            }).catch((error) => {

                alert(error.response.data.message)

            }).finally(() => {
                submitting.value = false
            })

    }

    function addSalesReceipt() {

        newDailySale.value.sales.push({
            amount: '',
            receipt: '',
            is_manual: false
        })

    }

    function addExpense() {

        newDailySale.value.expenses.push({
            receipt: '',
            amount: '',
            description: ''
        })

    }

    function addDeposit() {

        newDailySale.value.deposits.push({
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

    function removeExpense(index) {

        newDailySale.value.expenses.splice(index, 1)

    }

    function removeDeposit(index) {

        newDailySale.value.deposits.splice(index, 1)

    }

    function removeSalesReceipt(index) {

        newDailySale.value.sales.splice(index, 1)

    }

    function toggleExpenses(opened) {

        if (opened) {
            newDailySale.value.expenses.push({
                receipt: '',
                amount: '',
                description: ''
            })

        } else {
            newDailySale.value.expenses = []
        }

    }

    const uploadFile = ref(null)
    async function insertDailyTransaction() {

        await axios.post('/api/daily-sales/csv', {
            file: uploadFile.value,
            daily_sale_id: chosenDate.value.id
        }, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        }).then(() => {

            router.push({ name: 'DailySales' })
            store.dispatch('pushAlert', {
                type: 'success',
                message: 'Daily Sale transactions added'
            })

        }).catch((error) => {

            alert(error.response.data.data)

        })

    }

</script>

<style scoped></style>
