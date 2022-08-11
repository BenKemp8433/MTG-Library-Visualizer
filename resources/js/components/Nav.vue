<template>
    <nav class="col-span-12 bg-blue-400 shadow-lg shadow-gray-400 flex items-center justify-between">
        <input class="px-2 py-2 text-3xl text-white bg-blue-400 w-full" @change="updateDocumentTitle" :value="appTitle" placeholder="Name your card list" />
        <div class="mx-2 w-16 h-min text-3xl flex justify-between text-white">
            <button @click="updateGridSize">
                <i class="icon-grid-increase" data-change-direction="increase"></i>
            </button>
            <button @click="updateGridSize">
                <i class="icon-grid-decrease" data-change-direction="decrease"></i>
            </button>
        </div>
    </nav>
</template>

<script>
import { appTitle } from '../App.vue'
export default {
    name: 'Nav',

    data() {
        return {
            appTitle
        }
    },

    methods: {
        updateGridSize(el) {
            this.$emit('updateGridSize', el.target.dataset.changeDirection)
        },

        updateDocumentTitle(el) {
            this.$emit('updateDocumentTitle', el.target.value)
        }
    }
}
</script>
