import {iconList} from '../App.vue';

export function crawlTextForIcons(text, crawledIcons){
    const matches = text.match(/(\{(\D{1,3}|(\d{1,3})|(\d\/\D)|(\D\/\D\/\D))})/g) ?? [];
    matches.forEach((icon, i) => {
        crawledIcons[i] = {icon: icon, layers: iconList[icon]};
    });
    const delimitedText =  text.replace(/\{/g, '=={').replace(/}/g, '}==');
    return {
        icons: crawledIcons,
        text: delimitedText
    };
}
