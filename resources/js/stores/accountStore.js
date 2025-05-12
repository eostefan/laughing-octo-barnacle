import { defineStore } from 'pinia'
import axios from 'axios'

export const useAccountStore = defineStore('accounts', {
    state: () => ({
        accounts: [],
        transactions: [],
        notifications: [],
        loading_accounts: false,
        loading_transactions: false
    }),

    getters: {
        activeAccountCount: (state) => {
            return state.accounts.filter(account => account.deleted_at === null).length
        }
    },

    actions: {

        async fetchAccounts() {
            this.loading_accounts = true

            try {
                const response = await axios.get(route('accounts.index'))
                this.accounts = response.data.accounts || []

            } catch (error) {
                console.log('Failed to fetch accounts: ', error);

            } finally {
                this.loading_accounts = false
            }
        },

        async fetchTransactions() {
            this.loading_transactions = true

            try {
                const response = await axios.get(route('transactions.index'))
                this.transactions = response.data.transactions.sort((a,b) => new Date(b.created_at) - new Date(a.created_at))

            } catch (error) {
                console.log('Failed to fetch transactions: ', error);

            } finally {
                this.loading_transactions = false
            }
        },

        async addTransaction(payload) {
            try {
                const response = await axios.post(route('transactions.store'), payload)
                this.transactions.push(response.data.transaction)
                
            } catch (error) {
                console.error('Failed to add transaction: ', error);
                throw error
            }
        },

        calculateAccountBallance(id, category, sum) {
            const account = this.accounts.find(account => account.id === id)
            if (!account) return

            if (category === 'income') {
                account.balance += sum
                this.notifications = [...this.notifications, `Recieved ${sum}! Account ${account.type} updated!`]

            } else if (category === 'transfer') {
                account.balance -= sum
                this.notifications = [...this.notifications, `Sent ${sum}! Account ${account.type} updated!`]

            }
        },

        addAccount(account) {
            this.accounts = [...this.accounts, account]
            this.notifications = [...this.notifications, `Added a new account!`]
        },

        removeAccount(id) {
            this.notifications = [...this.notifications, `Removed account!`]
            this.accounts = this.accounts.filter(account => account.id !== id)
        }
    }
})
