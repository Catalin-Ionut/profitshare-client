<p align="center">
  <h4 align="center">The perfect starting point to integrate <a href="https://profitshare.ro/ target="_blank">Profitshare</a> functionality within your PHP project.</h4>

  <p align="center">
    <a href="https://packagist.org/packages/catalin-ionut/profitshare-client"><img src="https://poser.pugx.org/catalin-ionut/profitshare-client/d/total.svg" alt="Total Downloads"></a>
    <a href="https://packagist.org/packages/catalin-ionut/profitshare-client"><img src="https://poser.pugx.org/catalin-ionut/profitshare-client/license.svg" alt="License"></a>
    <a href="https://phpstan.org/"><img src="https://img.shields.io/badge/PHPStan-enabled-brightgreen.svg?style=flat" alt="PHPStan Enabled"></a>
  </p>
</p>

## ✨ Features

- Thin & minimal API client to interact with Profitshare's API
- Supports non blocking requests by default
- Full async methods implemented
- Easily configurable

## 💡 Getting Started

First, install Profitshare PHP API Client via the [composer](https://getcomposer.org/) package manager:
```bash
composer require catalin-ionut/profitshare-client
```
This package uses [json-mapper](https://packagist.org/packages/netresearch/jsonmapper) to map the response to models for type validation and autocomplition.

Then, create the client:
```php
$client = $client = new ProfitshareClient\Profitshare(
    API_USER,
    API_KEY
);
```

## Advertisers
```php
$advertisers = $client->getAdvertisers();
```

## Campaigns
```php
$campaigns = $client->getCampaign($page = 1);

/* full async non blocking */
$callback = function (array $campaigns) {
    var_dump($campaigns);
};
$client->loopAllCampaigns($callback);
```

## Products
```php
$products = $client->getProducts($advertiserID = 113725);

/* full async non blocking */
$callback = function (array $products) {
    var_dump($products);
};
$client->loopAllProducts($advertiserID = 113725, $callback);
```

## Commissions
```php
$filters = [
    'status' => 'approved',
    'date_from' => '2020-06-01',
    'date_to' => '2020-06-23',
];
$commissions = $client->getCommissions($filters);
```

For full documentation, visit the **[Profitshare API](https://app.profitshare.ro/files/pdf/api_affiliate.pdf)**.

## 📄 License

Profitshare PHP API Client is an open-sourced software licensed under the [MIT license](LICENSE.md).
