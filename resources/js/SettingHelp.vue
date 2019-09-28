<template functional>
    <div class="help-text">
        <slot v-html="$slots.default[0].text"/>
    </div>
</template>
