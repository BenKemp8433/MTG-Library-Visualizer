<template>
    <nav class="col-span-12 bg-blue-400 shadow-lg shadow-gray-400 flex items-center justify-between">
        <h1 class="px-2 py-2 text-3xl text-white">{{title}}</h1>
        <div class="mx-2 w-16 h-min text-3xl flex justify-between">
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
    export default {
        name: 'Nav',

        data() {
            return {
                title: 'MTG Library Visualizer'
            }
        },

        methods: {
            updateGridSize(el) {
                this.$emit('updateGridSize', el.target.dataset.changeDirection)
            },
        }
    }
</script>
