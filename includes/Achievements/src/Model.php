<?php

namespace NinjaForms\Achievements;

class Model
{
    public $metric;
    public $threshold;
    public $message;

    public function get( $property )
    {
        if( property_exists( $this, $property ) ){
            return $this->$property;
        }
        return NULL;
    }

    public function set( $property, $value )
    {
        if( property_exists( $this, $property ) ){
            $this->$property = $value;
        }
    }
}