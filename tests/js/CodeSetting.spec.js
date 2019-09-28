import { config, mount } from '@vue/test-utils'
import CodeSetting from '@/CodeSetting'

import Vue from 'vue'
Vue.mixin({ methods: { __: (s) => s } })

// CodeMirror needs this
global.document.body.createTextRange = function() {
    return {
        setEnd: function() {},
        setStart: function() {},
        getBoundingClientRect: function() {
            return { right: 0 };
        },
        getClientRects: function() {
            return {
                length: 0,
                left: 0,
                right: 0
            }
        }
    }
}

describe('Text setting', () => {

    it('sets `id` to the setting key', () => {
        const wrapper = mount(CodeSetting, {
            propsData: {
                setting: {
                    key: 'code',
                },
            },
        })

        expect(wrapper.find('textarea').attributes('id'))
            .toBe('code')

        wrapper.destroy()
    })

    it('displays the passed setting value', () => {
        const wrapper = mount(CodeSetting, {
            propsData: {
                setting: {
                    key: 'code',
                    value: "function hello () {\n    return 'world'\n}",
                },
            },
        })

        expect(wrapper.vm.doc.getValue())
            .toBe("function hello () {\n    return 'world'\n}")

        wrapper.destroy()
    })

    it('emits an updated setting object on input', () => {
        const wrapper = mount(CodeSetting, {
            propsData: {
                setting: {
                    label: 'Code Setting',
                    key: 'code',
                },
            },
        })

        wrapper.vm.doc.setValue('return null;')

        expect(wrapper.emitted().update[0])
            .toEqual([
                {
                    key: 'code',
                    value: 'return null;'
                }
            ])

        wrapper.destroy()
    })

})
