import { shallowMount } from '@vue/test-utils'
import SettingLabel from '@/SettingLabel'

describe('SettingLabel', () => {

    it('displays the setting label', () => {
        let wrapper = shallowMount(SettingLabel, {
            slots: {
                default: 'Fancy Setting',
            },
        })

        expect(wrapper.text())
            .toBe('Fancy Setting')
    })

    it('sets `for` to the setting key', () => {
        let wrapper = shallowMount(SettingLabel, {
            propsData: {
                labelFor: 'fancy-setting',
            },
        })

        expect(wrapper.attributes('for'))
            .toBe('fancy-setting')
    })

})
