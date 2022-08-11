<template>
    <div class="col-span-2 border-r border-r-grey-400 grid gap-y-2" style="grid-template-rows:auto 48px;">
        <textarea class="p-2 w-full h-full resize-none" name="LibraryList" v-model="cardList"></textarea>
        <button v-if="loading" class="p-2 w-full h-10 bg-blue-300 text-white loading" disabled>
            <div class="icon-loading w-6 mx-auto" title="Loading"></div>
        </button>
        <div v-else>
            <button class="p-2 w-11/12 h-10 bg-blue-400 hover:bg-blue-500 text-white font-semibold uppercase" @click="generate">
                Generate
            </button>
            <button class="p-2 w-1/12 h-10 bg-blue-400 hover:bg-blue-500 text-white font-semibold uppercase" @click="toggleExportMenu">
                :
                <div v-show="exportMenuActive" class="z-50 rounded-lg bg-white text-blue-400 -mt-20">
                    <button class="p-2 bg-white hover:bg-blue-500 hover:text-white" @click="exportCardList">Export</button>
                    <button class="p-2 bg-white hover:bg-blue-500 hover:text-white" @click="importCardList">Import</button>
                </div>
            </button>
        </div>
    </div>
</template>

<script>
    export default {
        name: "LibraryList",

        data() {
            return {
                loading: false,
                exportMenuActive: false,
                cardList:
                    'Odric, Blood-Cursed'
                    // +'\n'+
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
            },

            toggleExportMenu() {
                return this.exportMenuActive = !this.exportMenuActive;
            },

            exportCardList() {

            },

            importCardList() {
                let importedCards = {"version": 1, "cards": ["sol ring", "simic signet"]};
                this.cardList = importedCards.cards.join('\n');
                this.generate();
            }
        }
    }
</script>
