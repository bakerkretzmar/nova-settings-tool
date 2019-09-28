<template>
    <setting-wrapper :stacked="setting.stacked">

        <div class="w-1/5 px-8" :class="setting.stacked ? 'pt-6' : 'py-6'">

            <setting-label :label-for="setting.key" :class="{ 'mb-2': setting.help }">
                {{ __(setting.label) }}
            </setting-label>

        </div>

        <div class="py-6 px-8" :class="settingClasses">

            <slot name="setting"/>

            <setting-help class="error-text mt-2 text-danger" v-if="errors.length">
                {{ errors[0] }}
            </setting-help>

            <setting-help class="mt-2" v-if="setting.help">
                {{ __(setting.help) }}
            </setting-help>

        </div>

    </setting-wrapper>
</template>

<script>
import SettingHelp from './SettingHelp'
import SettingLabel from './SettingLabel'
import SettingWrapper from './SettingWrapper'

export default {
    components: { SettingHelp, SettingLabel, SettingWrapper },

    props: {
        setting: Object,
        fullWidth: Boolean,
        errors: Array,
    },

    computed: {
        settingClasses() {
            return this.fullWidth ? (this.setting.stacked ? 'w-full' : 'w-4/5') : 'w-1/2'
        },
    },
}
</script>
