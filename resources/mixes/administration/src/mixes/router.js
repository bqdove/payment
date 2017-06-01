import Pay from '../pages/Pay.vue';

export default function (injection) {
    injection.useExtensionRoute([
        {
            beforeEnter: injection.middleware.requireAuth,
            component: Pay,
            path: 'pay',
        },
    ]);
}