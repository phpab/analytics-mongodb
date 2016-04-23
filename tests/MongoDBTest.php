<?php
/**
 * This file is part of phpab/analytics-mongodb. (https://github.com/phpab/analytics-mongodb)
 *
 * @link https://github.com/phpab/analytics-mongodb for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://github.com/phpab/analytics-mongodb/blob/master/LICENSE MIT
 */

namespace PhpAb\Analytics;

class MongoDBTest extends \PHPUnit_Framework_TestCase
{

    private $mockedMongoCollection;
    private $mockedBulkWriteResult;

    public function setUp()
    {
        parent::setUp();

        $this->mockedBulkWriteResult = $this->getMockBuilder('\MongoDB\BulkWriteResult')
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockedBulkWriteResult->method('getInsertedCount')
            ->willReturn(2);

        $this->mockedMongoCollection = $this->getMockBuilder('\MongoDB\Collection')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockedMongoCollection->method('bulkWrite')
            ->willReturn($this->mockedBulkWriteResult);
    }

    public function testStore()
    {
        // Arrange
        $analytics = new \PhpAb\Analytics\MongoDB(
            [
            'bernard' => 'black',
            'walter' => 'white'
            ],
            $this->mockedMongoCollection
        );

        // Act
        $result = $analytics->store('1.2.3.4-abc', 'homepage.php');

        // Assert
        $this->assertSame(2, $result);
    }

    public function testEmptyParticipationStore()
    {
        // Arrange
        $analytics = new \PhpAb\Analytics\MongoDB([], $this->mockedMongoCollection);

        // Act
        $result = $analytics->store('1.2.3.4-abc', 'homepage.php');

        // Assert
        $this->assertFalse($result);
    }
}
