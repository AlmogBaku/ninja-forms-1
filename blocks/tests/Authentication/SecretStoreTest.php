<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

function get_option( $key ) { global $secret; return $secret; }
function update_option( $key, $value, $autoload ) { return true; }

final class SecretTest extends TestCase
{
    public function testCreatesASecretIfDoesNotExist(): void
    {
        global $secret;
        $secret = false;

        $this->assertNotEmpty(
            NinjaForms\Views\Authentication\SecretStore::getOrCreate()
        );
    }

    public function testDefaultDefinedConstant(): void
    {
        define( 'NINJA_FORMS_VIEWS_SECRET', 'private_key' );

        $this->assertEquals( 'private_key', NinjaForms\Views\Authentication\SecretStore::getOrCreate() );
    }

    public function testWrongTypeSecretSelfCorrects(): void
    {
        global $secret;
        $secret  = new stdClass();

        $this->assertTrue(
            is_string( NinjaForms\Views\Authentication\SecretStore::getOrCreate() )
        );
    }
}