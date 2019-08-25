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

                    <code-setting
                        v-if="setting.type == 'code'"
                        :name="setting.name"
                        :language="setting.language || 'javascript'"
                        :description="setting.description || ''"
                        :link="setting.link || {}"
                        :setting="{ key: setting.key, value: settings[setting.key] }"
                        @input="handleInput"
                    />

                   <file-setting
                        v-if="setting.type == 'file'"
                        :name="setting.name"
                        :description="setting.description || ''"
                        :link="setting.link || {}"
                        :setting="{ key: setting.key, value: settings[setting.key] }"
                        @input="handleFileInput"
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
import CodeSetting from './partials/Code'
import FileSetting from './partials/File'

export default {
    components: {
        ToggleSetting,
        TextSetting,
        TextAreaSetting,
        CodeSetting,
        FileSetting,
    },

    data: () => ({
        loading: true,
        saving: '',
        settings: {},
        settingConfig: [],
        files: new FormData(),
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

        handleFileInput(input) {
            this.files.set(input.key, input.value)
        },

        saveAndReload(groupName) {
            this.saving = groupName

            const settingsToUpdate = new FormData()

            let keys = this.settingConfig
                .filter(g => g.name == groupName)[0].settings
                .map(settingItem => settingItem.key)

            keys.forEach(key => {
                settingsToUpdate.set(key, this.settings[key])
            })

            for (const file of this.files.entries()) {
                settingsToUpdate.set(file[0], file[1])
            }

            Nova.request().post('/nova-vendor/settings-tool', settingsToUpdate)
                .then(response => {
                    if (response.status == 202) {
                        this.saving = ''
                        this.$toasted.show(this.__('Settings saved!'), { type: 'success' })
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
