<template>
    <Head :title="title" />
    <template v-for="(keys, panel) in panels">
        <Heading class="mb-6">{{ __(panelName(panel)) }}</Heading>
        <Card class="overflow-hidden mb-8">
            <Component
                v-for="(setting, index) in keys"
                :index="index"
                :key="settings[setting].key"
                :is="`${settings[setting].type}Setting`"
                :setting="settings[setting]"
                @update="updateSetting"
            />
        </Card>
    </template>
    <div class="flex">
        <LoadingButton class="ml-auto" @click="saveSettings" :processing="saving">
            {{ __('Save') }}
        </LoadingButton>
    </div>
</template>

<script>
import mapValues from 'lodash/mapValues';
import CodeSetting from './CodeSetting.vue';
import NumberSetting from './NumberSetting.vue';
import SelectSetting from './SelectSetting.vue';
import TextSetting from './TextSetting.vue';
import TextareaSetting from './TextareaSetting.vue';
import ToggleSetting from './ToggleSetting.vue';

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
    mounted() {
        Nova.request()
            .get('/nova-vendor/nova-settings-tool')
            .then(({ data }) => {
                this.title = this.__(data.title);
                this.settings = data.settings;
                this.panels = data.panels;
            });
    },
    methods: {
        updateSetting(data) {
            this.settings[data.key].value = data.value;
        },
        saveSettings() {
            this.saving = true;
            let settings = mapValues(this.settings, 'value');
            Nova.request()
                .post('/nova-vendor/nova-settings-tool', settings)
                .then(() => Nova.success(this.__('Settings saved!')))
                .catch(() => Nova.error(this.__('Failed to save settings!')))
                .finally(() => (this.saving = false));
        },
        panelName(value) {
            return value === '_default' ? (Object.keys(this.panels).length > 1 ? 'Other' : 'Settings') : value;
        },
    },
};
</script>
