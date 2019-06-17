import { shallowMount } from '@vue/test-utils'
import Toggle from '@/partials/Toggle'

describe('Toggle', () => {

    const methods = {
        __: (s) => s,
    }

    test('emit toggle event with setting key when clicked', () => {
        const wrapper = shallowMount(Toggle, {
            propsData: {
                name: 'toggle_setting',
                setting: {
                    key: 'toggle_setting',
                    value: false,
                },
                description: 'Toggle setting',
                link: {},
            },
            methods,
        })

        wrapper.find('.toggle.py-1').trigger('click')

        expect(wrapper.emitted().toggle[0]).toEqual(['toggle_setting'])
    })

})
