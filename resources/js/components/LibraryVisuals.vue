<template>
    <div class="overflow-y-scroll p-2 grid col-span-10 gap-2 auto-rows-min" :class="gridSize">
        <button @click="show(card)" v-for="card in imageList">
            <picture>
                <img :src="card.image_uris.normal" :alt="card.name">
            </picture>
        </button>
    </div>
</template>

<script>
    export default {
        name: "LibraryVisuals",
        props: [
            'imageList',
            'gridSize'
        ],

        methods: {
            show(card){
                this.$emit('show', card)
            }
        }
    }
</script>
