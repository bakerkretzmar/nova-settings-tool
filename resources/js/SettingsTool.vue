<template>
    <div>
        <form
            v-if="loaded"
            @submit.prevent="handleSubmit"
            autocomplete="off"
            ref="form"
        >
            <SettingsToolDefault
                v-if="visualized === 'stacked'"
                v-bind="params"
                :panels="panels"
                :saving="saving"
                @update-last-retrieved-at-timestamp="
                    updateLastRetrievedAtTimestamp
                "
            />
            <SettingsToolAccordion
                v-if="visualized === 'accordion'"
                v-bind="params"
                :panels="panels"
                :saving="saving"
                @panelWillChanged="handlePanelWillChanged"
                @update-last-retrieved-at-timestamp="
                    updateLastRetrievedAtTimestamp
                "
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
        this.getFields();
    },

    computed: {
        /**
         * Create the form data for creating the resource.
         */
        updateResourceFormData() {
            return _.tap(new FormData(), formData => {
                _(this.panels).each(panel => {
                    _(panel.fields).each(field => {
                        if (field && typeof field.fill == "function") {
                            field.fill(formData);
                        }
                    });
                });

                formData.append("_method", "POST");
                formData.append("_retrieved_at", this.lastRetrievedAt);
            });
        }
    },

    methods: {
        /**
         * Load all setting fields with configs.
         */
        getFields() {
            this.loaded = false;

            return Nova.request()
                .get("/nova-vendor/settings-tool/fields", {
                    params: {
                        editing: true,
                        editMode: "create"
                    }
                })
                .then(response => {
                    const { data: { data = {} } = {} } = response;
                    const values = data.values;
                    this.loaded = true;
                    this.params = data.settings;
                    this.visualized = data.settings.visualized;
                    this.panels = data.panels.map(panel => {
                        return {
                            ...panel,
                            component: "panel",
                            showToolbar: true,
                            limit: null
                        };
                    });
                });
        },

        handlePanelWillChanged(toPanel, fromPanel) {
            const formData = this.updateResourceFormData;

            if (fromPanel && fromPanel.fields.length > 0) {
                this.panels = this.panels.map(panel => {
                    if (panel.name === fromPanel.name) {
                        return {
                            ...panel,
                            fields: panel.fields.map(field => {
                                return {
                                    ...field,
                                    value: formData.get(field.attribute)
                                };
                            })
                        };
                    }
                    return panel;
                });
            }
        },

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

                        this.getFields();
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
