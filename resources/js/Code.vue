<template>
    <div class="flex border-b border-40">

        <setting-label>
            {{ __(name) }}
        </setting-label>

        <div class="w-3/4 py-6 px-8">

            <div class="form-input-bordered px-0 overflow-hidden">

                <textarea ref="textarea"/>

            </div>

            <setting-info
                v-if="description || link.text"
                :text="link.text || ''"
                :url="link.url || ''"
                class="pt-3"
            >
                {{ __(description) }}
            </setting-info>

        </div>

    </div>
</template>

<script>
import SettingLabel from './Label'
import SettingInfo from './Info'

import CodeMirror from 'codemirror'

import 'codemirror/mode/php/php'
import 'codemirror/mode/javascript/javascript'
import 'codemirror/mode/css/css'
import 'codemirror/mode/htmlmixed/htmlmixed'
import 'codemirror/mode/markdown/markdown'
import 'codemirror/mode/yaml/yaml'
import 'codemirror/mode/shell/shell'

export default {
    props: {
        name: String,
        setting: Object,
        description: String,
        language: String,
        link: Object
    },

    components: {
        SettingLabel,
        SettingInfo
    },

    data: () => ({
        codemirror: null,
    }),

    computed: {
        doc() {
            return this.codemirror.getDoc()
        },
    },

    mounted() {
        this.codemirror = CodeMirror.fromTextArea(this.$refs.textarea, {
            indentUnit: 4,
            lineNumbers: true,
            mode: this.language,
            theme: 'solarized light',
            viewportMargin: Infinity,
        })

        this.doc.on('change', (cm, change) => {
            this.$emit('input', {
                key: this.setting.key,
                value: cm.getValue(),
            })
        })

        this.doc.setValue(this.setting.value || '')
    },
}
</script>

<style src="codemirror/lib/codemirror.css"/>
<style src="codemirror/theme/solarized.css"/>

<style>
@import url(https://cdn.jsdelivr.net/gh/tonsky/FiraCode@1.206/distr/fira_code.css);

.CodeMirror {
    font-family: "Fira Code", Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
    font-size: 15px;
    line-height: 1.75;
    font-weight: 500;
    -moz-box-shadow: none !important;
    -webkit-box-shadow: none !important;
    box-shadow: none !important;
}
</style>
