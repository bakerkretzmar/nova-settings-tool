import { shallowMount } from '@vue/test-utils'
import Info from '@/Info'

describe('Info', () => {

    const methods = {
        __: (s) => s,
    }

    it('can display text', () => {
        let wrapper = shallowMount(Info, {
            propsData: {
                text: 'Info about the setting',
            },
            methods,
        })

        expect(wrapper.text())
            .toBe('Info about the setting')
    }),

    it('can display link', () => {
        let wrapper = shallowMount(Info, {
            propsData: {
                text: 'Info and a <a href="/docs">link to docs</a>.',
            },
            methods,
        })

        expect(wrapper.html())
            .toBe('<p class="text-sm leading-normal text-80 italic">Info and a <a href="/docs">link to docs</a>.</p>')
    })

})
