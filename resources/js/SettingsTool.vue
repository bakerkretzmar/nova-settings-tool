<template>
    <div>

        <template v-for="(keys, panel) in panels">

            <heading class="mb-6">
                {{ __(panelName(panel)) }}
            </heading>

            <card class="relative overflow-hidden mb-8">

                <component
                    v-for="setting in keys"
                    :key="settings[setting].key"
                    :is="`${settings[setting].type}-setting`"
                    :setting="settings[setting]"
                    @update="updateSetting"
                />

            </card>

        </template>

        <div class="flex items-center">

            <progress-button class="ml-auto" @click.native="saveSettings" :processing="saving">
                {{ __('Save') }}
            </progress-button>

        </div>

    </div>
</template>

<script>
import CodeSetting from './CodeSetting'
import NumberSetting from './NumberSetting'
import SelectSetting from './SelectSetting'
import TextSetting from './TextSetting'
import TextareaSetting from './TextareaSetting'
import ToggleSetting from './ToggleSetting'

export default {
    components: {
        CodeSetting,
        NumberSetting,
        SelectSetting,
        TextSetting,
        TextareaSetting,
        ToggleSetting,
    },

    data: () => ({
        saving: false,
        title: '',
        settings: {},
        panels: {},
    }),

    metaInfo() {
        return {
            title: this.title,
        }
    },

    mounted() {
        Nova.request().get('/nova-vendor/settings-tool')
            .then(({ data }) => {
                this.title = this.__(data.title)
                this.settings = data.settings
                this.panels = data.panels
            })
    },

    methods: {
        updateSetting(data) {
            this.settings[data.key].value = data.value
        },

        saveSettings() {
            this.saving = true

            let settings = _.mapValues(this.settings, 'value')

            Nova.request().post('/nova-vendor/settings-tool', settings)
                .then(response => {
                    this.saving = false
                    this.$toasted.show(this.__('Settings saved!'), { type: 'success' })
                })
                .catch(error => {
                    this.saving = false
                    console.log(error.response)
                })
        },

        panelName(value) {
            if (value === '_default') {
                return Object.keys(this.panels).length > 1
                    ? 'Other'
                    : 'Settings'
            }

            return value
        },
    },
}
</script>
