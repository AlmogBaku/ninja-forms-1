<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class KeyFactoryTest extends TestCase
{
    public function testKeyLength(): void
    {
        $key = NinjaForms\Blocks\Authentication\KeyFactory::make();
        $this->assertTrue( 40 <= strlen( $key ) );
        $this->assertTrue( 255 >= strlen( $key ) );
    }
}