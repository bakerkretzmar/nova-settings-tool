<template>
    <default-setting :setting="setting">

        <template slot="setting">

            <div
                class="toggle flex items-center"
                :class="{ 'on': setting.value }"
                @click="$emit('update', {
                    key: setting.key,
                    value: setting.value === true ? false : true,
                })"
            >
                <span class="slider"></span>
            </div>

        </template>

    </default-setting>
</template>

<script>
import DefaultSetting from './DefaultSetting'

export default {
    components: { DefaultSetting },

    props: {
        setting: Object,
    },
}
</script>

<style scoped>
.slider {
    position: relative;
    margin-right: 1rem;
    width: 4rem;
    height: 2rem;
    background: var(--50);
    transition: 200ms ease-out;
    border-radius: 1rem;
    cursor: pointer;
}
.slider::before {
    position: absolute;
    content: "";
    height: 1.5rem;
    width: 1.5rem;
    left: .25rem;
    top: .25rem;
    background: white;
    transition: 200ms;
    border-radius: 50%;
    box-shadow: 0px 1px 3px rgba(0, 0, 0, .15);
}
.toggle.on .slider {
    background: var(--info);
}
.toggle.on .slider::before {
    transform: translateX(2rem);
}
</style>
