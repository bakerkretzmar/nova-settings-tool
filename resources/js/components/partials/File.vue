<template>
    <div class="flex border-b border-40">

        <setting-label>{{ __(name) }}</setting-label>

        <div class="w-1/2 py-6 px-8">
            <div v-if="setting.value" class="mb-6">
                <div class="card flex item-center relative border border-lg border-50 overflow-hidden p-4">
                    <span class="truncate mr-3"> {{ __(setting.value) }} </span>
                </div>
            </div>
            <span class="form-file mr-4">
                <input
                    :id="setting.key"
                    @change="change"
                    type="file"
                    class="form-file-input select-none">
                <label :for="setting.key" class="form-file-btn btn btn-default btn-primary select-none"> {{ __('Choose File') }} </label>
            </span>
            <span v-if="!file" class="text-gray-50 select-none"> {{ __('no file selected') }} </span>
            <span v-if="file" class="text-gray-50 select-none"> {{ __(file) }} </span>

            <setting-info v-if="description || link.text" :text="link.text || ''" :url="link.url || ''" class="pt-3">{{ __(description) }}</setting-info>
        </div>
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

    data: () => ({
        file: null
    }),

    components: {
        SettingLabel,
        SettingInfo
    },

    methods: {
        change(e) {
            this.file = e.target.files[0].name

            this.$emit('input', {
                key: this.setting.key,
                value: e.target.files[0]
            })
        }
    }
}
</script>
