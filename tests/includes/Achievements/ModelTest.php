<?php

use PHPUnit\Framework\TestCase;
use NinjaForms\Achievements\Model;

final class ModelTest extends TestCase
{
    public function testHasProperties()
    {
        $model = new Model();
        $model->set( 'metric', 'testMetric' );
        $model->set( 'threshold', 1 );
        $model->set( 'message', 'hello' );

        $this->assertEquals( 'testMetric', $model->get( 'metric' ) );
        $this->assertEquals( 1, $model->get( 'threshold' ) );
        $this->assertEquals( 'hello', $model->get( 'message' ) );
    }

    public function testDoesntHaveExtraProperties()
    {
        $model = new Model();
        $model->set( 'notProperty', true );

        $this->assertEquals( null, $model->get( 'notProperty' ) );
    }
}
