<template functional>
    <div class="flex border-b border-40" :class="{ 'flex-col': props.stacked }">
        <slot/>
    </div>
</template>
