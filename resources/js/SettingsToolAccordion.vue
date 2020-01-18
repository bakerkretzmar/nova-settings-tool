<template>
    <div class="form-inner accordion-form">
        <div class="flex align-stretch" v-if="activePanel">
            <div class="accordion-form__sidebar card mr-4">
                <ul class="accordion-form__sidebar__list">
                    <li
                        v-for="panel in panels"
                        :key="panel.name"
                        :class="{ active: panel.name === activePanel.name }"
                        @click="setActivePanel(panel)"
                    >{{ panel.name }}</li>
                </ul>
                <div class="flex justify-end mt-4 mr-4">
                    <progress-button type="submit">
                        {{
                        __("Save")
                        }}
                    </progress-button>
                </div>
            </div>
            <div class="accordion-form__content">
                <div
                    class="accordion-form__content__item"
                    v-for="panel in panels"
                    :key="panel.name"
                    :class="{ active: panel.name === activePanel.name }"
                >
                    <transition name="fade">
                        <div
                            class="accordion-form__content__item__inner"
                            v-show="panel.name === activePanel.name"
                            v-if="!needsToBeReloaded(panel.fields)"
                        >
                            <form-panel
                                @update-last-retrieved-at-timestamp="
                                    $emit(
                                        'update-last-retrieved-at-timestamp',
                                        $event
                                    )
                                "
                                :panel="panel"
                                :name="panel.name"
                                :fields="filterOutDetailFields(panel.fields)"
                                :resourceName="'Setting'"
                                :resourceId="0"
                                :viaResource="''"
                                :viaResourceId="''"
                                :viaRelationship="''"
                                mode="form"
                            />
                            <DetailFieldsArea :panel="panel" />
                        </div>
                    </transition>
                </div>
                <div class="flex justify-end mt-4">
                    <progress-button type="submit">
                        {{
                        __("Save")
                        }}
                    </progress-button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import settingsViewMixins, { DetailFieldsArea } from "./settingsViewMixins";

export default {
    name: "SettingsToolAccordion",
    mixins: [settingsViewMixins],
    components: { DetailFieldsArea },
    props: {
        panels: Array,
        remember_active: Boolean
    },

    data: () => ({
        activePanel: null,
        reload: false
    }),

    mounted() {
        if (this.remember_active) {
            const hasActivePanel = sessionStorage.getItem(
                "settings-tool-accordion-active-panel"
            );
            if (hasActivePanel) {
                try {
                    this.activePanel = JSON.parse(hasActivePanel);
                } catch (e) {
                    this.activePanel = null;
                }
            }
        }

        if (!this.activePanel) {
            this.activePanel = this.panels[0];
        }
    },

    methods: {
        needsToBeReloaded(fields = []) {
            if (this.reload) {
                const filterOut = ["code-field"];

                return (
                    fields.filter(field => {
                        return filterOut.indexOf(field.component) >= 0;
                    }).length > 0
                );
            }

            return false;
        },

        setActivePanel(panel) {
            this.$emit("panelWillChanged", panel, this.activePanel);

            this.activePanel = panel;

            // Code Field needs to be reloaded before it will render.
            this.reload = true;

            if (this.remember_active) {
                sessionStorage.setItem(
                    "settings-tool-accordion-active-panel",
                    JSON.stringify(panel)
                );
            }

            // Waiting for next tick because code field has to reload.
            this.$nextTick(() => {
                this.reload = false;
            });
        }
    }
};
</script>

<style lang="scss" scoped>
.accordion-form__content {
    flex-grow: 1;
    flex-shrink: 1;
}
.accordion-form__sidebar {
    min-width: 250px;
    min-height: 50vh;

    @media (max-width: 767px) {
        min-width: 0px;
        min-height: 0px;
    }
}
.accordion-form__sidebar__list {
    list-style: none;
    padding: 0;
    margin: 0;
}
.accordion-form__sidebar__list li {
    cursor: pointer;
    font-size: 18px;
    font-weight: bold;
    line-height: 1.3;
    padding: 10px 30px;
    margin: 0;
    border-bottom: 1px solid #cccccc;

    &:hover {
        background-color: var(--sidebar-icon);
    }
}
.accordion-form__sidebar__list li.active {
    background-color: var(--logo);
    color: var(--white);
}

/* Enter and leave animations can use different */
/* durations and timing functions.              */
.fade-enter-active {
    transition: all 0.3s ease;
}
.fade-leave-active {
    transition: none;
}
.fade-enter, .fade-leave-to
/* .fade-leave-active below version 2.1.8 */ {
    transform: translateX(10px);
    opacity: 0;
}
</style>
