<?php

namespace NinjaForms\Milestones;

class ModelFactory
{
    public static function fromArray( $array )
    {
        $model = new Model;
        foreach( $array as $property => $value ) {
            $model->set( $property, $value );
        }
        return $model;
    }

    public static function collectionFromArray( $array )
    {
        return new Collection(
            array_map( [ self::class, 'fromArray' ], $array )
        );
    }
}