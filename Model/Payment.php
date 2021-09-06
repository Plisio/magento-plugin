<?php

namespace Plisio\PlisioGateway\Model;

use Magento\Directory\Model\CountryFactory;
use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\Api\ExtensionAttributesFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Response\Http;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Module\ModuleListInterface;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\UrlInterface;
use Magento\Payment\Helper\Data;
use Magento\Payment\Model\Method\AbstractMethod;
use Magento\Payment\Model\Method\Logger;
use Magento\Sales\Api\OrderManagementInterface;
use Magento\Sales\Model\Order;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Message\ManagerInterface;
use Plisio\PlisioGateway\Lib\PlisioGateway;

class Payment extends AbstractMethod
{
    const PLISIO_MAGENTO_VERSION = '1.0.1';
    const CODE = 'plisio_plisiogateway';

    protected $_code = 'plisio_plisiogateway';

    protected $_isInitializeNeeded = true;

    protected $urlBuilder;
    private $plisio;
    protected $storeManager;
    protected $orderManagement;
    protected $messageManager;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param ExtensionAttributesFactory $extensionFactory
     * @param AttributeValueFactory $customAttributeFactory
     * @param Data $paymentData
     * @param ScopeConfigInterface $scopeConfig
     * @param Logger $logger
     * @param UrlInterface $urlBuilder
     * @param StoreManagerInterface $storeManager
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param OrderManagementInterface $orderManagement
     * @param array $data
     * @internal param ModuleListInterface $moduleList
     * @internal param TimezoneInterface $localeDate
     * @internal param CountryFactory $countryFactory
     * @internal param Http $response
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ExtensionAttributesFactory $extensionFactory,
        AttributeValueFactory $customAttributeFactory,
        Data $paymentData,
        ScopeConfigInterface $scopeConfig,
        Logger $logger,
        UrlInterface $urlBuilder,
        StoreManagerInterface $storeManager,
        OrderManagementInterface $orderManagement,
        ManagerInterface $messageManager,
        array $data = [],
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null
    ) {
        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $paymentData,
            $scopeConfig,
            $logger,
            $resource,
            $resourceCollection,
            $data
        );

        $this->urlBuilder = $urlBuilder;
        $this->storeManager = $storeManager;
        $this->orderManagement = $orderManagement;
        $this->messageManager = $messageManager;
        $this->plisio = new PlisioGateway($this->getConfigData('api_auth_token'));
    }

    private function get_plisio_receive_currencies($source_currency)
    {
        $currencies = $this->plisio->getCurrencies($source_currency);
        return array_reduce($currencies, function ($acc, $curr) {
            $acc[$curr['cid']] = $curr;
            return $acc;
        }, []);
    }

    /**
     * @param Order $order
     * @return array
     */
    public function getPlisioRequest(Order $order)
    {
        $plisio_receive_currencies = $this->get_plisio_receive_currencies($order->getOrderCurrencyCode());
        $plisio_receive_cids = array_keys($plisio_receive_currencies);

        $description = [];
        foreach ($order->getAllItems() as $item) {
            $description[] = number_format($item->getQtyOrdered(), 0) . ' × ' . $item->getName();
        }

        $params = [
            'order_number' => $order->getIncrementId(),
            'order_name' => $order->getIncrementId(),
            'source_amount' => number_format($order->getGrandTotal(), 2, '.', ''),
            'source_currency' => $order->getOrderCurrencyCode(),
            'currency' => $plisio_receive_cids[0],
            'callback_url' => $this->urlBuilder->getUrl('plisio/payment/callback'),
            'cancel_url' => $this->urlBuilder->getUrl('plisio/payment/cancelOrder'),
            'success_url' => $this->urlBuilder->getUrl('plisio/payment/returnAction'),
            'description' => implode(', ', $description),
            'email' => $order->getShippingAddress()->getEmail(),
            'plugin' => 'Magento',
            'version' => self::PLISIO_MAGENTO_VERSION,
        ];

        $plOrder = $this->plisio->createTransaction($params);

        if (!empty($plOrder['data']['invoice_url'])) {
            return [
                'status' => true,
                'data' => [
                    'invoice_url' => $plOrder["data"]["invoice_url"]
                    ],
            ];
        } else {
            return [
                'status' => false,
                'message' => json_decode($plOrder['data']['message'], true)['amount']
            ];
        }
    }

    /**
     * @param Order $order
     */
    public function validatePlisioCallback(Order $order)
    {
        try {
            if (!$order || !$order->getIncrementId()) {
                $request_order_number = (
                    filter_input(INPUT_POST, 'order_number')
                    ?: filter_input(INPUT_GET, 'order_number')
                );

                throw new \Exception('Order #' . $request_order_number . ' does not exists');
            }

            $request_txn_id = (filter_input(INPUT_POST, 'txn_id')
                ?: filter_input(INPUT_GET, 'txn_id'));
            $plOrder = $_POST;

            if (!$plOrder) {
                throw new \Exception('Plisio Order #' . $request_txn_id . ' does not exist');
            }

            if ($plOrder['status'] == 'completed' || $plOrder['status'] == 'mismatch') {
                $order->setState(Order::STATE_COMPLETE);
                $order->setStatus($order->getConfig()->getStateDefaultStatus(Order::STATE_COMPLETE));
                $order->save();
            } elseif (in_array($plOrder['status'], ['expired', 'cancelled', 'error'])) {
                $this->orderManagement->cancel($plOrder['order_number']);
            }
        } catch (\Exception $e) {
            $this->_logger->error($e);
        }
    }
}
