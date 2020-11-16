<?php

namespace Plisio\PlisioGateway\Controller\Payment;

use Plisio\PlisioGateway\Model\Payment as PlisioPayment;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Sales\Model\Order;

class Callback extends Action
{
    protected $order;
    protected $plisioPayment;

    /**
     * @param Context $context
     * @param Order $order
     * @param Payment|PlisioPayment $plisioPayment
     * @internal param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        Order $order,
        PlisioPayment $plisioPayment
    ) {

        parent::__construct($context);
        $this->order = $order;
        $this->plisioPayment = $plisioPayment;

        $this->execute();
    }

    /**
     * Default customer account page
     *
     * @return void
     */
    public function execute()
    {
        $request_order_number = (filter_input(INPUT_POST, 'order_number')
            ? filter_input(INPUT_POST, 'order_number') : filter_input(INPUT_GET, 'order_number'));

        $order = $this->order->loadByIncrementId($request_order_number);
        $this->plisioPayment->validatePlisioCallback($order);

        $this->getResponse()->setBody('Use POST for callback request');
    }
}
