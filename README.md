# Analytics-MongoDB

Store [PhpAb](https://github.com/phpab/phpab) tests participations in MongoDB.

## Install

Via Composer

``` bash
$ composer require phpab/analytics-mongodb
```

## Usage

This is a full usage.

``` php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

$storage = new \PhpAb\Storage\Cookie('phpab');
$manager = new \PhpAb\Participation\Manager($storage);

$analyticsData = new \PhpAb\Analytics\DB\DataCollector;

$dispatcher = new \PhpAb\Event\Dispatcher();
$dispatcher->addSubscriber($analyticsData);

$filter = new \PhpAb\Participation\PercentageFilter(50);
$chooser = new \PhpAb\Variant\RandomChooser();

$engine = new PhpAb\Engine\Engine($manager, $dispatcher, $filter, $chooser);

$test = new \PhpAb\Test\Test('foo_test');
$test->addVariant(new \PhpAb\Variant\SimpleVariant('_control'));
$test->addVariant(new \PhpAb\Variant\CallbackVariant('v1', function () {
    echo 'v1';
}));
$test->addVariant(new \PhpAb\Variant\CallbackVariant('v2', function () {
    echo 'v2';
}));
$test->addVariant(new \PhpAb\Variant\CallbackVariant('v3', function () {
    echo 'v3';
}));

// Add some tests
$engine->addTest($test);

$engine->start();

// Here starts MongoDB interaction

// Provide a MongoDB Collection to be injected
$mongoCollection = (new \MongoDB\Client)->phpab->run;

// Inject together with Analytics Data
$analytics = new \PhpAb\Analytics\MongoDB(
        $analyticsData->getTestsData(), $mongoCollection
);

// Store it providing a user identifier and a scenario
// typically a URL or a controller name

$result = $analytics->store('1.2.3.4-abc', 'homepage.php');

// $result is the amount of documents inserted
```



## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please open an issue in the issue tracker. We realize 
this is not ideal but it's the fastest way to get the issue solved.

## Credits

- [Walter Tamboer](https://github.com/waltertamboer)
- [Patrick Heller](https://github.com/psren)
- [Mariano F.co Benítez Mulet](https://github.com/pachico)
- [All Contributors](https://github.com/phpab/phpab/graphs/contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/phpab/phpab.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/phpab/phpab/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/phpab/phpab.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/phpab/phpab.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/phpab/phpab.svg?style=flat-square
[ico-versioneye]: https://img.shields.io/versioneye/d/phpab/phpab.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/phpab/phpab
[link-travis]: https://travis-ci.org/phpab/phpab
[link-scrutinizer]: https://scrutinizer-ci.com/g/phpab/phpab/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/phpab/phpab
[link-downloads]: https://packagist.org/packages/phpab/phpab
[link-versioneye]: https://www.versioneye.com/user/projects/5702c846fcd19a004543f8fb
[link-author]: https://github.com/:author_username
[link-contributors]: ../../contributors
