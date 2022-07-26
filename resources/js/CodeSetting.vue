<template>
    <DefaultSetting :setting="setting" :errors="errors || []">
        <div class="form-input form-input-bordered px-0 overflow-hidden">
            <textarea ref="txt" :id="setting.key" />
        </div>
    </DefaultSetting>
</template>

<script>
import CodeMirror from 'codemirror';
import 'codemirror/keymap/vim';
import 'codemirror/mode/css/css';
import 'codemirror/mode/dockerfile/dockerfile';
import 'codemirror/mode/htmlmixed/htmlmixed';
import 'codemirror/mode/javascript/javascript';
import 'codemirror/mode/markdown/markdown';
import 'codemirror/mode/nginx/nginx';
import 'codemirror/mode/php/php';
import 'codemirror/mode/shell/shell';
import 'codemirror/mode/sql/sql';
import 'codemirror/mode/vue/vue';
import 'codemirror/mode/xml/xml';
import 'codemirror/mode/yaml/yaml';
import 'codemirror/mode/yaml-frontmatter/yaml-frontmatter';
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
    codemirror: null,
    mounted() {
        this.codemirror = CodeMirror.fromTextArea(this.$refs.txt, {
            indentUnit: 4,
            lineNumbers: true,
            mode: this.setting.language,
            theme: 'solarized light',
            viewportMargin: Infinity,
        });
        this.codemirror?.getDoc()?.on('change', (cm, changeObj) => {
            this.$emit('update', {
                key: this.setting.key,
                value: cm.getValue(),
            });
        });
        this.codemirror?.getDoc()?.setValue(this.setting.value ?? '');
        this.codemirror?.setSize('100%');
    },
};
</script>

<style>
@import 'codemirror/lib/codemirror.css';
@import 'codemirror/theme/solarized.css';
@import url(https://cdn.jsdelivr.net/gh/tonsky/FiraCode@1.206/distr/fira_code.css);

.CodeMirror {
    font-family: 'Fira Code', Menlo, Monaco, Consolas, 'Liberation Mono', 'Courier New', monospace;
    font-size: 14px;
    line-height: 1.75;
    font-weight: 500;
    -moz-box-shadow: none !important;
    -webkit-box-shadow: none !important;
    box-shadow: none !important;
}
.cm-s-solarized.cm-s-light {
    background-color: #fdf6e3 !important;
    color: #657b83 !important;
}
</style>
