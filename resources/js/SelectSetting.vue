<template>
    <default-setting :setting="setting" :errors="errors || []">

        <template slot="setting">

            <select
                :id="setting.key"
                :value="setting.value"
                @change="$emit('update', {
                    key: setting.key,
                    value: $event.target.value,
                })"
                class="w-full form-control form-select"
            >
                <option value="" selected>{{ setting.placeholder || __('Choose an option') }}</option>
                <option
                    v-for="(label, option) in setting.options"
                    :value="option"
                >
                    {{ __(label) }}
                </option>
            </select>

        </template>

    </default-setting>
</template>

<script>
import DefaultSetting from './DefaultSetting'

export default {
    components: { DefaultSetting },

    props: {
        setting: Object,
        errors: Array,
    },
}
</script>
