<?php

/*
 * (c) Kristijan Grozdanovski <kgrozdanovski7@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Kernel\Business\Security\Cryptography;

readonly class Cryptography implements CryptographyInterface
{
    /**
     * {@inheritDoc}
     */
    public function hash(string $secret): string
    {
        return password_hash($secret, PASSWORD_BCRYPT);
    }

    /**
     * {@inheritDoc}
     */
    public function verify(string $secret, string $hash): bool
    {
        return password_verify($secret, $hash);
    }

    /**
     * {@inheritDoc}
     */
    public function sslEncrypt(string $data): string
    {
        $encrypted = '';

        $privateKeyFilename = getenv('USER_SIGNATURE_PRIVATE_KEY_FILENAME');

        // Load the private key from file
        $fileStream = fopen($privateKeyFilename, 'r');
        $privateKeyContent = fread($fileStream, 8192);
        fclose($fileStream);

        $privateKey = openssl_get_privatekey($privateKeyContent);

        // Compute the signature
        openssl_private_encrypt($data, $encrypted, $privateKey);

        /*
         * Free the key from memory
         *
         * PHP 8 deprecates openssl_free_key (actually openssl_pkey_free which it aliases) and automatically
         * destroys the key instance when it goes out of scope.
         */
        if (PHP_VERSION_ID < 80000) {
            openssl_free_key($privateKey);
        }

        return $encrypted;
    }

    /**
     * {@inheritDoc}
     */
    public function sslDecrypt(string $encrypted): string
    {
        $decrypted = '';

        $privateKeyFilename = getenv('USER_SIGNATURE_PRIVATE_KEY_FILENAME');

        // Load the private key from file
        $fileStream = fopen($privateKeyFilename, 'r');
        $privateKeyContent = fread($fileStream, 8192);
        fclose($fileStream);

        $privateKey = openssl_get_privatekey($privateKeyContent);

        $success = openssl_private_decrypt($encrypted, $decrypted, $privateKey);
        if (!$success) {
            throw new \Exception('Failed to decrypt the access token.');
        }

        /*
         * Free the key from memory
         *
         * PHP 8 deprecates openssl_free_key (actually openssl_pkey_free which it aliases) and automatically
         * destroys the key instance when it goes out of scope.
         */
        if (PHP_VERSION_ID < 80000) {
            openssl_free_key($privateKey);
        }

        return $decrypted;
    }

    /**
     * {@inheritDoc}
     */
    public function urlSafeBase64Encode(string $value): string
    {
        $encoded = base64_encode($value);

        // Replace unsafe characters +, = and / with the safe characters -, _ and ~
        return str_replace(
            array('+', '=', '/'),
            array('-', '_', '~'),
            $encoded
        );
    }

    /**
     * {@inheritDoc}
     */
    public function urlSafeBase64Decode(string $value): string
    {
        // Replace safe characters -, _ and ~ with the unsafe characters +, = and /
        $safeCharactersReversed = str_replace(
            array('-', '_', '~'),
            array('+', '=', '/'),
            $value
        );

        return base64_decode($safeCharactersReversed);
    }
}
