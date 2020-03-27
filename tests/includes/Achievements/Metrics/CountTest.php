<?php

use PHPUnit\Framework\TestCase;
use NinjaForms\Achievements\Metrics\Count;

final class CountTest extends TestCase
{
    public function testValueAboveThreshold()
    {
        $metric = new Count( 1 );

        $this->assertTrue( $metric->isAtLeast( 1 ) );
    }

    public function testValueBelowThreshold()
    {
        $metric = new Count( 2 );

        $this->assertTrue( $metric->isAtLeast( 1 ) );
    }
}