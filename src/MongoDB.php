<?php

namespace PhpAb\Analytics;

class MongoDB
{
    /**
     * @var \MongoDB\Driver\Manager
     */
    private $mockCollection;

    /**
     * @var array
     */
    private $participations;

    /**
     * @param array $participations
     * @param \MongoDB\Collection $collection
     */
    public function __construct(array $participations, \MongoDB\Collection $collection)
    {
        $this->participations = $participations;
        $this->mockCollection = $collection;
    }

    /**
     * @param string $userIdentifier
     * @param string $scenarioIdentifier
     *
     * @return boolean
     */
    public function store($userIdentifier, $scenarioIdentifier)
    {
        if (empty($this->participations)) {
            return false;
        }

        $documents = [];

        foreach ($this->participations as $testIdentifier => $variationIdentifier) {
            $document = [
                'testIdentifier' => $testIdentifier,
                'variationIdentifier' => $variationIdentifier,
                'userIdentifier' => $userIdentifier,
                'scenarioIdentifier' => $scenarioIdentifier,
                'createdAt' => new \MongoDB\BSON\UTCDatetime(time() * 1000)
            ];

            $documents[] = [
                "insertOne" => [$document]
            ];
        }

        $result = $this->mockCollection->bulkWrite($documents);

        return $result->getInsertedCount();
    }
}
