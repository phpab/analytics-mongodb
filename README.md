# Analytics-MongoDB

Store [PhpAb](https://github.com/phpab/phpab) tests participations in MongoDB.

Note: This is compatible with MongoDB and not with the legacy Mongo library.

## Install

Via Composer

```bash
$ composer require phpab/analytics-mongodb
```

## Usage

Usage after full PhpAB example.

```php

use PhpAb\Storage\Cookie;
use PhpAb\Participation\Manager;
use PhpAb\Analytics\DataCollector\Generic;
use PhpAb\Event\Dispatcher;
use PhpAb\Participation\Filter\Percentage;
use PhpAb\Variant\Chooser\RandomChooser;
use PhpAb\Variant\SimpleVariant;
use PhpAb\Variant\CallbackVariant;
use PhpAb\Engine\Engine;
use PhpAb\Test\Test;

$storage = new Cookie('phpab');
$manager = new Manager($storage);

$analyticsData = new Generic();

$dispatcher = new Dispatcher();
$dispatcher->addSubscriber($analyticsData);

$filter = new Percentage(50);
$chooser = new RandomChooser();

$engine = new Engine($manager, $dispatcher, $filter, $chooser);

$test = new Test('foo_test');
$test->addVariant(new SimpleVariant('_control'));
$test->addVariant(new CallbackVariant('v1', function () {
    echo 'v1';
}));
$test->addVariant(new CallbackVariant('v2', function () {
    echo 'v2';
}));
$test->addVariant(new CallbackVariant('v3', function () {
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

var_dump($result);
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

- [Mariano F.co Benítez Mulet](https://github.com/pachico)
- [All Contributors](https://github.com/phpab/analytics-mongodb/graphs/contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.