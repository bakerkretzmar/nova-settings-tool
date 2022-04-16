<template>
    <DefaultSetting :setting="setting" :errors="errors || []">
        <div
            :id="setting.key"
            class="toggle inline-flex items-center"
            :class="{ on: setting.value }"
            @click="
                $emit('update', {
                    key: setting.key,
                    value: setting.value === true ? false : true,
                })
            "
        >
            <span class="slider"></span>
        </div>
    </DefaultSetting>
</template>

<script>
import DefaultSetting from './DefaultSetting.vue';

export default {
    components: {
        DefaultSetting,
    },
    props: {
        setting: Object,
        errors: Array,
    },
    emits: ['update'],
};
</script>

<style scoped>
.slider {
    position: relative;
    width: 4rem;
    height: 2rem;
    background-color: rgb(var(--colors-gray-200));
    transition: 200ms ease-out;
    border-radius: 1rem;
    overflow: hidden;
    cursor: pointer;
}
.slider::before {
    position: absolute;
    content: '';
    height: 1.5rem;
    width: 1.5rem;
    left: 0.25rem;
    top: 0.25rem;
    background: white;
    transition: 200ms;
    border-radius: 50%;
    box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.15);
}
.toggle.on .slider {
    background-color: rgb(var(--colors-primary-400));
}
.toggle.on .slider::before {
    transform: translateX(2rem);
}
</style>
