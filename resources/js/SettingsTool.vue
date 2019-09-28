<template>
    <div>

        <!-- <div v-for="group in settingConfig"> -->

            <!-- <heading class="mb-6">
                {{ __(group.name) }}
            </heading> -->
            <heading class="mb-6">
                Settings
            </heading>

            <card class="relative overflow-hidden mb-8">

                <!-- <div v-for="setting in group.settings"> -->

                   <!--  <toggle-setting
                        v-if="setting.type == 'toggle'"
                        :name="setting.name"
                        :description="setting.description || ''"
                        :link="setting.link || {}"
                        :setting="{ key: setting.key, value: settings[setting.key] }"
                        @toggle="handleToggle"
                    /> -->

                    <!-- The API I *really want*: -->

                    <component
                        v-for="setting in settings"
                        :key="setting.key"
                        :is="`${setting.type}-setting`"
                        :setting="setting"
                        @update="updateSetting"
                    />

<!--                     <code-setting
                        v-if="setting.type == 'code'"
                        :name="setting.name"
                        :language="setting.language || 'javascript'"
                        :description="setting.description || ''"
                        :link="setting.link || {}"
                        :setting="{ key: setting.key, value: settings[setting.key] }"
                        @input="handleInput"
                    />
 -->
                <!-- </div> -->
<!--
                <div class="bg-30 flex px-8 py-4">
                    <progress-button
                        class="ml-auto"
                        @click.native="saveAndReload(group.name)"
                        :processing="saving == group.name"
                    >
                        {{ __('Save') }}
                    </progress-button>
                </div> -->

            </card>

        <!-- </div> -->

    </div>
</template>

<script>
import CodeSetting from './Code'
import TextSetting from './TextSetting'
import TextareaSetting from './TextareaSetting'
import ToggleSetting from './ToggleSetting'

export default {
    components: {
        CodeSetting,
        TextSetting,
        TextareaSetting,
        ToggleSetting,
    },

    data: () => ({
        saving: '',
        settings: {},
        settingConfig: [],
    }),

    mounted() {
        Nova.request().get('/nova-vendor/settings-tool')
            .then(res => this.settings = res.data)
    },

    methods: {
        updateSetting(data) {
            this.settings[data.key] = data.value
        },

        handleToggle(key) {
            this.settings[key] = !this.settings[key]
        },

        handleInput(input) {
            this.settings[input.key] = input.value
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
