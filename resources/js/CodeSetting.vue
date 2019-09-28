<template>
    <default-setting :setting="setting" :errors="errors || []">

        <template slot="setting">

            <div class="form-input-bordered px-0 overflow-hidden">
                <textarea ref="textarea" :id="setting.key"/>
            </div>

        </template>

    </default-setting>
</template>

<script>
import DefaultSetting from './DefaultSetting'

import CodeMirror from 'codemirror'
import 'codemirror/mode/php/php'
import 'codemirror/mode/javascript/javascript'
import 'codemirror/mode/css/css'
import 'codemirror/mode/htmlmixed/htmlmixed'
import 'codemirror/mode/markdown/markdown'
import 'codemirror/mode/yaml/yaml'
import 'codemirror/mode/shell/shell'

export default {
    components: { DefaultSetting },

    props: {
        setting: Object,
        errors: Array,
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
            mode: this.setting.language,
            theme: 'solarized light',
            viewportMargin: Infinity,
        })

        this.doc.setValue(this.setting.value || '')

        this.doc.on('change', (cm, change) => {
            this.$emit('update', {
                key: this.setting.key,
                value: cm.getValue(),
            })
        })
    },
}
</script>

<style src="codemirror/lib/codemirror.css"/>
<style src="codemirror/theme/solarized.css"/>

<style>
@import url(https://cdn.jsdelivr.net/gh/tonsky/FiraCode@1.206/distr/fira_code.css);

.CodeMirror {
    font-family: "Fira Code", Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
    font-size: 14px;
    line-height: 1.75;
    font-weight: 500;
    -moz-box-shadow: none !important;
    -webkit-box-shadow: none !important;
    box-shadow: none !important;
}
</style>
