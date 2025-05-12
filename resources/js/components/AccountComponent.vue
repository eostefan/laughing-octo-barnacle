<template>
    <div class="dashboard-user flex flex-col items-center justify-center gap-4 text-center overflow-hidden">
        <div class="dashboard-user-left flex gap-2 items-center">
            <span class="avatar grid items-center font-bold text-3xl text-slate-500 w-25 h-25 bg-slate-200 rounded-3xl">
                {{ utils.avatarInitials(props.user.name) }}
            </span>
            <h1 class="text-slate-500 text-2xl font-bold text-center">{{ props.user.name }}</h1>
        </div>

        <div class="dashboard-user-main">
            <p class="text-slate-500 text-sm">You have <strong>{{ store.activeAccountCount }} active accounts</strong></p>
            <p class="text-slate-500 text-sm mb-4">You have a total of <strong>{{ store.transactions.length }} transactions</strong></p>

            <button @click="logout"
                class="font-bold text-red-600 cursor-pointer bg-red-300 px-8 py-1 rounded active:scale-75 transition-transform">
                    logout
            </button>
        </div>

    </div>
</template>

<script setup>

import { router } from '@inertiajs/vue3'
import utils from '@/utils/utils'

import { useAccountStore } from '@/stores/accountStore'
import { watch } from 'vue'

const store = useAccountStore()

const props = defineProps({
    user: Object,
})

watch(
    () => store.activeAccountCount, 
    (newV, oldV) => {
        console.log(`Active accounts changed from ${oldV} to ${newV}`)
    }
)

const logout = () => {
  router.delete(route('logout'), {}, {
    preserveScroll: true,
    preserveState: false,
  })
}

</script>
