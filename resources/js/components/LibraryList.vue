<template>
    <div class="col-span-2 border-r border-r-grey-400 grid gap-y-2" style="grid-template-rows:auto 48px;">
        <textarea class="p-2 w-full h-full resize-none" name="LibraryList" v-model="cardList"></textarea>
        <button class="p-2 w-full h-10 bg-blue-400 hover:bg-blue-500 text-white font-semibold uppercase" @click="generate">Generate</button>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        name: "LibraryList",

        data() {
            return {
                cardList:
                    'Sol Ring'
                    +'\n'+
                    'Angelfire ignition'
                    +'\n'+
                    'Elspeth Tirel'
                    +'\n'+
                    'All That Glitters'
                    +'\n'+
                    'Tamiyo, Compleated Sage'
            }
        },

        mounted() {
            this.generate();
        },

        methods: {
            generate() {
                axios.post(route('api.cards.get'), {nameList: this.cardList})
                     .then((response) => this.$emit('fetchedCards', response.data))
            }
        }
    }
</script>
