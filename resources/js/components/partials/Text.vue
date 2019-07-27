<template>
    <div class="flex border-b border-40">

        <setting-label>
            {{ __(label) }}
        </setting-label>

        <div class="w-1/2 py-6 px-8">

            <input
                :value="setting.value || ''"
                @input="$emit('input', { [setting.key]: $event.target.value })"
                class="w-full form-control form-input form-input-bordered"
            />

            <setting-info v-if="help" :text="__(help)" class="pt-3"/>

        </div>

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
