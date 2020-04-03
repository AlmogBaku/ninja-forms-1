<?php

namespace NinjaForms\Milestones;

class Collection
{
    public $items;

    public function __construct( $items )
    {
        $this->items = $items;
    }

    public function where( $property, $value )
    {
        return new self( array_filter( $this->items, function( $item ) use ( $value, $property ) {
            return $value == $item->$property;
        }));
    }

    public function whereCallback( $property, $callback )
    {
        return new self( array_filter( $this->items, $callback ) );
    }

    public function pop()
    {
        return array_pop( $this->items );
    }
}
