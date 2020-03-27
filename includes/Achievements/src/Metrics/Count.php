<?php

namespace NinjaForms\Achievements\Metrics;

class Count
{
    /** @var int */
    protected $value;

    public function __construct( $value )
    {
        $this->value = $value;
    }

    public function isAtLeast( $threshold )
    {
        return $threshold <= $this->value;
    }
}