<template>
    <Component size="lg" align="center" v-bind="$attrs" is="DefaultButton" ref="button">
        <span :class="{ invisible: processing }">
            <slot />
        </span>
        <span v-if="processing" class="absolute" style="top: 50%; left: 50%; transform: translate(-50%, -50%)">
            <Loader class="text-white" width="32" />
        </span>
    </Component>
</template>

<script>
export default {
    props: {
        processing: Boolean,
    },
    methods: {
        focus() {
            this.$refs.button.focus();
        },
    },
};
</script>
