<template>
    <div class="flex border-b border-40">

        <setting-label>
            {{ __(label) }}
        </setting-label>

        <div class="py-6 px-8">

            <div
                class="toggle py-1"
                :class="{ 'on' : setting.value }"
                @click="$emit('toggle', setting.key)"
            >
                <span class="slider"></span>
            </div>

        </div>

        <setting-info v-if="help" :text="__(help)" class="py-6 w-1/2"/>

    </div>
</template>

<script>
import SettingLabel from './Label'
import SettingInfo from './Info'

export default {
    props: {
        label: String,
        setting: Object,
        help: {
            type: String,
            required: false,
        },
    },

    components: {
        SettingLabel,
        SettingInfo
    },
}
</script>

<style scoped>
.toggle {
    display: flex;
    align-items: center;
}
.slider {
    position: relative;
    margin-right: 1rem;
    width: 4rem;
    height: 2rem;
    background: var(--50);
    transition: .2s ease-out;
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
    transition: .2s;
    border-radius: 50%;
    box-shadow: 0px 1px 3px rgba(0,0,0,0.15);
}
.toggle.on .slider {
    background: var(--info);
}
.toggle.on .slider::before {
    transform: translateX(2rem);
}
</style>
