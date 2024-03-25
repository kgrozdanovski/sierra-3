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
    public function encrypt(string $data): string
    {
        $encrypted = '';

        $privateKey = getenv('USER_SIGNATURE_PRIVATE_KEY');

        // Decode the base64 key to get the raw binary key
        $privateKeyDecoded = base64_decode($privateKey);

        // Specify the method of encryption, e.g., AES-256-CBC
        $method = 'aes-256-cbc';    // todo: move to settings

        // Generate an initialization vector (IV) for the encryption
        $ivLength = openssl_cipher_iv_length($method);
        $iv = openssl_random_pseudo_bytes($ivLength);

        // Encrypt the data
        $encryptedData = openssl_encrypt($data, $method, $privateKeyDecoded, OPENSSL_RAW_DATA, $iv);

        // Return the IV concatenated with the encrypted data
        return $iv . $encryptedData;
    }

    /**
     * {@inheritDoc}
     */
    public function decrypt(string $encrypted): string
    {
        $decrypted = '';

        $privateKey = getenv('USER_SIGNATURE_PRIVATE_KEY');

        // Decode the base64 key to get the raw binary key
        $privateKeyDecoded = base64_decode($privateKey);

        // Specify the method of encryption, e.g., AES-256-CBC
        $method = 'aes-256-cbc';    // todo: move to settings

        // Extract the initialization vector (IV) from the encrypted data
        $ivLength = openssl_cipher_iv_length($method);
        $iv = substr($encrypted, 0, $ivLength);

        // Encrypt the data
        $decrypted = openssl_decrypt($encrypted, $method, $privateKeyDecoded, OPENSSL_RAW_DATA, $iv);
        if (!$decrypted) {
            throw new \Exception('Failed to decrypt the access token.');
        }

        // Remove the IV from the decrypted data
        return substr($decrypted, $ivLength);
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
