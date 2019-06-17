<template>
    <div class="flex border-b border-40">

        <setting-label>{{ __(name) }}</setting-label>

        <div class="py-6 px-8">

            <div
                class="toggle py-1"
                :class="{ 'on' : setting.value }"
                @click="toggle"
            >
                <span class="slider"></span>
            </div>

        </div>

        <setting-info :text="link.text || ''" :url="link.url || ''" class="py-6 w-1/2">{{ __(description) }}</setting-info>

    </div>
</template>

<script>
import SettingLabel from './Label'
import SettingInfo from './Info'

export default {
    props: {
        name: String,
        setting: Object,
        description: String,
        link: Object
    },

    components: {
        SettingLabel,
        SettingInfo
    },

    methods: {
        toggle(e) {
            this.$emit('toggle', this.setting.key)
        }
    }
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
    box-shadow: inset 0px 1px 2px rgba(0,0,0,0.15)
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
