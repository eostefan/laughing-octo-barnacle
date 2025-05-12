<template>
    <section class="dashboard">

        <AccountComponent :user="props.user" />

        <NotificationsComponent />

        <AccountsComponent />

        <TransactionsComponent />

    </section>
</template>

<script setup>

import { useAccountStore } from '@/stores/accountStore'

import AccountComponent from '@/components/AccountComponent.vue'
import NotificationsComponent from '@/components/NotificationsComponent.vue'
import AccountsComponent from '@/components/AccountsComponent.vue'
import TransactionsComponent from '@/components/TransactionsComponent.vue'

const store = useAccountStore()

store.fetchAccounts()
store.fetchTransactions()

const props = defineProps({
    user: Object,
})

</script>

<style lang="scss" scoped>

.dashboard {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    grid-template-rows: repeat(6, 1fr);
    gap: 20px;
    width: 100%;
    max-width: 1920px;
    margin: 0 auto;

    grid-template-areas: 
        "user notifications transactions transactions transactions"
        "user notifications transactions transactions transactions"
        "accounts accounts transactions transactions transactions"
        "accounts accounts transactions transactions transactions"
        "accounts accounts transactions transactions transactions"
        "accounts accounts transactions transactions transactions"
    ;

    position: relative;
    width: 100dvw;
    height: 100dvh;
    padding: 1rem;
    overflow: hidden;
    background-color: rgb(226,232,248);

    @media screen and (max-width: 1200px) {
        grid-template-columns: repeat(4, 1fr);
        grid-template-rows: repeat(4, 1fr);
        grid-template-areas: 
            "user accounts accounts accounts"
            "transactions transactions transactions transactions"
            "transactions transactions transactions transactions"
            "transactions transactions transactions transactions"
        ;
    }

    @media screen and (max-width: 768px) {
        display: flex;
        flex-direction: column;
        overflow: auto;
        height: auto;
    }

    & > * {
        padding: 1rem;
        border-radius: 1rem;
        box-shadow: 2px 2px 12px rgba(0,0,0,0.1);
    }

    .dashboard-user {
        background-color: #FFF;
        grid-area: user;
    }

    .dashboard-notifications {
        grid-area: notifications;

        @media screen and (max-width: 1200px) {
            position: absolute;
            opacity: 0;
        }
    }
    
    .dashboard-accounts {
        background-color: #FFF;
        grid-area: accounts;

        @media screen and (max-width: 768px) {
            .dashboard-accounts-main {
                gap: 60px;
                flex-direction: column;
                justify-content: flex-start;
                min-height: 350px;
            }
        }
    }

    .dashboard-transactions {
        background-color: #FFF;
        grid-area: transactions;
        overflow-y: auto;
        padding: 0 0 1rem !important;
    }
}

</style>
