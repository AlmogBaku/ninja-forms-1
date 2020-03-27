<?php

use PHPUnit\Framework\TestCase;
use NinjaForms\Achievements\Model;
use NinjaForms\Achievements\Collection;

final class CollectionTest extends TestCase
{
    public function testCollectionCorrectCount()
    {
        $collection = new Collection([
            new Model(),
            new Model(),
        ]);

        $this->assertEquals( 2, count( $collection->items ) );
    }

    public function testFiltersByMetric()
    {
        $model = new Model();
        $model->metric = 'testMetric';

        $collection = new Collection([
            $model
        ]);

        $collection = $collection->where( 'metric', 'testMetric' );

        $this->assertEquals( $model, $collection->pop() );
    }
}