<template>
    <span ref="btnRef"
          v-on:mouseenter="toggleTooltip()"
          v-on:mouseleave="toggleTooltip()"
          class="underline"
    >
        {{keyword}}
    </span>
    <div ref="tooltipRef"
         v-bind:class="{'hidden': !tooltipShow, 'block': tooltipShow}"
         class="bg-blue-400 z-50 font-normal text-sm max-w-xs text-left no-underline rounded-lg shadow-lg shadow-gray-400"
    >
        <div class="text-white font-semibold p-3 mb-0 border-b border-solid border-slate-100 uppercase rounded-t-lg">
            {{keyword}}
        </div>
        <div class="text-white p-3">
            <v-injector :content="crawledDescription">
                <template v-for="iconData in crawledIcons" v-slot:[iconData.icon]>
                    <v-icon :icon="iconData.icon" :layers="iconData.layers" />
                </template>
            </v-injector>
        </div>
    </div>
</template>

<script>
import { createPopper } from "@popperjs/core";
import { iconList } from "../../App.vue";
import { crawlTextForIcons } from "../../functions/cardIcon";

export default {
    name: "Tooltip",

    props: [
        'keyword',
        'description',
    ],

    data() {
        return {
            crawledIcons: [],
            tooltipShow: false,
            iconList
        }
    },

    computed: {
        crawledDescription() {
            let response = crawlTextForIcons(this.description, this.crawledIcons);
            this.crawledIcons = response.icons;
            return response.text;
        }
    },

    methods: {
        toggleTooltip: function(){
            if(this.tooltipShow){
                this.tooltipShow = false;
            } else {
                this.tooltipShow = true;
                createPopper(this.$refs.btnRef, this.$refs.tooltipRef, {
                    placement: "top"
                });
            }
        }
    }
}
</script>
