<template>
    <div>
        <form v-if="loaded" @submit.prevent="handleSubmit" autocomplete="off" ref="form">
            <SettingsToolDefault
                v-if="visualized === 'stacked'"
                v-bind="params"
                :panels="panels"
                :saving="saving"
                @update-last-retrieved-at-timestamp="updateLastRetrievedAtTimestamp"
            />
            <SettingsToolAccordion
                v-if="visualized === 'accordion'"
                v-bind="params"
                :panels="panels"
                :saving="saving"
                @update-last-retrieved-at-timestamp="updateLastRetrievedAtTimestamp"
            />
        </form>
    </div>
</template>

<script>
import SettingsToolDefault from "./SettingsToolDefault";
import SettingsToolAccordion from "./SettingsToolAccordion";

export default {
    components: {
        SettingsToolDefault,
        SettingsToolAccordion
    },
    data: () => ({
        saving: false,
        loaded: false,
        params: {},
        visualized: "stacked",
        panels: []
    }),

    mounted() {
        Nova.request()
            .get("/nova-vendor/settings-tool/fields", {
                params: {
                    editing: true,
                    editMode: "create"
                }
            })
            .then(response => {
                const { data: { data = {} } = {} } = response;
                console.log("object", data);
                const values = data.values;
                this.loaded = true;
                this.params = data.settings;
                this.visualized = data.settings.visualized;
                this.panels = data.panels.map(panel => {
                    return {
                        ...panel,
                        fields: panel.fields.map(field => {
                            if (values[field.attribute]) {
                                return {
                                    ...field,
                                    value: values[field.attribute]
                                };
                            }

                            return field;
                        })
                    };
                });
            });
    },

    computed: {
        /**
         * Create the form data for creating the resource.
         */
        updateResourceFormData() {
            return _.tap(new FormData(), formData => {
                _(this.panels).each(panel => {
                    _(panel.fields).each(field => {
                        field.fill(formData);
                    });
                });

                formData.append("_method", "POST");
                formData.append("_retrieved_at", this.lastRetrievedAt);
            });
        }
    },

    methods: {
        updateLastRetrievedAtTimestamp() {
            this.lastRetrievedAt = Math.floor(new Date().getTime() / 1000);
        },
        handleSubmit(e) {
            if (this.$refs.form.reportValidity()) {
                this.saving = true;
                Nova.request()
                    .post(
                        "/nova-vendor/settings-tool",
                        this.updateResourceFormData
                    )
                    .then(response => {
                        this.saving = false;
                        this.$toasted.show(this.__("Settings saved!"), {
                            type: "success"
                        });
                    })
                    .catch(error => {
                        this.saving = false;
                        console.log(error.response);

                        if (error.response.status == 422) {
                            Nova.error(
                                this.__(
                                    "There was a problem submitting the form."
                                )
                            );
                        }

                        if (error.response.status == 409) {
                            Nova.error(
                                this.__(
                                    "Another user has updated this resource since this page was loaded. Please refresh the page and try again."
                                )
                            );
                        }
                    });
            }
        }
    }
};
</script>
