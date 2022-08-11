<template>
    <Transition name="slide-fade">
        <div class="sideBar overflow-y-scroll fixed right-0 p-2 w-1/3 border-l border-l-grey-400 shadow-lg shadow-gray-400 grid" v-show="active">
            <h2 class="text-3xl">
                {{cardData.name}}
                <button class="float-right" @click="closeSidebar()">X</button>
            </h2>

            <div class="text-xl">
                <span v-if="cardData.mana_cost" v-for="icon in cardData.mana_cost.split(/\{|}/g)">
                    <v-icon v-if="icon.length" :icon="'{'+icon+'}'" :layers="iconList['{'+icon+'}']" />
                </span>
                <br v-else>
            </div>

            <picture v-if="cardData.image_uris">
                <img class="mx-auto mb-5" :src="cardData.image_uris.large" :alt="cardData.name">
            </picture>

            <h3 class="mb-5">{{cardData.type}}</h3>

            <p class="mb-5">
                <v-injector :content="oracleTextByLine">
                    <template v-slot:br>
                        <br>
                    </template>
                    <template v-for="keywordData in crawledKeywords" v-slot:[keywordData.keyword]>
                        <v-tooltip :keyword="keywordData.keyword" :description="keywordData.description" />
                    </template>
                    <template v-for="iconData in crawledIcons" v-slot:[iconData.icon]>
                        <v-icon :icon="iconData.icon" :layers="iconData.layers" />
                    </template>
                </v-injector>
            </p>

            <table class="">
                <thead>
                    <tr class="bg-blue-400 text-left text-white uppercase">
                        <th>Format</th>
                        <th>Legality</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="" v-for="(legality, format) in cardData.legalities">
                        <td class="">{{format}}</td>
                        <td class="">{{legality}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </Transition>
</template>

<script>
    import { iconList, keywordList } from "../App.vue";
    import { crawlTextForIcons } from "../functions/cardIcon";

    export default {
        name: "Sidebar",
        props: [
            'cardData',
            'active'
        ],

        data() {
            return {
                crawledKeywords: [],
                crawledIcons: [],
                keywordExceptions: [
                    'banding',
                    'changeling',
                    'compleated',
                    'enchant',
                    'escape',
                    'flying',
                    'frenzy',
                    'mill',
                    'mills',
                    'morph',
                    'protection',
                    'shadow',
                    'storm',
                ],
                iconList,
                keywordList
            }
        },

        mounted() {
            document.addEventListener('click', (e) => {
                // if (this.active) {
                //     this.closeSidebar();
                // }
            })
        },

        computed: {
            oracleTextByLine() {
                if (!this.cardData.oracle_text) {
                    return false;
                }

                let oracleText = this.cardData.oracle_text;

                oracleText = this.crawlTextForBreaks(oracleText);
                oracleText = this.crawlTextForKeywords(oracleText);
                let response = crawlTextForIcons(oracleText, this.crawledIcons);
                this.crawledIcons = response.icons;
                return response.text;
            }
        },

        methods: {
            closeSidebar(){
                this.$emit('hide');
            },

            crawlTextForBreaks(text){
                return text.replace(/\n/g, '==br==');
            },

            crawlTextForKeywords(text){
                let i = 0;
                for (let keyword in this.keywordList) {
                    if (text.toLowerCase().includes(keyword) &&
                        (!this.cardData.name.toLowerCase().includes(keyword) ||
                        this.cardData.name.toLowerCase() === keyword) ||
                        this.keywordExceptions.includes(keyword)
                    ) {
                        this.crawledKeywords[i++] = {"keyword": keyword, "description": this.keywordList[keyword]};
                        text = text.replace(new RegExp(`\\b${keyword}\\b`, 'i'), '=='+keyword+'==');
                    }
                }
                return this.capitalizeFirstLetter(text);
            },

            capitalizeFirstLetter([ first, ...rest ], locale = navigator.language) {
                return first === undefined ? '' : first.toLocaleUpperCase(locale) + rest.join('')
            }
        }
    }
</script>

<style>
    tr:nth-child(even) {background: #eee}
    tr:nth-child(odd):not(:first-child) {background: #fff}

    .sideBar {
        top:51px;
        height:calc(100vh - 51px);
        z-index: 10;
        background-color: #fff;
    }
    /* Sliding animation */
    .slide-fade-enter-active,
    .slide-fade-leave-active {transition: all 0.3s ease-out;}
    .slide-fade-enter-from,
    .slide-fade-leave-to {transform: translateX(50vw);}
</style>
