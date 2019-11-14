<template>
    <default-setting :setting="setting" :errors="errors || []">

        <template slot="setting">
            <div class="form-input px-0">
                <div :id="setting.key" v-html="this.contents"></div>
            </div>
        </template>

    </default-setting>
</template>

<script>
import DefaultSetting from './DefaultSetting'

import Quill from 'quill/core';

import Toolbar from 'quill/modules/toolbar';
import Snow from 'quill/themes/snow';

import Bold from 'quill/formats/bold';
import Italic from 'quill/formats/italic';
import Underline from 'quill/formats/underline';
import Link from 'quill/formats/link';
import List, { ListItem } from 'quill/formats/list';

Quill.register({
    'modules/toolbar': Toolbar,
    'themes/snow': Snow,
    'formats/bold': Bold,
    'formats/italic': Italic,
    'formats/underline': Underline,
    'formats/link': Link,
    'formats/list': List,
    'formats/list/item': ListItem,
});

export default {
    components: { DefaultSetting },

    props: {
        setting: Object,
        errors: Array,
    },

    data: () => ({
        editor: null,
        contents: null
    }),

    created(){
        /**
         * Used to avoid Vue reactivity updating the contents, we parse and stringify
         *
         * @type {any}
         */
        this.contents = JSON.parse(JSON.stringify(this.setting.value))
    },

    mounted() {
        this.editor = new Quill(document.getElementById(this.setting.key), {
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline', 'link',],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ]
            },
            theme: 'snow'
        })

        this.editor.on('text-change', () => {
            this.$emit('update', {
                key: this.setting.key,
                value: this.editor.root.innerHTML,
            })
        });


    },
}
</script>

<style lang="scss" scoped>
    @import '~quill/dist/quill.core.css';
    @import '~quill/dist/quill.snow.css';

    .form-input {
        &:focus, &:active {
            box-shadow: none;
        }

    }

    .ql-toolbar {
        border-radius: .5rem .5rem 0 0;
    }

    .ql-container {
        height:300px;
        border-radius: 0 0 .5rem .5rem ;
    }
</style>
