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
            <button class="p-2 w-1/12 h-10 bg-blue-400 hover:bg-blue-500 text-white font-semibold" @click="toggleExportMenu">
                :
                <div v-show="exportMenuActive" class="-mt-24 float-right rounded-lg shadow-lg">
                    <button class="p-2 z-50 w-20 rounded-t-lg border border-b-0 border-grey-400 bg-white text-blue-400 bg-white hover:bg-blue-500 hover:text-white" @click="exportCardList">Export</button>
                    <button class="p-2 z-50 w-20 rounded-b-lg border border-t-0 border-grey-400 bg-white text-blue-400 bg-white hover:bg-blue-500 hover:text-white">
                        <label>
                            <input class="hidden" type="file" ref="doc" @change="importCardList">Import
                        </label>
                    </button>
                </div>
            </button>
        </div>
    </div>
</template>

<script>
import download from "downloadjs";
import { appTitle } from '../App.vue'

export default {
    name: "LibraryList",

    props: [
        'appTitle'
    ],

    data() {
        return {
            loading: false,
            exportMenuActive: false,
            cardList: '',
            originalTitle: appTitle
        }
    },

    methods: {
        generate() {
            this.loading = true;
            axios.post(route('api.cards.get'), {nameList: this.cardList})
                 .then((response) => this.$emit('fetchedCards', response.data))
                 .then(() => {
                     this.loading = false;
                     this.exportMenuActive = false;
                 })
        },

        toggleExportMenu() {
            return this.exportMenuActive = !this.exportMenuActive;
        },

        exportCardList() {
            let exportedCards = {"version": 1, "cards": this.cardList.split('\n')};
            let documentTitle = this.appTitle !== this.originalTitle ? this.appTitle : 'unnamed cardlist of '+Date.now();
            download(JSON.stringify(exportedCards), documentTitle, 'text/plain');
        },

        importCardList() {
            const reader = new FileReader();
            let file = this.$refs.doc.files[0];
            if (file.name.includes(".txt")) {
                reader.onload = (res) => {
                    let importedCards = JSON.parse(res.target.result);

                    this.cardList = importedCards.cards.join('\n');
                    this.generate();
                };
                reader.onerror = (err) => console.log(err);
                reader.readAsText(file);
            }
        }
    }
}
</script>
