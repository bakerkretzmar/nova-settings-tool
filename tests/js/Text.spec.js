import { shallowMount } from '@vue/test-utils'
import Text from '@/Text'

describe('Text', () => {

    const methods = {
        __: (s) => s,
    }

    test('emit new setting object on input', () => {
        const wrapper = shallowMount(Text, {
            propsData: {
                label: 'Text Setting',
                setting: {
                    key: 'text_setting',
                },
            },
            methods,
        })

        wrapper.find('.form-input').setValue('Hi there')
        wrapper.find('.form-input').trigger('input')

        expect(wrapper.emitted().input[0]).toEqual([{ text_setting: 'Hi there' }])
    })

})
