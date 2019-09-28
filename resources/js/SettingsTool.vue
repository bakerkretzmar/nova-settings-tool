<template>
    <div>

            <!-- <heading class="mb-6">
                {{ __(group.name) }}
            </heading> -->
            <heading class="mb-6">
                Settings
            </heading>

            <card class="relative overflow-hidden mb-8">

                <!-- <div v-for="setting in group.settings"> -->

                    <!-- The API I *really want*: -->

                    <component
                        v-for="setting in settings"
                        :key="setting.key"
                        :is="`${setting.type}-setting`"
                        :setting="setting"
                        @update="updateSetting"
                    />

                <!-- </div> -->

            </card>

            <div class="flex items-center">

                <progress-button class="ml-auto" @click.native="saveSettings" :processing="saving">
                    {{ __('Save') }}
                </progress-button>

            </div>

    </div>
</template>

<script>
import CodeSetting from './CodeSetting'
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
        saving: false,
        settings: {},
    }),

    mounted() {
        Nova.request().get('/nova-vendor/settings-tool')
            .then(response => this.settings = response.data)
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
        }
    }
}
</script>
