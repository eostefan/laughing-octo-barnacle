<template>
    <div class="dashboard-transactions">
        <div class="dashboard-transaction-filters flex items-center gap-5 w-full px-4 sticky top-0 h-20 bg-slate-100 p-1">
            <input v-model="keyword" type="search" name="search" id="search" placeholder="Search..." class="bg-white pl-2 h-10 border-1 border-slate-300 rounded flex-1">

            <div class="transaction-checks flex flex-1 gap-5">
                <div class="form-control" v-for="type in filters_list" :key="type">
                    <input type="checkbox" :name="type" :id="type" :value="type" v-model="selected_filters" class="mr-1">
                    <label :for="type" class="cursor-pointer">{{ type }}</label>
                </div>
            </div>

            <div class="transaction-actions">
                <button 
                    @click.prevent="resetFiltering" 
                    class="bg-yellow-200 text-yellow-600 text-sm font-bold py-2 px-4 cursor-pointer rounded-md">
                        reset filters
                </button>
            </div>
        </div>
        <div class="dashboard-transactions-main flex flex-col">
            <template v-if="filtered_transactions_list.length">
                <div v-for="transaction in filtered_transactions_list" :key="transaction.id" 
                        :class="transaction.account_id === null && 'opacity-20'"
                        class="transaction cursor-pointer hover:bg-slate-100 not-last:border-b-1 border-slate-300 transition-all py-2 px-4 flex items-center gap-5">

                    <div class="transactions-box flex flex-col gap-1 items-center">

                        <font-awesome-icon 
                            v-if="transaction.category === 'income'"
                            class="text-green-500 text-2xl" 
                            :icon="['fas', 'arrow-trend-up']" />
    
                        <font-awesome-icon 
                            v-else-if="transaction.category === 'transfer'"
                            class="text-red-500 text-2xl" 
                            :icon="['fas', 'arrow-trend-down']" />
                        
                        <p class="flex items-center gap-2 font-bold px-2 py-1 rounded text-sm" 
                            :class="transaction.category === 'income' ? 'bg-green-500 text-white' : 'bg-red-300 text-red-500'">
                            {{ transaction.category }}
                        </p>
                        <small class="text-white text-center text-xs mb-0.5 w-fit px-2 rounded"
                            :class="transaction.type === 'debit' ? 'bg-blue-500' : 'bg-yellow-500'">

                            {{ transaction.type }}
                        </small>
                        <p class="text-sm text-slate-500 font-bold">{{ utils.formatDate(transaction.created_at) }}</p>
                    </div>

                    <div class="transaction-box flex flex-col">
                        <p class="transaction-ballance text-3xl font-bold text-slate-400">
                            {{ utils.formatPrice(transaction.ammount, transaction.currency) }}
                        </p>
                        <p class="text-sm text-slate-600">{{ transaction.description }}</p>
                    </div>
                </div>
            </template>
            <template v-else-if="store.loading_transactions">
                <p class="text-center font-bold text-slate-500 mt-10">Loading</p>
            </template>
            <template v-else>
                <p class="text-center font-bold text-slate-500 mt-10">No results found</p>
            </template>
        </div>
    </div>
</template>

<script setup>

import { ref, computed } from 'vue'
import utils from '@/utils/utils'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { useAccountStore } from '@/stores/accountStore'

const store = useAccountStore()

const keyword = ref('')
const filters_list = ref(['credit', 'debit', 'income', 'transfer'])
const selected_filters = ref([])

const filtered_transactions_list = computed(() => {
  return store.transactions.filter(transaction => {

    const matchesKeyword = keyword.value
      ? transaction.description.toLowerCase().includes(keyword.value.toLowerCase())
      : true

    const selectedTypes = ['credit', 'debit']
    const selectedCategories = ['income', 'transfer']

    const filtersContainType = selected_filters.value.filter(f => selectedTypes.includes(f))
    const filtersContainCategory = selected_filters.value.filter(f => selectedCategories.includes(f))

    const matchesType = filtersContainType.length
      ? filtersContainType.includes(transaction.type)
      : true

    const matchesCategory = filtersContainCategory.length
      ? filtersContainCategory.includes(transaction.category)
      : true

    return matchesKeyword && matchesType && matchesCategory

  })
})

const resetFiltering = () => {
    keyword.value = ''
    selected_filters.value = []
}

</script>
