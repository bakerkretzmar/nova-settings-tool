import { shallowMount } from '@vue/test-utils'
import NumberSetting from '@/NumberSetting'

describe('Text setting', () => {

    it('sets `id` to the setting key', () => {
        const wrapper = shallowMount(NumberSetting, {
            propsData: {
                setting: {
                    key: 'number_setting',
                },
            },
        })

        expect(wrapper.find('.form-input').attributes('id'))
            .toBe('number_setting')
    })

    it('displays the passed setting value', () => {
        const wrapper = shallowMount(NumberSetting, {
            propsData: {
                setting: {
                    key: 'number_setting',
                    value: 4,
                },
            },
        })

        expect(wrapper.find('.form-input').element.value)
            .toBe('4')
    })

    it('emits an updated setting object on input', () => {
        const wrapper = shallowMount(NumberSetting, {
            propsData: {
                setting: {
                    label: 'Lucky Number',
                    key: 'lucky_number',
                },
            },
        })

        wrapper.find('.form-input').setValue(42)
        wrapper.find('.form-input').trigger('input')

        expect(wrapper.emitted().update[0])
            .toEqual([
                {
                    key: 'lucky_number',
                    value: 42,
                }
            ])
    })

})
