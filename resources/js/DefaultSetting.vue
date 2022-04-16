<template>
    <SettingWrapper :stacked="setting.stacked">
        <div class="px-8 mt-2 md:mt-0" :class="setting.stacked ? 'md:pt-5 w-full' : 'w-full md:w-1/5 md:py-5'">
            <SettingLabel :label-for="setting.key" :class="{ 'mb-2': setting.help }">
                {{ __(setting.label) }}
            </SettingLabel>
        </div>
        <div class="mt-1 md:mt-0 pb-5 px-8" :class="setting.stacked ? 'md:pt-5 w-full' : 'w-full md:w-3/5 md:py-5'">
            <slot />
            <SettingHelp class="mt-2 help-text-error" v-if="errors.length">{{ errors[0] }}</SettingHelp>
            <SettingHelp class="mt-2 help-text" v-if="setting.help" v-html="__(setting.help)" />
        </div>
    </SettingWrapper>
</template>

<script>
import SettingHelp from './SettingHelp.vue';
import SettingLabel from './SettingLabel.vue';
import SettingWrapper from './SettingWrapper.vue';

export default {
    components: {
        SettingHelp,
        SettingLabel,
        SettingWrapper,
    },
    props: {
        setting: Object,
        fullWidth: Boolean,
        errors: Array,
    },
};
</script>
