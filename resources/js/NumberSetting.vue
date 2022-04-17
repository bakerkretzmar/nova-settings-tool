<template>
    <DefaultSetting :setting="setting" :errors="errors || []">
        <input
            type="number"
            :id="setting.key"
            :value="setting.value"
            @input="
                $emit('update', {
                    key: setting.key,
                    value: Number($event.target.value),
                })
            "
            :placeholder="setting.placeholder"
            class="w-full form-control form-input form-input-bordered"
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
