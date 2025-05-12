<template>
    <div class="dashboard-notifications relative flex flex-col items-center justify-center gap-4 bg-white overflow-hidden" :class="store.notifications.length && 'bg-green-600'">
        <font-awesome-icon :icon="['fas', 'bell']" class="absolute text-9xl text-slate-300 opacity-30 z-0" />
        <p class="font-bold text-slate-500 mb-4">You have {{ store.notifications.length }} notifications</p>

        <div class="notifications-wrap" v-if="store.notifications.length">
            <div class="notification" v-for="(notification, idx) in store.notifications" :key="idx">
                {{ notification }}
            </div>
        </div>
    </div>
</template>

<script setup>
import { useAccountStore } from '@/stores/accountStore'

const store = useAccountStore()

</script>
