<template>
    <div class="col-span-2 border-r border-r-grey-400 grid gap-y-2" style="grid-template-rows:auto 48px;">
        <textarea class="p-2 w-full h-full resize-none" name="LibraryList" v-model="cardList"></textarea>
        <button v-if="loading" class="p-2 w-full h-10 bg-blue-300 text-white loading" disabled>
            <div class="icon-loading w-6 mx-auto" title="Loading"></div>
        </button>
        <button v-else class="p-2 w-full h-10 bg-blue-400 hover:bg-blue-500 text-white font-semibold uppercase" @click="generate">
            Generate
        </button>
    </div>
</template>

<script>
    export default {
        name: "LibraryList",

        data() {
            return {
                loading: false,
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
                this.loading = true;
                axios.post(route('api.cards.get'), {nameList: this.cardList})
                     .then((response) => this.$emit('fetchedCards', response.data))
                     .then(() => this.loading = false)
            }
        }
    }
</script>
