<?php

namespace Doctrine\MongoDB\Tests\Aggregation\Stage;

use Doctrine\MongoDB\Aggregation\Stage\BucketAuto;
use Doctrine\MongoDB\Tests\Aggregation\AggregationTestCase;
use Doctrine\MongoDB\Tests\TestCase;

class BucketAutoTest extends TestCase
{
    use AggregationTestCase;

    public function testBucketAutoStage()
    {
        $bucketAutoStage = new BucketAuto($this->getTestAggregationBuilder());
        $bucketAutoStage
            ->groupBy('$someField')
            ->buckets(3)
            ->granularity('R10')
            ->output()
                ->field('averageValue')
                ->avg('$value');

        $this->assertSame(['$bucketAuto' => [
            'groupBy' => '$someField',
            'buckets' => 3,
            'granularity' => 'R10',
            'output' => ['averageValue' => ['$avg' => '$value']]
        ]], $bucketAutoStage->getExpression());
    }

    public function testBucketAutoFromBuilder()
    {
        $builder = $this->getTestAggregationBuilder();
        $builder->bucketAuto()
            ->groupBy('$someField')
            ->buckets(3)
            ->granularity('R10')
            ->output()
                ->field('averageValue')
                ->avg('$value');

        $this->assertSame([['$bucketAuto' => [
            'groupBy' => '$someField',
            'buckets' => 3,
            'granularity' => 'R10',
            'output' => ['averageValue' => ['$avg' => '$value']]
        ]]], $builder->getPipeline());
    }

    public function testBucketAutoSkipsUndefinedProperties()
    {
        $bucketAutoStage = new BucketAuto($this->getTestAggregationBuilder());
        $bucketAutoStage
            ->groupBy('$someField')
            ->buckets(3);

        $this->assertSame(['$bucketAuto' => [
            'groupBy' => '$someField',
            'buckets' => 3,
        ]], $bucketAutoStage->getExpression());
    }
}
