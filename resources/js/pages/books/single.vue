<template>

    <ItemCard
        v-if="!loading"
        class=""
        :title="book.title"
        heading
    >

        <template #rows>
            <ItemDescription :description="book.title" label="title" />
            <ItemDescription :description="book.category" label="category" />
            <ItemDescription
                label="balance"
                :description="book.balance + ' copies'"
                class="tabular-nums font-medium"
            />
            <ItemDescription :description="book.code" label="code" />
            <ItemDescription :description="book.type" label="purchase type" />
            <ItemDescription label="supplier">
                <div v-if="book['supplier']">
                    {{ book['supplier']['name'] }}
                </div>
                <p v-else class="text-brand-primary text-xs cursor-pointer">Register a Supplier</p>
            </ItemDescription>
            <ItemDescription :description="new Date(book['created_at']).toDateString()" label="registration date" />
            <ItemDescription :description="new Date(book['updated_at']).toDateString()" label="last updated" />
        </template>

    </ItemCard>

</template>

<script setup>

    import { ref, computed, onMounted } from "vue";
    import ItemCard from "../../components/ItemCard.vue";
    import ItemDescription from "../../components/ItemDescription.vue";
    import { useRoute } from "vue-router"
    import store from "../../store"

    const loading = ref(true)
    const book = computed(() => store.state.book.book)

    async function fetchBook() {

        await store.dispatch('getBook', useRoute().params.id)
            .then()
            .finally(() => {
                console.log('fetchBook finally ', book.value.title)
                loading.value = false
            })

    }

    fetchBook()

</script>

<style scoped>

</style>
