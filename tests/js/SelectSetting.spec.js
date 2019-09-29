import { config, shallowMount } from '@vue/test-utils'
import SelectSetting from '@/SelectSetting'

config.methods['__'] = s => s

describe('Text setting', () => {

    it('sets `id` to the setting key', () => {
        const wrapper = shallowMount(SelectSetting, {
            propsData: {
                setting: {
                    key: 'opts',
                },
            },
        })

        expect(wrapper.find('select').attributes('id'))
            .toBe('opts')
    })

    it('displays the passed setting value', () => {
        const wrapper = shallowMount(SelectSetting, {
            propsData: {
                setting: {
                    key: 'opts',
                    value: 'foo',
                    options: {
                        foo: 'Foo',
                    },
                },
            },
        })

        expect(wrapper.find('select').element.value)
            .toBe('foo')
    })

    it('emits an updated setting object on input', () => {
        const wrapper = shallowMount(SelectSetting, {
            propsData: {
                setting: {
                    label: 'Pick One',
                    key: 'opts',
                    options: {
                        foo: 'Foo',
                        bar: 'Bar',
                    }
                },
            },
        })

        wrapper.find('select').setValue('bar')
        wrapper.find('select').trigger('change')

        expect(wrapper.emitted().update[0])
            .toEqual([
                {
                    key: 'opts',
                    value: 'bar'
                }
            ])
    })

})
