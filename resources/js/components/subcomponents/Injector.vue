<template>
    <template v-for="(chunk, i) in chunks">
        <span v-html="chunk" v-if="i % 2 === 0" />
        <slot v-else :name="chunk" />
    </template>
</template>

<script>
export default {
    name: "Injector",

    props: [
        'content',
    ],

    computed: {
        chunks () {
            if (!this.content) { return false }
            return this.content.split('==');
        }
    }
}
</script>
