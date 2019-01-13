Nova.booting((Vue, router, store) => {
    router.addRoutes([
        {
            name: 'SettingsTool',
            path: '/settings',
            component: require('./components/SettingsTool'),
        },
    ])
})
