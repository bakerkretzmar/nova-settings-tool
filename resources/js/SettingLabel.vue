<template>
    <label :for="labelFor" class="inline-block pt-2 leading-tight">
        <slot />
    </label>
</template>

<script>
export default {
    props: {
        labelFor: String,
    },
};
</script>
