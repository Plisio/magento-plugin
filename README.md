# Plisio Сryptocurrency Payment Gateway Plugin for Magento 2

<h2>Overview</h2>
<p dir="ltr"><span>Meet the best way to accept cryptocurrency on <strong>Magento 2</strong>. With <strong>Plisio Сryptocurrency Payment Gateway Magento 2</strong> extension, you will easily integrate your ecommerce store with the popular cryptocurrency gateway accepting <a href="https://plisio.net/accept-bitcoin?utm_source=github-magento" target="_blank">Bitcoin (BTC)</a>, <a href="https://plisio.net/accept-ethereum?utm_source=github-magento" target="_blank">Ethereum (ETH)</a>, <a href="https://plisio.net/accept-bitcoin-cash?utm_source=github-magento" target="_blank">BitcoinCash (BCH)</a>, <a href="https://plisio.net/accept-monero?utm_source=github-magento" target="_blank">Monero (XMR)</a>, <a href="https://plisio.net/accept-litecoin?utm_source=github-magento" target="_blank">Litecoin (LTC)</a>, <a href="https://plisio.net/accept-zcash?utm_source=github-magento" target="_blank">Zcash (ZEC)</a>, <a href="https://plisio.net/accept-dash?utm_source=github-magento" target="_blank">Dash</a>, <a href="https://plisio.net/accept-dogecoin?utm_source=github-magento" target="_blank">DogeCoin (DOGE)</a>, <a href="https://plisio.net/accept-usdt?utm_source=github-magento" target="_blank">Tether (USDT)</a>.

<p dir="ltr"><strong>Features</strong></p>

<p>- The gateway is fully automatic - set it and forget it;</p>
<p>-Payment amount is calculated using real-time exchange rates</p>
<p>-Your customers can select to pay with Bitcoin, Litecoin, Ethereum and other altcoins at checkout</p>
<p>-No setup or recurring fees</p>
<p>-No chargebacks - guaranteed!</p>
<p>-Payments forwarding directly into your wallet</p>
<p>-Partial payments allow</p>
<p>-Fully Anonymous.</p>
<p>-No KYC/documentation necessary.</p>
<p>-Around the world</p>
<p>-Unlimited count of your requests</p>

<p dir="ltr"><span><a href="https://plisio.net/?utm_source=github-magento" target="_blank"><strong>Plisio.net</strong></a> is a popular online platform that allows accepting, storing, converting, and withdrawing altcoins. Currently it supports 8 cryptocurrencies and provides receive payments from all of them. Thus, you can easily accept payments in such popular altcoins as Bitcoin and Ethereum on your Magento 2 ecommerce website. Bitcoin is a number one cryptocurrency that has become the first decentralized digital currency that uses peer-to-peer transactions, so users interact directly without any intermediary. Ethereum is based on the same technology, blockchain, and provides a cryptocurrency token transferable between accounts as well. </span></p>
<p dir="ltr"><span>With the Plisio Сryptocurrency Payment Gateway Plugin for Magento 2, you can not only accept altcoins on your Magento 2 website, but also store cryptocurrency in a secure online wallet as well as protect altcoins in the vault that requires a time amount before being able to spend them. Almost 500 thousand vendors all over the world already use Plisio, so don't waste your chance to implement the new technology on your ecommerce storefront with the Plisio Сryptocurrency Payment Gateway Plugin for Magento 2.</span></p>

<h2>Download</h2>
Grab the latest version by clicking the Releases tab. Then come back here for installation instructions.

## Requirements

- Magento 2.*
- PHP >= 5.6.0
- Magento version as specified in composer.json of this project
- Plisio account (<a href="https://plisio.net/account/signup?utm_source=github-magento" target="_blank">Account registration</a>)

# Installation

1. Upload all files to your Magento installation root `app/code/Plisio/PlisioGateway`
2. Login to your server, and in the root of your Magento2 install, run the following commands:

```
php bin/magento setup:upgrade
php bin/magento deploy:mode:set production 
```

* Flush your Magento2 Caches

```
php bin/magento cache:flush
php bin/magento cache:clean
```

You can now activate Plisio in the admin dashboard *Stores->Configuration->Sales->Payment Methods*


 * Initially your order will be in a **Pending** status when it is initially created
 * After the invoice is paid by the user, it will change to a **Processing** status
 * When Plisio finalizes the transaction, it will change to a **Complete** status, and your order will be safe to ship, allow access to downloadable products, etc.
