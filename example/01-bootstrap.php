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

var_dump($result);