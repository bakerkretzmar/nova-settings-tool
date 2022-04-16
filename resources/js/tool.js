import Tool from './Tool.vue';

Nova.booting((app, store) => {
    Nova.inertia('NovaSettingsTool', Tool);
});
