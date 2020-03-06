<?php

namespace NinjaForms\Views\Authentication;

/**
 * Creates a new token using the stored secret.
 */
class TokenFactory {

    /**
     * @return Token
     */
    public static function make() {
        return new Token(
            SecretStore::getOrCreate()
        );
    }
}