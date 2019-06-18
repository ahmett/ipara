# Payconn: Ipara

**iPara gateway for Payconn payment processing library**

[![Build Status](https://travis-ci.com/payconn/ipara.svg?branch=master)](https://travis-ci.com/payconn/ipara)

[Payconn](https://github.com/payconn/common) is a framework agnostic, multi-gateway payment
processing library for PHP. This package implements common classes required by Payconn.

## Installation

    $ composer require payconn/ipara:~1.0

## Supported card families
* Bonus 
* World
* Axess
* Maximum
* Paraf
* CardFinans
* Sağlam Kart 
* Advantage

## Supported methods
* purchase
* authorize
* complete
* refund
* cancel

## Basic Usage
```php
use Payconn\Ipara;
use Payconn\Ipara\Token;
use Payconn\Ipara\Product;
use Payconn\Ipara\Model\Purchase;
use Payconn\Common\CreditCard;

$token = new Token('YOUR_PUBLIC_KEY', 'YOUR_PRIVATE_KEY');
$purchase = new Purchase();
$purchase->setTestMode(true);
$purchase->setAmount(100);
$purchase->setInstallment(1);
$purchase->setFirstName('Murat');
$purchase->setLastName('Sac');
$purchase->setEmail('muratsac@mail.com');
$purchase->addProduct((new Product('001', 'Test', 100)));
$purchase->setCreditCard((new CreditCard('4282209027132016', '2024', '12', '358'))->setHolderName('MuratSac'));
$purchase->generateOrderId();
$response = (new Ipara($token))->purchase($purchase);
if($response->isSuccessful()){
    // success!
}
```

## Change log

Please see [UPGRADE](UPGRADE.md) for more information on how to upgrade to the latest version.

## Support

If you are having general issues with Payconn, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/payconn/ipara/issues),
or better yet, fork the library and submit a pull request.


## Security

If you discover any security related issues, please email muratsac@mail.com instead of using the issue tracker.


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
