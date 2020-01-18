const detailComponents = ["sparkline-field", "status-field"];

const settingsViewMixins = {
    methods: {
        resolveDetailComponentName(field = {}) {
            return `detail-${field.component}`;
        },
        filterOutDetailFields(fields = []) {
            return fields.filter(
                field => detailComponents.indexOf(field.component) === -1
            );
        },
        onlyDetailFields(fields = []) {
            return fields.filter(
                field => detailComponents.indexOf(field.component) >= 0
            );
        }
    }
};

export const DetailFieldsArea = {
    name: "DetailFieldsArea",
    mixins: [settingsViewMixins],
    props: {
        panel: Object,
        visualized: String
    },
    template: `<div
        class="accordion-form__content__item__detail-fields mt-4"
        v-if="onlyDetailFields(panel.fields).length > 0"
    >
        <div>
            <heading :level="1" class="mb-3"><span v-if="visualized === 'stacked'">{{ panel.name }} - </span>{{ __('Detail fields') }}</heading>

            <card class="mb-6 py-3 px-6">
                <component
                    v-for="(field, index) in onlyDetailFields(panel.fields)"
                    :key="index"
                    :is="resolveDetailComponentName(field)"
                    :resource-name="''"
                    :resource-id="''"
                    :field="field"
                />
            </card>
        </div>
    </div>`
};

export default settingsViewMixins;
