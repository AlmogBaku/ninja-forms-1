<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class HashTest extends TestCase
{
    public function testTokenValidates(): void
    {
        $token = new NinjaForms\Views\Authentication\Token( 'private_key' );
        $this->assertTrue(
            $token->validate(
                $token->create( 'public_key' )
            )
        );
    }

    public function testTokenDoesNotValidate(): void
    {
        $token = new NinjaForms\Views\Authentication\Token( 'private_key' );
        $this->assertFalse(
            $token->validate( 'malformed_token' )
        );
    }
}