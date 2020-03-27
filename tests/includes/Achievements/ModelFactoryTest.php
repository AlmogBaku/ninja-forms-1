<?php

use PHPUnit\Framework\TestCase;
use NinjaForms\Achievements\Model;
use NinjaForms\Achievements\Collection;
use NinjaForms\Achievements\ModelFactory;

final class ModelFactoryTest extends TestCase
{
    public function testMakesModelFromArray()
    {
        $model = ModelFactory::fromArray([]);

        $this->assertEquals( Model::class, get_class( $model ) );
    }

    public function testMakeCollectionFromArray()
    {
        $collection = ModelFactory::collectionFromArray([
            [],
            []
        ]);

        $this->assertEquals( Collection::class, get_class( $collection ) );
    }
}