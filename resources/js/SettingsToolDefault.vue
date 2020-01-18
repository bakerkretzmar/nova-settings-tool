<template>
    <div class="form-inner">
        <form-panel
                v-for="panel in panels"
                @update-last-retrieved-at-timestamp="$emit('update-last-retrieved-at-timestamp', $event)"
                :panel="panel"
                :name="panel.name"
                :key="panel.name"
                :fields="panel.fields"
                mode="form"
                class="mb-8"
            />
            <div class="flex justify-end">
                <progress-button type="submit">{{ __('Save') }}</progress-button>
            </div>
    </div>
</template>

<script>
export default {
    name: 'SettingsToolDefault',

    props: {
        panels: Array
    },
}
</script>
