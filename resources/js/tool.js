Nova.booting((Vue, router, store) => {
    router.addRoutes([
        {
            name: 'nova-settings-tool',
            path: '/nova-settings-tool',
            component: require('./components/Tool'),
        },
    ])
})
