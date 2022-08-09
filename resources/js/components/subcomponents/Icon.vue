<template>
    <span :class="'icon-'+icon.replace(/\{|\/|}/g, '')" :title="icon">
        <span v-for="layer in layers" :class="'path' + layer"></span>
    </span>
</template>

<script>
export default {
    name: "Icon",
    props: [
        'icon',
        'layers'
    ]
}
</script>
