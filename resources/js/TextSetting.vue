<template>
    <default-setting :setting="setting" :errors="errors || []">

        <template slot="setting">

            <input
                :id="setting.key"
                :value="setting.value"
                @input="$emit('input', { key: setting.key, value: $event.target.value })"
                class="w-full form-control form-input form-input-bordered"
            />

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
