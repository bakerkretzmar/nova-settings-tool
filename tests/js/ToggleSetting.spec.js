import { shallowMount } from '@vue/test-utils'
import ToggleSetting from '@/ToggleSetting'

describe('Toggle setting', () => {

    it('displays the passed setting value', () => {
        const wrapper = shallowMount(ToggleSetting, {
            propsData: {
                setting: {
                    key: 'toggle',
                    value: true,
                },
            },
        })

        expect(wrapper.find('.toggle').element.classList)
            .toContain('on')
    })

    it('defaults to `false`', () => {
        const wrapper = shallowMount(ToggleSetting, {
            propsData: {
                setting: {
                    key: 'toggle',
                },
            },
        })

        expect(wrapper.find('.toggle').element.classList)
            .not.toContain('on')
    })

    it('emits an updated setting object on click', () => {
        const wrapper = shallowMount(ToggleSetting, {
            propsData: {
                setting: {
                    key: 'toggle_setting',
                    value: false,
                },
            },
        })

        wrapper.find('.toggle').trigger('click')

        expect(wrapper.emitted().update[0])
            .toEqual([
                {
                    key: 'toggle_setting',
                    value: true,
                }
            ])
    })

})
