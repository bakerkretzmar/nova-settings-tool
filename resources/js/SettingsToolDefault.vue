<template>
    <div class="form-inner">
        <div class="panel__wrapper" v-for="panel in panels" :key="panel.name">
            <form-panel
                @update-last-retrieved-at-timestamp="
                $emit('update-last-retrieved-at-timestamp', $event)
            "
                :panel="panel"
                :name="panel.name"
                :fields="panel.fields"
                mode="form"
                class="mb-8"
            />
            <DetailFieldsArea visualized="stacked" :panel="panel" />
        </div>
        <div class="flex justify-end">
            <progress-button type="submit">{{ __("Save") }}</progress-button>
        </div>
    </div>
</template>

<script>
import settingsViewMixins, { DetailFieldsArea } from "./settingsViewMixins";

export default {
    name: "SettingsToolDefault",
    mixins: [settingsViewMixins],
    components: { DetailFieldsArea },

    props: {
        panels: Array
    }
};
</script>
