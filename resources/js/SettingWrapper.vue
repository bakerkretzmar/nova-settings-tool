<template>
    <div class="field-wrapper flex flex-col border-b border-gray-100 dark:border-gray-700" :class="{ 'md:flex-row': !stacked }">
        <slot />
    </div>
</template>

<script>
export default {
    props: {
        index: Number,
        stacked: Boolean,
    },
};
</script>
