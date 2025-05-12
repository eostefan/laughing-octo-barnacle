<template>
    <div class="dashboard-accounts overflow-hidden">
        <div class="dashboard-accounts-header border-b-1 border-b-slate-300 pb-2 mb-6">
            <h2 class="text-slate-500 font-bold">Active accounts</h2>
        </div>

        <div class="dashboard-accounts-manage mb-4">
            <button @click="toggleNewAccountModal" class="bg-green-200 text-green-700 px-4 py-1 rounded cursor-pointer">
                open a new account
            </button>
        </div>

        <div class="dashboard-accounts-main flex justify-between flex-wrap">
            <template v-for="account in store.accounts">
                <div :key="account.id" v-if="!account.deleted_at" class="account flex flex-col justify-between border-1 border-slate-300 p-2 rounded-md hover:bg-slate-100 transition-all ease-in w-1/2 mb-4">
                    <div class="account-wrap flex justify-between px-2 mb-4">
                        <div class="account-main">
                            <p class="text-2xl font-bold text-slate-500">{{ account.type }}</p>
                            <p class="account-ballance text-2xl font-bold text-slate-400">
                                {{ account.balance && utils.formatPrice(account.balance, account.currency) }}
                            </p>
                            <small class="text-slate-500">{{ account.institution }}</small>
                        </div>

                        <font-awesome-icon class="text-6xl text-slate-300" :icon="['fas', 'credit-card']" />
                    </div>
    
                    <div class="card-actions flex justify-between items-center rounded gap-5 border-1 border-slate-200 h-14 p-1 px-4 w-full bg-slate-100">
                        <div class="card-actions-wrap flex gap-4">
                            <button @click="toggleAddIncomeModal(account)" class="cursor-pointer text-green-500 font-bold text-sm border-1 rounded px-2 hover:bg-green-200 hover:text-green-500">
                                <font-awesome-icon :icon="['fas', 'arrow-trend-up']" />
                                add income
                            </button>
                            <button @click="toggleMakeTransferModal(account)" v-if="account.balance > 0" class="cursor-pointer text-red-500 font-bold text-sm border-1 rounded px-2 hover:bg-red-200 hover:text-red-500">
                                <font-awesome-icon :icon="['fas', 'arrow-trend-down']" />
                                make transfer
                            </button>
                        </div>
                        <button @click="toggleDeleteWarn(account.id)" class="cursor-pointer text-red-400 font-bold text-sm">
                            <font-awesome-icon :icon="['fas', 'trash-can']" />
                        </button>
                    </div>
                </div>
            </template>
        </div>

        <Transition name="fade">
            <modal-component :toggle_variable="show_delete_warn" :toggle_action="toggleDeleteWarn">
                <h3 class="text-center text-xl font-medium mb-6 text-slate-600">
                    Are you sure you want to delete this account?<br> 
                    <small>( transfer history will not be deleted )</small>
                </h3>
    
                <div class="actions flex items-center justify-center gap-8">
                    <button @click="doDeleteAccount" :disabled="is_loading_transaction" class="bg-red-200 text-red-600 px-4 py-1 cursor-pointer rounded disabled:opacity-50">Delete</button>
                    <button @click="toggleDeleteWarn" class="bg-gray-200 text-gray-600 px-4 py-1 cursor-pointer rounded">Cancel</button>
                </div>
            </modal-component>
        </Transition>

        <Transition name="fade">
            <modal-component :toggle_variable="new_account_modal" :toggle_action="toggleNewAccountModal">
                <h3 class="text-center text-xl font-medium mb-6 text-slate-600">
                    Lets open a new account
                </h3>

                <form @submit.prevent="doSaveNewAccount" class="flex flex-col gap-2">
                    <div class="form-control">
                        <label for="type" class="text-sm font-medium text-slate-600">Select account type</label>
                         <select name="type" id="type" class="w-full border-1 border-slate-300 rounded h-9" v-model="accountFormData.type">
                            <option value="debit">Debit</option>
                            <option value="credit">Credit</option>
                            <option value="savings">Savings</option>
                         </select>
                        <small v-if="accountFormData.errors.type" class="text-red-600">{{ accountFormData.errors.type }}</small>
                    </div>

                    <div class="form-control">
                        <label for="institution" class="text-sm font-medium text-slate-600">Institution</label>
                        <input type="text" id="institution" name="institution" class="w-full border-1 border-slate-300 rounded h-9" v-model="accountFormData.institution">
                        <small v-if="accountFormData.errors.institution" class="text-red-600">{{ accountFormData.errors.institution }}</small>
                    </div>

                    <div class="form-control">
                        <label for="ballance" class="text-sm font-medium text-slate-600">Balance</label>
                        <input type="number" id="ballance" name="ballance" class="w-full border-1 border-slate-300 rounded h-9" v-model="accountFormData.balance">
                        <small v-if="accountFormData.errors.balance" class="text-red-600">{{ accountFormData.errors.balance }}</small>
                    </div>

                    <div class="form-control">
                        <label for="type" class="text-sm font-medium text-slate-600">Currency</label>
                         <select name="type" id="type" class="w-full border-1 border-slate-300 rounded h-9" v-model="accountFormData.currency">
                            <option value="EUR">EURO</option>
                            <option value="USD">DOLLARS</option>
                         </select>
                        <small v-if="accountFormData.errors.currency" class="text-red-600">{{ accountFormData.errors.currency }}</small>
                    </div>

                    <div class="form-control flex gap-2 mt-6">
                        <button type="submit" :disabled="is_loading_transaction" class="bg-red-200 text-red-600 px-4 py-1 cursor-pointer rounded disabled:opacity-50">Submit</button>
                        <button type="reset" @click.prevent="toggleNewAccountModal" class="bg-gray-200 text-gray-600 px-4 py-1 cursor-pointer rounded">Cancel</button>
                    </div>
                </form>
            </modal-component>
        </Transition>

        <Transition name="fade">
            <modal-component :toggle_variable="show_add_income" :toggle_action="toggleAddIncomeModal">
                <div class="modal-header mb-6">
                    <h3>
                        Lets add some money! <br> 
                        <small>pretend to get rich quick</small>
                    </h3>
                </div>

                <div class="modal-main">

                    <form @submit.prevent="addTransaction" class="flex flex-col gap-2">
                        <div class="form-control">
                            <label for="ammount" class="text-sm font-medium text-slate-600">Ammount</label>
                            <input v-model="incomeFormData.ammount" type="number" name="ammount" id="ammount" class="w-full border-1 border-slate-300 rounded h-9">
                            <small v-if="incomeFormData.errors.ammount" class="text-red-600">{{ incomeFormData.errors.ammount[0] }}</small>
                        </div>

                        <div class="form-control">
                            <label for="description" class="text-sm font-medium text-slate-600">Description</label>
                            <input v-model="incomeFormData.description" type="text" name="description" id="description" class="w-full border-1 border-slate-300 rounded h-9">
                            <small v-if="incomeFormData.errors.description" class="text-red-600">{{ incomeFormData.errors.description[0] }}</small>
                        </div>

                        <div class="form-control flex gap-2 mt-6">
                            <button type="submit" @click="addIncomeToAccount" :disabled="is_loading_transaction" class="bg-green-200 text-green-600 px-4 py-1 cursor-pointer rounded disabled:opacity-50">Transfer</button>
                            <button type="reset" @click.prevent="toggleAddIncomeModal" class="bg-gray-200 text-gray-600 px-4 py-1 cursor-pointer rounded">Cancel</button>
                        </div>
                    </form>

                </div>
            </modal-component>
        </Transition>

        <Transition name="fade">
            <modal-component :toggle_variable="show_make_transfer" :toggle_action="toggleMakeTransferModal">
                <div class="modal-header mb-6">
                    <h3>
                        Lets transfer some money! <br> 
                        <small>make sure you dont transfer more than you got, starvation hurts the stomach</small>
                    </h3>
                </div>

                <div class="modal-main">

                    <form @submit.prevent="addTransaction" class="flex flex-col gap-2">
                        <div class="form-control">
                            <label for="ammount" class="text-sm font-medium text-slate-600">Ammount</label>
                            <input v-model="transferFormData.ammount" type="number" name="ammount" id="ammount" class="w-full border-1 border-slate-300 rounded h-9">
                            <small v-if="transferFormData.errors.ammount" class="text-red-600">{{ transferFormData.errors.ammount[0] }}</small>
                        </div>

                        <div class="form-control">
                            <label for="description" class="text-sm font-medium text-slate-600">Description</label>
                            <input v-model="transferFormData.description" type="text" name="description" id="description" class="w-full border-1 border-slate-300 rounded h-9">
                            <small v-if="transferFormData.errors.ammount" class="text-red-600">{{ transferFormData.errors.ammount[0] }}</small>
                        </div>

                        <div class="form-control flex gap-2 mt-6">
                            <button type="submit" @click.prevent="transferFromAccount" :disabled="is_loading_transaction" class="bg-green-200 text-green-600 px-4 py-1 cursor-pointer rounded disabled:opacity-50">Transfer</button>
                            <button type="reset" @click.prevent="toggleMakeTransferModal" class="bg-gray-200 text-gray-600 px-4 py-1 cursor-pointer rounded">Cancel</button>
                        </div>
                    </form>

                </div>
            </modal-component>
        </Transition>
    </div>
</template>

<script setup>

import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import ModalComponent from './reusables/ModalComponent.vue'
import { useAccountStore } from '@/stores/accountStore'
import utils from '../utils/utils'

const store = useAccountStore()

const new_account_modal = ref(false)
const show_delete_warn = ref(false)
const delete_account_id = ref(null)
const show_add_income = ref(false)
const show_make_transfer = ref(false)

const is_loading_transaction = ref(false)
const current_account_data = ref({})

const accountFormData = ref({
    type: null,
    institution: null,
    balance: null,
    currency: null,
    errors: []
})

const incomeFormData = ref({
    description: null,
    ammount: null,
    currency: null,
    category: null,
    type: null,
    errors: []
})

const transferFormData = ref({
    description: null,
    ammount: null,
    currency: null,
    category: null,
    type: null,
    errors: []
})

const toggleNewAccountModal = () => {
    accountFormData.errors = []
    new_account_modal.value = !new_account_modal.value
}

const toggleDeleteWarn = (account_id) => {
    show_delete_warn.value = !show_delete_warn.value
    delete_account_id.value = account_id
}

const doSaveNewAccount = () => {
    is_loading_transaction.value = true
    router.post(route('accounts.store'), accountFormData.value, {
        onSuccess: () => {
            store.addAccount(accountFormData) 
            store.fetchAccounts()
            toggleNewAccountModal()
            is_loading_transaction.value = false
        },
    })
}

const doDeleteAccount = () => {
    is_loading_transaction.value = true
    router.delete(route('accounts.destroy', {'id': delete_account_id.value}), {
        onSuccess: () => {
            store.removeAccount(delete_account_id.value)
            store.fetchAccounts()
            delete_account_id.value = null
            toggleDeleteWarn()
            is_loading_transaction.value = false
        }
    })
}

const toggleAddIncomeModal = (account) => {
    show_add_income.value = !show_add_income.value

    if (show_add_income.value) {
        current_account_data.value = account
        current_account_data.value.category = 'income'
        
    } else {
        current_account_data.value = {}
        incomeFormData.value.description = null
        incomeFormData.value.ammount = null
        incomeFormData.value.errors = {}

    }
}

const toggleMakeTransferModal = (account) => {
    show_make_transfer.value = !show_make_transfer.value

    if (show_make_transfer.value) {
        current_account_data.value = account
        current_account_data.value.category = 'transfer'

    } else {
        current_account_data.value = {}
        transferFormData.value.description = null
        transferFormData.value.ammount = null
        incomeFormData.value.errors = {}
    }
}

const addIncomeToAccount = async () => {

    is_loading_transaction.value = true

    try {

        incomeFormData.value.category = current_account_data.value.category
        incomeFormData.value.type = current_account_data.value.type
        incomeFormData.value.currency = current_account_data.value.currency

        await store.addTransaction(incomeFormData.value)

        store.fetchTransactions()

        store.calculateAccountBallance(current_account_data.value.id, incomeFormData.value.category, incomeFormData.value.ammount)

        show_add_income.value = false

    } catch (error) {
        console.error('Could not make transaction: ', error)

        incomeFormData.value.errors = error.response.data.errors

    } finally {
        is_loading_transaction.value = false

    }
}

const transferFromAccount = async () => {

    is_loading_transaction.value = true

    try {

        transferFormData.value.category = current_account_data.value.category
        transferFormData.value.type = current_account_data.value.type
        transferFormData.value.currency = current_account_data.value.currency

        await store.addTransaction(transferFormData.value)

        store.fetchTransactions()

        store.calculateAccountBallance(current_account_data.value.id, transferFormData.value.category, transferFormData.value.ammount)

        show_make_transfer.value = false

    } catch (error) {
        console.error('Could not make transaction: ', error)
        transferFormData.value.errors = error.response.data.errors
        

    } finally {
        is_loading_transaction.value = false

    }
}

</script>
