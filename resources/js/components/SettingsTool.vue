<template>
    <div class="relative">

        <div v-if="loading" class="rounded-lg flex items-center justify-center absolute pin z-50 bg-40 -m-4">
            <loader class="text-60" />
        </div>

        <div v-for="group in settingConfig">

            <heading class="mb-6">{{ __(group.name) }}</heading>

            <card class="relative overflow-hidden mb-8">

                <div v-for="setting in group.settings">

                    <toggle-setting
                        v-if="setting.type == 'toggle'"
                        :name="setting.name"
                        :description="setting.description || ''"
                        :link="setting.link || {}"
                        :setting="{ key: setting.key, value: settings[setting.key] }"
                        @toggle="handleToggle"
                    />

                    <text-setting
                        v-if="setting.type == 'text'"
                        :name="setting.name"
                        :description="setting.description || ''"
                        :link="setting.link || {}"
                        :setting="{ key: setting.key, value: settings[setting.key] }"
                        @input="handleInput"
                    />

                    <text-area-setting
                        v-if="setting.type == 'textarea'"
                        :name="setting.name"
                        :description="setting.description || ''"
                        :link="setting.link || {}"
                        :setting="{ key: setting.key, value: settings[setting.key] }"
                        @input="handleInput"
                    />

                </div>

                <div class="bg-30 flex px-8 py-4">
                    <progress-button
                        class="ml-auto"
                        @click.native="saveAndReload(group.name)"
                        :processing="saving == group.name"
                    >
                        {{ __('Save') }}
                    </progress-button>
                </div>

            </card>

        </div>

    </div>
</template>

<script>
import ToggleSetting from './partials/Toggle'
import TextSetting from './partials/Text'
import TextAreaSetting from './partials/Textarea'

export default {
    components: {
        ToggleSetting,
        TextSetting,
        TextAreaSetting
    },

    data: () => ({
        loading: true,
        saving: '',
        settings: {},
        settingConfig: [],
    }),

    mounted() {
        const vm = this

        Nova.request().get('/nova-vendor/settings-tool')
            .then(response => {
                vm.settingConfig = response.data.settingConfig
                vm.settings = response.data.settings
                setTimeout(() => { vm.loading = false }, 200)
            })
    },

    methods: {
        handleToggle(key) {
            this.settings[key] = !this.settings[key]
        },

        handleInput(input) {
            this.settings[input.key] = input.value
        },

        saveAndReload(groupName) {
            this.saving = groupName

            let settingsToUpdate = {}

            let keys = this.settingConfig
                .filter(g => g.name == groupName)[0].settings
                .map(settingItem => settingItem.key)

            keys.forEach(key => {
                settingsToUpdate[key] = this.settings[key]
            })

            Nova.request().post('/nova-vendor/settings-tool', { settings: settingsToUpdate })
                .then(response => {
                    if (response.status == 202) {
                        this.saving = ''
                        this.$toasted.show(__('Settings saved!'), { type: 'success' })
                    }
                })
                .catch(error => {
                    console.log(error)
                    this.saving = ''
                })
        }
    }
}
</script>
