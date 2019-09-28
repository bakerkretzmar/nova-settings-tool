<template>
    <p class="text-sm leading-normal text-80 italic" v-html="text"></p>
</template>

<script>
export default {
    props: {
        text: String,
    },
}
</script>

<style>
a {
    color: var(--primary);
    text-decoration-line: none;
}

a:hover,
a:active {
    opacity: 0.5;
}

a:active {
    opacity: 0.8;
}
</style>
