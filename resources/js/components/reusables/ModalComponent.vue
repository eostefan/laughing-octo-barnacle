<template>
    <div class="modal fixed inset-0 z-10 flex items-center justify-center" v-if="toggle_variable">
        <div class="overflow absolute inset-0 bg-black opacity-20" @click="toggle_action"></div>

        <div class="modal-main bg-white relative z-20 w-1/3 mx-auto p-6 rounded-md">
            <slot />
        </div>
    </div>
</template>

<script setup>

const props = defineProps({
    toggle_variable: Boolean,
    toggle_action: Function
})

</script>
