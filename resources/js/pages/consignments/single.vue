<template>

    <div class="grid grid-cols-4 md:grid-cols-1 gap-2">
        <InfoCard class="col-span-1" label="Current balance">
            <span v-if="!! consignmentHistory" class="">{{ consignmentHistory.book.balance }}</span>
            <svg v-else class="animate-rotate my-2" width="19" height="20" viewBox="0 0 19 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.4706 5.94533C18.1987 5.55015 18.4769 4.63079 17.9774 3.96992C17.0157 2.69771 15.7589 1.66786 14.3065 0.974827C12.4006 0.0653761 10.2557 -0.217352 8.1792 0.167163C6.10271 0.551679 4.20125 1.5837 2.74734 3.11531C1.29344 4.64692 0.361732 6.59949 0.0857576 8.69317C-0.190217 10.7869 0.203708 12.9142 1.21108 14.7702C2.21844 16.6262 3.78753 18.1157 5.69346 19.0252C7.59939 19.9346 9.74431 20.2174 11.8208 19.8328C13.4032 19.5398 14.8839 18.8708 16.1424 17.8912C16.7961 17.3824 16.7656 16.4223 16.1648 15.852V15.852C15.5639 15.2816 14.621 15.3229 13.9359 15.7887C13.1386 16.3308 12.2332 16.7055 11.2746 16.883C9.82102 17.1521 8.31958 16.9542 6.98542 16.3176C5.65127 15.681 4.55291 14.6384 3.84775 13.3391C3.1426 12.0399 2.86685 10.5508 3.06003 9.08522C3.25321 7.61964 3.90541 6.25284 4.92314 5.18072C5.94087 4.10859 7.27189 3.38618 8.72544 3.11701C10.179 2.84785 11.6804 3.04576 13.0146 3.68238C13.8945 4.10224 14.6718 4.69872 15.3015 5.42894C15.8424 6.05634 16.7425 6.34051 17.4706 5.94533V5.94533Z" fill="#FF8100"/>
            </svg>
        </InfoCard>
        <InfoCard class="group col-span-1" label="Last settlement date">
            <span v-if="!! lastSettlement" class="w-full">
                <span class="w-full flex items-center justify-between">

                    <label class="text-subtitle scale-75 origin-left" v-if="lastSettlement.transaction_on === 'unsettled'">
                        N/A
                    </label>
                    <template v-else>

                        <div class="gap-2 flex items-center justify-center">
                            <div class="h-12 w-12 text-[1.6rem] rounded-full bg-border-dark text-black flex items-center justify-center">
                                <template v-if="datesInEthiopian">
                                    {{ ethiopianDate(new Date(lastSettlement.transaction_on)).getDate() }}
                                </template>
                                <template v-else>
                                    {{ new Date(lastSettlement.transaction_on).getDate() }}
                                </template>
                            </div>
                            <div class="h-full flex flex-col items-start justify-between">
                                <p class="text-base">
                                    <template v-if="datesInEthiopian">
                                        {{ ethiopianMonths[ethiopianDate(new Date(lastSettlement.transaction_on)).getMonth()] }}
                                    </template>
                                    <template v-else>
                                        {{ months[new Date(lastSettlement.transaction_on).getMonth()] }}
                                    </template>
                                </p>
                                <p class="text-sm text-subtitle">
                                    <template v-if="datesInEthiopian">
                                        {{ ethiopianDate(new Date(lastSettlement.transaction_on)).getFullYear() }}
                                    </template>
                                    <template v-else>
                                        {{ new Date(lastSettlement.transaction_on).getFullYear() }}
                                    </template>
                                </p>
                            </div>
                        </div>

                    </template>

                    <svg @click="toggleDateLocale" :class="[datesInEthiopian ? 'rotate-45' : '-rotate-45']" class="group-hover:opacity-100 opacity-0 transition duration-500 cursor-pointer" width="20" height="14" viewBox="0 0 20 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8 5.99999H5.101L5.102 5.99099C5.23257 5.35162 5.48813 4.74434 5.854 4.20399C6.39845 3.4018 7.16215 2.77315 8.054 2.39299C8.356 2.26499 8.671 2.16699 8.992 2.10199C9.65789 1.96698 10.3441 1.96698 11.01 2.10199C11.967 2.29808 12.8451 2.7714 13.535 3.46299L14.951 2.05099C14.3128 1.41262 13.5578 0.903028 12.727 0.549986C12.3033 0.370615 11.8628 0.233939 11.412 0.141986C10.4818 -0.0470031 9.52316 -0.0470031 8.593 0.141986C8.14185 0.23432 7.70101 0.371329 7.277 0.550986C6.02753 1.08109 4.95793 1.96108 4.197 3.08499C3.68489 3.84284 3.32676 4.69398 3.143 5.58999C3.115 5.72499 3.1 5.86299 3.08 5.99999H0L4 9.99999L8 5.99999ZM12 7.99999H14.899L14.898 8.00799C14.6367 9.28975 13.8812 10.4171 12.795 11.146C12.2548 11.5122 11.6475 11.7677 11.008 11.898C10.3424 12.033 9.65656 12.033 8.991 11.898C8.35163 11.7674 7.74435 11.5119 7.204 11.146C6.93862 10.9665 6.69085 10.7622 6.464 10.536L5.05 11.95C5.68851 12.5882 6.44392 13.0974 7.275 13.45C7.699 13.63 8.142 13.767 8.59 13.858C9.51982 14.0471 10.4782 14.0471 11.408 13.858C13.2005 13.4859 14.7773 12.4294 15.803 10.913C16.3146 10.1557 16.6724 9.30525 16.856 8.40999C16.883 8.27499 16.899 8.13699 16.919 7.99999H20L16 3.99999L12 7.99999Z" fill="#6A727F"/>
                    </svg>

                </span>
            </span>
            <svg v-else class="animate-rotate my-2" width="19" height="20" viewBox="0 0 19 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.4706 5.94533C18.1987 5.55015 18.4769 4.63079 17.9774 3.96992C17.0157 2.69771 15.7589 1.66786 14.3065 0.974827C12.4006 0.0653761 10.2557 -0.217352 8.1792 0.167163C6.10271 0.551679 4.20125 1.5837 2.74734 3.11531C1.29344 4.64692 0.361732 6.59949 0.0857576 8.69317C-0.190217 10.7869 0.203708 12.9142 1.21108 14.7702C2.21844 16.6262 3.78753 18.1157 5.69346 19.0252C7.59939 19.9346 9.74431 20.2174 11.8208 19.8328C13.4032 19.5398 14.8839 18.8708 16.1424 17.8912C16.7961 17.3824 16.7656 16.4223 16.1648 15.852V15.852C15.5639 15.2816 14.621 15.3229 13.9359 15.7887C13.1386 16.3308 12.2332 16.7055 11.2746 16.883C9.82102 17.1521 8.31958 16.9542 6.98542 16.3176C5.65127 15.681 4.55291 14.6384 3.84775 13.3391C3.1426 12.0399 2.86685 10.5508 3.06003 9.08522C3.25321 7.61964 3.90541 6.25284 4.92314 5.18072C5.94087 4.10859 7.27189 3.38618 8.72544 3.11701C10.179 2.84785 11.6804 3.04576 13.0146 3.68238C13.8945 4.10224 14.6718 4.69872 15.3015 5.42894C15.8424 6.05634 16.7425 6.34051 17.4706 5.94533V5.94533Z" fill="#FF8100"/>
            </svg>
        </InfoCard>
        <InfoCard class="col-span-1" label="Payable amount in quantity">
            <span class="flex items-center gap-2" v-if="!! consignmentHistory">
                <span>{{ consignmentHistory['payable'] }}</span>
                <button @click="toggleInfoModalCardView" class="hover:scale-105 hover:bg-black transition-colors transition-transform duration-150 cursor-pointer scale-90 grid place-items-center w-4 h-4 bg-subtitle rounded-full font-extrabold text-2xs text-white">
                    <span class="leading-none mt-0.5">?</span>
                </button>
            </span>
            <svg v-else class="animate-rotate my-2" width="19" height="20" viewBox="0 0 19 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.4706 5.94533C18.1987 5.55015 18.4769 4.63079 17.9774 3.96992C17.0157 2.69771 15.7589 1.66786 14.3065 0.974827C12.4006 0.0653761 10.2557 -0.217352 8.1792 0.167163C6.10271 0.551679 4.20125 1.5837 2.74734 3.11531C1.29344 4.64692 0.361732 6.59949 0.0857576 8.69317C-0.190217 10.7869 0.203708 12.9142 1.21108 14.7702C2.21844 16.6262 3.78753 18.1157 5.69346 19.0252C7.59939 19.9346 9.74431 20.2174 11.8208 19.8328C13.4032 19.5398 14.8839 18.8708 16.1424 17.8912C16.7961 17.3824 16.7656 16.4223 16.1648 15.852V15.852C15.5639 15.2816 14.621 15.3229 13.9359 15.7887C13.1386 16.3308 12.2332 16.7055 11.2746 16.883C9.82102 17.1521 8.31958 16.9542 6.98542 16.3176C5.65127 15.681 4.55291 14.6384 3.84775 13.3391C3.1426 12.0399 2.86685 10.5508 3.06003 9.08522C3.25321 7.61964 3.90541 6.25284 4.92314 5.18072C5.94087 4.10859 7.27189 3.38618 8.72544 3.11701C10.179 2.84785 11.6804 3.04576 13.0146 3.68238C13.8945 4.10224 14.6718 4.69872 15.3015 5.42894C15.8424 6.05634 16.7425 6.34051 17.4706 5.94533V5.94533Z" fill="#FF8100"/>
            </svg>
        </InfoCard>
        <InfoCard class="col-span-1" label="Settled amount in quantity">
            <span v-if="!! consignmentHistory">{{ consignmentHistory['settled'] }}</span>
            <svg v-else class="animate-rotate my-2" width="19" height="20" viewBox="0 0 19 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.4706 5.94533C18.1987 5.55015 18.4769 4.63079 17.9774 3.96992C17.0157 2.69771 15.7589 1.66786 14.3065 0.974827C12.4006 0.0653761 10.2557 -0.217352 8.1792 0.167163C6.10271 0.551679 4.20125 1.5837 2.74734 3.11531C1.29344 4.64692 0.361732 6.59949 0.0857576 8.69317C-0.190217 10.7869 0.203708 12.9142 1.21108 14.7702C2.21844 16.6262 3.78753 18.1157 5.69346 19.0252C7.59939 19.9346 9.74431 20.2174 11.8208 19.8328C13.4032 19.5398 14.8839 18.8708 16.1424 17.8912C16.7961 17.3824 16.7656 16.4223 16.1648 15.852V15.852C15.5639 15.2816 14.621 15.3229 13.9359 15.7887C13.1386 16.3308 12.2332 16.7055 11.2746 16.883C9.82102 17.1521 8.31958 16.9542 6.98542 16.3176C5.65127 15.681 4.55291 14.6384 3.84775 13.3391C3.1426 12.0399 2.86685 10.5508 3.06003 9.08522C3.25321 7.61964 3.90541 6.25284 4.92314 5.18072C5.94087 4.10859 7.27189 3.38618 8.72544 3.11701C10.179 2.84785 11.6804 3.04576 13.0146 3.68238C13.8945 4.10224 14.6718 4.69872 15.3015 5.42894C15.8424 6.05634 16.7425 6.34051 17.4706 5.94533V5.94533Z" fill="#FF8100"/>
            </svg>
        </InfoCard>
    </div>

    <div class="w-full flex sm:flex-col gap-4">

        <div class="sm:w-full w-1/2 flex flex-col gap-6">

            <ItemCard
                v-if="consignmentHistory"
                class="w-full"
                :title="consignmentHistory.book.alternate_title ?? consignmentHistory.book.title"
                subtitle="Consignment record history and supplier information"
            >

                <template #action>

                    <button class="focus:outline-brand-primary sm:focus:outline-none focus:outline-offset-2 h-fit md:w-fit px-6 py-2.5 bg-brand-primary rounded-lg text-white flex justify-between sm:justify-center items-center gap-2 sm:w-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="fill-white"><path d="M18.948 11.112C18.511 7.67 15.563 5 12.004 5c-2.756 0-5.15 1.611-6.243 4.15-2.148.642-3.757 2.67-3.757 4.85 0 2.757 2.243 5 5 5h1v-2h-1c-1.654 0-3-1.346-3-3 0-1.404 1.199-2.757 2.673-3.016l.581-.102.192-.558C8.153 8.273 9.898 7 12.004 7c2.757 0 5 2.243 5 5v1h1c1.103 0 2 .897 2 2s-.897 2-2 2h-2v2h2c2.206 0 4-1.794 4-4a4.008 4.008 0 0 0-3.056-3.888z"></path><path d="M13.004 14v-4h-2v4h-3l4 5 4-5z"></path></svg>
                        <span class="font-medium whitespace-nowrap">
                            Download Stock Record
                        </span>
                    </button>

                </template>

                <template #rows>
                    <ItemDescription ratio="5:7" :description="consignmentHistory.book.author" label="author">
                        <span v-if="consignmentHistory.book.author">{{ consignmentHistory.book.author }}</span>
                        <span v-else class="text-subtitle">N/A</span>
                    </ItemDescription>
                    <ItemDescription ratio="5:7" :description="consignmentHistory.book.category" label="category" />
                    <ItemDescription
                        label="balance"
                        ratio="5:7" :description="consignmentHistory.book.balance + ' copies'"
                        class="tabular-nums font-medium"
                    />
                    <ItemDescription ratio="5:7" :description="consignmentHistory.book.code" label="code" />
                    <ItemDescription ratio="5:7" :description="consignmentHistory.book.type" label="purchase type" />
                    <ItemDescription ratio="5:7" :description="new Date(consignmentHistory.book['created_at']).toDateString()" label="registration date" />
                    <ItemDescription ratio="5:7" :description="new Date(consignmentHistory.book['updated_at']).toDateString()" label="last updated" />
                </template>

            </ItemCard>

            <ItemCard
                v-if="consigner"
                class="w-full"
                title="Supplier information"
                subtitle="Supplier contact information and/or address"
                heading
            >

                <template #action>
                    <button class="focus:outline-brand-primary sm:focus:outline-none focus:outline-offset-2 h-fit md:w-fit px-6 py-2.5 bg-brand-primary rounded-lg text-white flex justify-between sm:justify-center items-center gap-2 sm:w-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="fill-white"><path d="M18.948 11.112C18.511 7.67 15.563 5 12.004 5c-2.756 0-5.15 1.611-6.243 4.15-2.148.642-3.757 2.67-3.757 4.85 0 2.757 2.243 5 5 5h1v-2h-1c-1.654 0-3-1.346-3-3 0-1.404 1.199-2.757 2.673-3.016l.581-.102.192-.558C8.153 8.273 9.898 7 12.004 7c2.757 0 5 2.243 5 5v1h1c1.103 0 2 .897 2 2s-.897 2-2 2h-2v2h2c2.206 0 4-1.794 4-4a4.008 4.008 0 0 0-3.056-3.888z"></path><path d="M13.004 14v-4h-2v4h-3l4 5 4-5z"></path></svg>
                        <span class="font-medium whitespace-nowrap">
                            Update Supplier Information
                        </span>
                    </button>
                </template>

                <template #rows>
                    <ItemDescription ratio="5:7" :description="consigner.name" label="name" />
                    <ItemDescription ratio="5:7" label="tin number">
                        <span v-if="consigner.tin_number" class="font-mono">{{ consigner.tin_number }}</span>
                        <span v-else class="text-subtitle">{{ 'N/A' }}</span>
                    </ItemDescription>
                    <ItemDescription ratio="5:7" label="phone number">
                        <a v-if="consigner.phone" :href="'tel:' + consigner.phone">{{ consigner.phone }}</a>
                        <span v-else class="text-subtitle">N/A</span>
                    </ItemDescription>
                    <ItemDescription class="max-h-fit" ratio="5:7" label="email">
                        <a v-if="consigner.email" :href="'mailto:' + consigner.email">{{ consigner.email }}</a>
                        <span v-else class="text-subtitle">N/A</span>
                    </ItemDescription>
                </template>

            </ItemCard>
            <div
                v-else
                class="bg-wallpaper relative flex flex-col h-fit
             items-center justify-center gap-4 w-full border-2
             border-dashed border-border-light rounded-md p-4"
            >

                <div class="gap-4 flex flex-col items-center justify-center py-8">

                    <svg width="19" height="20" viewBox="0 0 19 20" :class="'fill-brand-primary'" xmlns="http://www.w3.org/2000/svg">
                        <path d="M16.5 1C15.8371 1.00053 15.2015 1.26409 14.7328 1.73282C14.2641 2.20155 14.0005 2.83712 14 3.5C14 3.857 14.078 4.196 14.214 4.505L12.259 6.704C11.5949 6.24558 10.807 6.00004 10 6C9.26 6 8.576 6.216 7.981 6.566L5.707 4.293L5.684 4.316C5.88 3.918 6 3.475 6 3C6 2.40666 5.82405 1.82664 5.49441 1.33329C5.16477 0.839943 4.69623 0.455426 4.14805 0.228363C3.59987 0.00129986 2.99667 -0.0581102 2.41473 0.0576455C1.83279 0.173401 1.29824 0.459124 0.878681 0.878681C0.459124 1.29824 0.173401 1.83279 0.0576455 2.41473C-0.0581102 2.99667 0.00129986 3.59987 0.228363 4.14805C0.455426 4.69623 0.839943 5.16477 1.33329 5.49441C1.82664 5.82405 2.40666 6 3 6C3.475 6 3.917 5.88 4.316 5.684L4.293 5.707L6.567 7.98C6.19964 8.59003 6.00375 9.28791 6 10C6 10.997 6.38 11.899 6.985 12.601L4.408 15.177C4.11917 15.0614 3.81111 15.0013 3.5 15C2.122 15 1 16.121 1 17.5C1 18.879 2.122 20 3.5 20C4.878 20 6 18.879 6 17.5C6 17.179 5.934 16.874 5.823 16.591L8.661 13.753C9.082 13.903 9.528 14 10 14C12.206 14 14 12.206 14 10C14 9.364 13.837 8.771 13.572 8.236L15.689 5.853C15.945 5.941 16.215 6 16.5 6C17.879 6 19 4.879 19 3.5C19 2.121 17.879 1 16.5 1ZM10 12C8.897 12 8 11.103 8 10C8 8.897 8.897 8 10 8C11.103 8 12 8.897 12 10C12 11.103 11.103 12 10 12Z" />
                    </svg>
                    <div class="flex flex-col justify-center items-center gap-0.5">
                        <p class="text-sm font-medium"> No supplier </p>
                        <p class="text-sm text-subtitle"> Register or assign a supplier for this item. </p>
                    </div>

                    <button @click="addSupplier" class="bg-brand-primary flex items-center justify-around gap-2 px-2.5 my-2 rounded-md">

                        <span class="text-white text-2xl font-light align-text-top inline-block pb-1">+</span>
                        <span class="text-white font-medium">New Supplier</span>

                    </button>

                </div>

            </div>

        </div>

        <div class="sm:w-full w-1/2 gap-2 flex flex-col">

            <button @click="openSettlementFormModal" v-if="consignmentHistory && consignmentHistory.history.length > 0" class="hover:bg-white transition-colors duration-500 w-full h-[86px] grid place-items-center p-4 rounded-xl border-dashed border-2 border-border-dark">

                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-subtitle stroke-[1.5] w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>

                <h3 class="text-subtitle font-medium">Make a settlement</h3>

            </button>

            <ul v-if="consignmentHistory" class="w-full gap-2 flex flex-col-reverse justify-end">

                <template v-if="consignmentHistory.history.length > 0">

                    <template v-for="(record, index) in consignmentHistory.history">

                        <label
                            v-if="(index === 0) || (new Date(record.transaction_on).getFullYear() !== new Date(consignmentHistory.history[index-1].transaction_on).getFullYear())"
                            class="text-center font-medium text-subtitle my-2"
                        >
                            {{ new Date(record.transaction_on).getFullYear() }}
                        </label>

                        <li class="flex justify-between items-center p-4 bg-white rounded-xl border border-border-dark">

                            <div class="flex flex-col">

                                <h3 class="font-medium">
                                    <span v-if="record.type==='settlement'">Settled</span>
                                    <span v-if="record.type==='sale'">Sold</span>
                                    <span v-if="record.type==='purchase'">Received</span>
                                    <span class="text-subtitle">&nbsp;{{ record.quantity }} {{ record.quantity > 1 ? 'copies' : 'copy' }}</span>
                                </h3>
                                <h5 class="text-subtitle text-xs">
                                    {{ record.type==='settlement' ? record.receipt : record.invoice }}
                                </h5>
                                <label v-if="record.type==='purchase'" class="w-fit text-2xs leading-none font-semibold text-center whitespace-nowrap text-green-600">purchase</label>
                                <label v-if="record.type==='sale'" class="w-fit text-2xs leading-none font-semibold text-center whitespace-nowrap text-red-600">sale</label>
                                <label v-if="record.type==='settlement'" class="w-fit text-2xs leading-none font-semibold text-center whitespace-nowrap text-yellow-600">settlement</label>

                            </div>
                            <div class="flex flex-col items-center gap-1">

                                <div class="font-medium w-9 h-9 grid place-items-center bg-border-dark rounded-full">
                                    {{ new Date(record.transaction_on).getDate() }}
                                </div>

                                <label class="font-medium text-xs px-4 leading-none">
                                    {{ months[new Date(record.transaction_on).getMonth()].substring(0,3) }}
                                </label>

                            </div>

                        </li>

                    </template>

                    <li class="px-2 py-2 font-medium text-subtitle flex justify-between">
                        <span>
                            Consignment History
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="mx-2 stroke-2 stroke-subtitle w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5L7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5" />
                        </svg>
                    </li>

                </template>

                <li v-else class="min-h-24 flex flex-col items-center justify-center gap-2 font-medium text-subtitle py-8 h-fit">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 my-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12a7.5 7.5 0 0015 0m-15 0a7.5 7.5 0 1115 0m-15 0H3m16.5 0H21m-1.5 0H12m-8.457 3.077l1.41-.513m14.095-5.13l1.41-.513M5.106 17.785l1.15-.964m11.49-9.642l1.149-.964M7.501 19.795l.75-1.3m7.5-12.99l.75-1.3m-6.063 16.658l.26-1.477m2.605-14.772l.26-1.477m0 17.726l-.26-1.477M10.698 4.614l-.26-1.477M16.5 19.794l-.75-1.299M7.5 4.205L12 12m6.894 5.785l-1.149-.964M6.256 7.178l-1.15-.964m15.352 8.864l-1.41-.513M4.954 9.435l-1.41-.514M12.002 12l-3.75 6.495" />
                    </svg>
                    <h3 class="text-black">No consignment history</h3>
                    <h5 class="font-normal sm:w-full w-[24rem] text-center">
                        There has not been any consignment transactions made (purchase, sale or settlement)
                        for this item. Consignment record history will appear here as they are made.
                    </h5>
                </li>

            </ul>

        </div>

    </div>

    <Teleport v-if="toggleNewSupplierForm" to="#top-view">

        <Modal>

            <div ref="newSupplierForm" class="w-[45%] sm:w-full sm:rounded-t-lg sm:overflow-auto sm:max-h-[75%] sm:animate-slide-up-modal animate-scale-up">
                <Form modal class="h-full w-full rounded-b-none" :submit="submitNewSupplier" @cancel="addSupplier(false)" title-layout="contained" title="Assign a supplier" submit="assignSupplier">

                    <div class="w-full sm:flex-col flex gap-8">
                        <TextInput required v-model="newSupplier.name" class="sm:w-full w-3/5" placeholder="Supplier full name" label="Full name" />
                        <TextInput v-model="newSupplier.tin_number" class="sm:w-full w-2/5" placeholder="Supplier tin number" label="Tin number" />
                    </div>
                    <div class="w-full sm:flex-col flex gap-8">
                        <TextInput v-model="newSupplier.phone" class="sm:w-full w-1/2" placeholder="Phone number (09 / +251)" label="Phone number" />
                        <TextInput v-model="newSupplier.email" class="sm:w-full w-1/2" placeholder="Email address" label="Email" />
                    </div>

                    <Collapsable :open="false" collapse-direction="down" :label="['Address information', 'Address information']">

                        <div class="w-full flex sm:flex-col gap-8">
                            <TextInput v-model="newSupplier.address.sub_city" class="sm:w-full w-1/3" required placeholder="Sub-City" label="Sub-City" />
                            <TextInput v-model="newSupplier.address.kebele" class="sm:w-full w-1/3" required placeholder="Kebele" label="Kebele" />
                            <TextInput v-model="newSupplier.address.house_number" class="sm:w-full w-1/3" placeholder="House No." label="House No." />
                        </div>

                        <div class="w-full sm:flex-col flex gap-8">
                            <TextInput v-model="newSupplier.address.country" class="sm:w-full w-1/2" required placeholder="Country" label="Country" />
                            <TextInput v-model="newSupplier.address.city" class="sm:w-full w-1/2" required placeholder="City" label="City" />
                        </div>

                    </Collapsable>

                </Form>
            </div>

        </Modal>

    </Teleport>

    <ConsignmentSettlementForm @close="openSettlementFormModal(false)" :show="viewSettlementForm" v-if="consignmentHistory" :book="{ title: consignmentHistory.book.title, id: consignmentHistory.book.id }" />

    <InfoModalCard :open="viewInfoModalCard" @close="toggleInfoModalCardView" title="Why is the payable amount below zero?">

        <template #description>
            Negative payable amount indicates the amount of items acquired from the consignee but paid in advance before
            sale of the items were made. Settlements to the consignee will only be eligible once the pre-purchases from
            are completed; i.e once the payable amount exceeds above a negative value.
        </template>

    </InfoModalCard>

</template>

<script setup>

    import { ref, onMounted } from "vue"
    import { useRoute } from "vue-router"
    import store from "../../store"
    import router from "../../router";
    import InfoCard from "../../components/InfoCard.vue"
    import ItemCard from "../../components/ItemCard.vue"
    import ItemDescription from "../../components/ItemDescription.vue"
    import Modal from "../../components/Modal.vue"
    import Form from "../../components/Form/Form.vue"
    import TextInput from "../../components/Form/TextInput.vue"
    import Collapsable from "../../components/Collapsable.vue"
    import ConsignmentSettlementForm from "../../pages/new/consignment-settlement.vue"
    import InfoModalCard from "../../components/InfoModalCard.vue"
    import { ethiopianDate, months, ethiopianMonths } from "../../utils";
    import { onClickOutside } from "@vueuse/core";

    const loading = ref(false)
    const consignmentHistory = ref(null)
    const lastSettlement = ref(null)
    const datesInEthiopian = ref(false)

    const viewSettlementForm = ref(false)
    function openSettlementFormModal(open=true) {
        viewSettlementForm.value = open
    }

    function toggleDateLocale() {
        datesInEthiopian.value = ! datesInEthiopian.value
    }

    const toggleNewSupplierForm = ref(false)
    function addSupplier(toggle=true) {
        toggleNewSupplierForm.value = toggle
    }

    const newSupplierForm = ref(null)
    onClickOutside(newSupplierForm, () => {
        toggleNewSupplierForm.value = false
    })

    const consigner = ref(null)
    const newSupplier = ref({
        name: null,
        tin_number: null,
        phone: null,
        email: null,
        address: {
            sub_city: null,
            kebele: null,
            house_number: null,
            country: 'Ethiopia',
            city: 'Addis Ababa'
        },
        books_id: [ useRoute().params.id ],
    })
    function submitNewSupplier() {

        if (newSupplier.value.email === null) {
            delete newSupplier.value.email
        }

        if (newSupplier.value.phone === null) {
            delete newSupplier.value.phone
        }

        axios.post('/api/suppliers', { ...newSupplier.value })
            .then(response => {

                store.dispatch('pushAlert', {
                    type: 'success',
                    message: `Supplier ${response.data.data.name} registered successfully`,
                })
                consigner.value = response.data.data
                addSupplier(false)

            })
            .catch(error => {

                alert(`Error while assigning supplier, ${error.response.data.message}`)

            })

    }

    async function get(parameters) {

        loading.value = true;

        await axios.get('/api/consignments/history', {

            params: parameters

        }).then((response) => {

            consignmentHistory.value = response.data.body
            consigner.value = response.data.body.book.supplier

        }).catch((error) => {

            router.push({ name: 'Books' })
            store.dispatch('pushAlert', {
                type: 'error',
                message: error.response.data.message
            })

        })

        loading.value = false

    }

    const viewInfoModalCard = ref(false)
    function toggleInfoModalCardView() {
        viewInfoModalCard.value = !viewInfoModalCard.value
    }

    onMounted(() => {

        get({ 'book_id': useRoute().params.id }).then(() => {

            lastSettlement.value = consignmentHistory.value
                .history.slice().reverse()
                .find(c => {
                    return c.type === 'settlement'
                }) ||
                { transaction_on: 'unsettled' }

        })

    })

</script>

<style scoped>

</style>
