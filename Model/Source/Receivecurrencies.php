<?php

namespace Plisio\PlisioGateway\Model\Source;

class Receivecurrencies
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'BTC', 'label' => 'Bitcoin (฿)'],
            ['value' => 'ETH', 'label' => 'Ethereum'],
            ['value' => 'LTC', 'label' => 'Litecoin'],
            ['value' => 'DASH', 'label' => 'Dash'],
            ['value' => 'TZEC', 'label' => 'Zcash'],
            ['value' => 'DOGE', 'label' => 'Dogecoin'],
            ['value' => 'BCH', 'label' => 'Bitcoin Cash'],
            ['value' => 'XMR', 'label' => 'Monero'],
        ];
    }
}
