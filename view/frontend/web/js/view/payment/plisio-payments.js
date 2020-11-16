define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'plisio_plisiogateway',
                component: 'Plisio_PlisioGateway/js/view/payment/method-renderer/plisio-method'
            }
        );
        /** Add view logic here if needed */
        return Component.extend({});
    }
);
