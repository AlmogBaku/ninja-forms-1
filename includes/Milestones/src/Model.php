<?php

namespace NinjaForms\Milestones;

class Model
{
    public $uid;
    public $metric;
    public $threshold;
    public $title;
    public $message;
    public $links;

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