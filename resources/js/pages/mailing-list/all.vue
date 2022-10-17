<template>

    <div class="grid grid-cols-2 gap-2">
        <InfoCard class="col-span-1" label="Total Subscribers">
            <span v-if="statistics" class="">{{ formatNumber(statistics.count) ?? 0 }}</span>
            <svg v-else class="animate-rotate my-2" width="19" height="20" viewBox="0 0 19 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.4706 5.94533C18.1987 5.55015 18.4769 4.63079 17.9774 3.96992C17.0157 2.69771 15.7589 1.66786 14.3065 0.974827C12.4006 0.0653761 10.2557 -0.217352 8.1792 0.167163C6.10271 0.551679 4.20125 1.5837 2.74734 3.11531C1.29344 4.64692 0.361732 6.59949 0.0857576 8.69317C-0.190217 10.7869 0.203708 12.9142 1.21108 14.7702C2.21844 16.6262 3.78753 18.1157 5.69346 19.0252C7.59939 19.9346 9.74431 20.2174 11.8208 19.8328C13.4032 19.5398 14.8839 18.8708 16.1424 17.8912C16.7961 17.3824 16.7656 16.4223 16.1648 15.852V15.852C15.5639 15.2816 14.621 15.3229 13.9359 15.7887C13.1386 16.3308 12.2332 16.7055 11.2746 16.883C9.82102 17.1521 8.31958 16.9542 6.98542 16.3176C5.65127 15.681 4.55291 14.6384 3.84775 13.3391C3.1426 12.0399 2.86685 10.5508 3.06003 9.08522C3.25321 7.61964 3.90541 6.25284 4.92314 5.18072C5.94087 4.10859 7.27189 3.38618 8.72544 3.11701C10.179 2.84785 11.6804 3.04576 13.0146 3.68238C13.8945 4.10224 14.6718 4.69872 15.3015 5.42894C15.8424 6.05634 16.7425 6.34051 17.4706 5.94533V5.94533Z" fill="#FF8100"/>
            </svg>
        </InfoCard>
        <InfoCard class="col-span-1" label="New Subscribers (Last 30 days)">
            <span v-if="statistics">{{ formatNumber(statistics['count_last_month']) ?? '0' }}</span>
            <svg v-else class="animate-rotate my-2" width="19" height="20" viewBox="0 0 19 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.4706 5.94533C18.1987 5.55015 18.4769 4.63079 17.9774 3.96992C17.0157 2.69771 15.7589 1.66786 14.3065 0.974827C12.4006 0.0653761 10.2557 -0.217352 8.1792 0.167163C6.10271 0.551679 4.20125 1.5837 2.74734 3.11531C1.29344 4.64692 0.361732 6.59949 0.0857576 8.69317C-0.190217 10.7869 0.203708 12.9142 1.21108 14.7702C2.21844 16.6262 3.78753 18.1157 5.69346 19.0252C7.59939 19.9346 9.74431 20.2174 11.8208 19.8328C13.4032 19.5398 14.8839 18.8708 16.1424 17.8912C16.7961 17.3824 16.7656 16.4223 16.1648 15.852V15.852C15.5639 15.2816 14.621 15.3229 13.9359 15.7887C13.1386 16.3308 12.2332 16.7055 11.2746 16.883C9.82102 17.1521 8.31958 16.9542 6.98542 16.3176C5.65127 15.681 4.55291 14.6384 3.84775 13.3391C3.1426 12.0399 2.86685 10.5508 3.06003 9.08522C3.25321 7.61964 3.90541 6.25284 4.92314 5.18072C5.94087 4.10859 7.27189 3.38618 8.72544 3.11701C10.179 2.84785 11.6804 3.04576 13.0146 3.68238C13.8945 4.10224 14.6718 4.69872 15.3015 5.42894C15.8424 6.05634 16.7425 6.34051 17.4706 5.94533V5.94533Z" fill="#FF8100"/>
            </svg>
        </InfoCard>
    </div>
    <Table
        :key="mailingList"
        :loading="!! ! mailingList"
        title="Mailing List"
        :data="mailingList"
        :center="['phone']"
        :right="[]"
        :sortable="['created_at']"
        :searchable="[]"
        :hideable=false
        :hide="['updated_at']"
        :hide-labels="['updated_at']"
        :actions="true"
        @table-button-click="handleTableButton"
        @edit="handleEdit"
        @delete="handleDelete"
        @new-item="goto('')"
    >

        <template #description>
            <p>
                Subscribers list from 
                <a href="https://ankeboot.com" target="_blank">
                    <i class="text-brand-primary focus:outline-none">ankeboot.com</i>
                </a>
            </p>
        </template>

        <template #rows="data">

            <Cell name="id" :hide="data.hide" class="w-[2%] text-xs font-semibold text-subtitle text-center">{{ data['record']['id'] }} </Cell>
            <LinkCell class="w-[46%]" name="name" :main="true" :hide="data.hide" :value="data['record']['name']" :to="'mailing-list/' + data['record']['id']">
                <RouterLink :to="'mailing-list/' + data['record']['id']">
                    {{ data['record']['name'] }}
                </RouterLink>
            </LinkCell>
            <Cell name="email" :hide="data.hide" class="w-[23%] text-xs font-semibold text-subtitle">
                <template v-if="! data['record']['email']" class="text-center text-opacity-0">N/A</template>
                <a :href="`mailto:${data['record']['email']}`">{{ data['record']['email'] }}</a>
            </Cell>
            <Cell name="phone" :hide="data.hide" class="w-[23%] text-center text-xs font-semibold text-subtitle">
                <template v-if="! data['record']['phone']" class="text-center text-opacity-0">N/A</template>
                <a :href="`tel:${data['record']['phone']}`">{{ data['record']['phone'] }}</a>
            </Cell>
            <DateCell class="w-[2%]" name="created_at" :hide="data.hide" :value="new Date(data['record']['created_at'])" />  

        </template>

    </Table>

    <ConfirmationModal
        :open="deleteModal"
        title="Delete subscriber"
        button-text="Delete subscriber"
        :loading="deleting"
        @cancel="cancelDelete"
        @confirm="attemptDelete(recordToDelete.id)"
    >

        Are you sure you want to delete this subscriber from the mailing list? This action cannot be undone.
        The subscriber will no longer receive any mail from you.    

    </ConfirmationModal>
    
</template>

<script setup>

    import { onMounted, ref } from "vue"
    import axios from "axios";
    import store from "../../store"
    import { formatNumber } from "../../utils";
    import InfoCard from "../../components/InfoCard.vue"
    import Table from "../../components/Table/Table.vue"
    import Cell from "../../components/Table/Cell.vue"
    import LinkCell from "../../components/Table/LinkCell.vue"
    import EnumCell from "../../components/Table/EnumCell.vue"
    import DateCell from "../../components/Table/DateCell.vue"
    import Modal from "../../components/Modal.vue";
    import ConfirmationModal from "../../components/ConfirmationModal.vue";
    
    const statistics = ref(null)
    const mailingList = ref(null)

    async function fetchStatistics() {

        return await axios.get('/api/mailing-list/statistics').then((response) => {

            statistics.value = response.data.data

        })

    }

    async function fetchMailingList() {

        return await axios.get('/api/mailing-list').then((response) => {

            mailingList.value = response.data.data

        })

    }

    function handleTableButton() {

    }

    function handleEdit() {

    }

    const deleting = ref(false)
    async function attemptDelete(id) {

        deleting.value = true
        await axios.post('/api/mailing-list/delete', { 
            id 
        }).then(() => {

            fetchMailingList()
            fetchStatistics()
            store.dispatch('pushAlert', {
                type: 'success',
                message: 'Subscriber deleted'
            })
            deleteModal.value = false

        }).finally(() => {
            deleting.value = false
        })

    }

    function cancelDelete() {

        deleteModal.value = false
        recordToDelete.value = null

    }

    const recordToDelete = ref(null)
    const deleteModal = ref(false)
    function handleDelete(record) {

        recordToDelete.value = record
        deleteModal.value = true

    }

    onMounted(() => {

        console.log('hello')
        fetchMailingList().then(() => console.log(mailingList.value))
        fetchStatistics()

    })

</script>