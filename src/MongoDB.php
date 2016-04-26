<?php
/**
 * This file is part of phpab/analytics-mongodb. (https://github.com/phpab/analytics-mongodb)
 *
 * @link https://github.com/phpab/analytics-mongodb for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://github.com/phpab/analytics-mongodb/blob/master/LICENSE MIT
 */

namespace PhpAb\Analytics;

/**
 * Stores PhpAb participation results in MongoDB.
 *
 * @package PhpAb
 */
class MongoDB
{
    /**
     * @var \MongoDB\Collection
     */
    private $collection;

    /**
     * @var array
     */
    private $participations;

    /**
     * Initializes a new instance of this class.
     *
     * @param array $participations An array containing tests chosen variants
     * @param \MongoDB\Collection $collection MongoDB Collection where participation will be stored
     */
    public function __construct(array $participations, \MongoDB\Collection $collection)
    {
        $this->participations = $participations;
        $this->collection = $collection;
    }

    /**
     * Persists participation in MongoDB
     *
     * @param string $userIdentifier Web user unique identification
     * @param string $scenarioIdentifier Scenario where tests has been executed (ie. url)
     *
     * @return boolean
     */
    public function store($userIdentifier, $scenarioIdentifier)
    {
        if (empty($this->participations)) {
            return false;
        }

        $documents = [];

        $uniqueRunIdentifier = uniqid('', true);

        foreach ($this->participations as $testIdentifier => $variantIdentifier) {
            $document = [
                'testIdentifier' => $testIdentifier,
                'variantIdentifier' => $variantIdentifier,
                'userIdentifier' => $userIdentifier,
                'scenarioIdentifier' => $scenarioIdentifier,
                'runIdentifier' => $uniqueRunIdentifier,
                'createdAt' => new \MongoDB\BSON\UTCDatetime(time() * 1000)
            ];

            $documents[] = [
                'insertOne' => [$document]
            ];
        }

        $result = $this->collection->bulkWrite($documents);

        return $result->getInsertedCount();
    }
}
