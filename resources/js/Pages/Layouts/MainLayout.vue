<template>
    <main>
        <p v-if="showFlashMessage" 
           class="fixed z-10 top-18 left-1/2 -translate-x-1/2 w-full max-w-95 mx-auto text-center py-1 bg-green-400 text-green-900">
                {{ flashMessage }}
        </p>

        <Transition name="fade" mode="out-in">
            <slot></slot>
        </Transition>
    </main>
</template>

<script setup>

import { ref, computed, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const flashMessage = computed(() => page.props.flash.success)
const showFlashMessage = ref(false)

watch(flashMessage, (newV) => {
    if (newV) showFlashMessage.value = true
    setTimeout(() => showFlashMessage.value = false, 1500)
})

</script>
