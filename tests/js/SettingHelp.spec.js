import { shallowMount } from '@vue/test-utils'
import SettingHelp from '@/SettingHelp'

describe('SettingHelp', () => {

    it('displays the setting help text', () => {
        let wrapper = shallowMount(SettingHelp, {
            slots: {
                default: 'This is the fancy setting.',
            },
        })

        expect(wrapper.text())
            .toBe('This is the fancy setting.')
    })

    it('can render HTML help text', () => {
        let wrapper = shallowMount(SettingHelp, {
            slots: {
                default: 'Read more about this fancy setting in <a href="/docs">the docs</a>.',
            },
        })

        expect(wrapper.html())
            .toBe('<div class="help-text">Read more about this fancy setting in <a href="/docs">the docs</a>.</div>')
    })

})
