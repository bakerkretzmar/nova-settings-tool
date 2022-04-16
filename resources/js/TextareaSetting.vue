<template>
    <DefaultSetting :setting="setting" :errors="errors || []">
        <textarea
            :id="setting.key"
            :value="setting.value"
            @input="
                $emit('update', {
                    key: setting.key,
                    value: $event.target.value,
                })
            "
            :placeholder="setting.placeholder"
            class="w-full form-control form-input form-input-bordered py-3 h-auto"
            rows="6"
        />
    </DefaultSetting>
</template>

<script>
import DefaultSetting from './DefaultSetting.vue';

export default {
    components: {
        DefaultSetting,
    },
    props: {
        setting: Object,
        errors: Array,
    },
    emits: ['update'],
};
</script>
