<?php

/*
 * (c) Kristijan Grozdanovski <kgrozdanovski7@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Kernel\Business\Security\Cryptography;

interface CryptographyInterface
{
    /**
     * @param string $secret
     *
     * @return string
     */
    public function hash(string $secret): string;

    /**
     * @param string $secret
     * @param string $hash
     *
     * @return bool
     */
    public function verify(string $secret, string $hash): bool;

    /**
     * Encrypt a string using a secret key.
     *
     * @param string $data
     *
     * @return string
     */
    public function encrypt(string $data): string;

    /**
     * Decipher a string using a secret key.
     *
     * @param string $encrypted
     *
     * @return string
     */
    public function decrypt(string $encrypted): string;

    /**
     * @param string $value
     *
     * @return string
     */
    public function urlSafeBase64Encode(string $value): string;

    /**
     * @param string $value
     *
     * @return string
     */
    public function urlSafeBase64Decode(string $value): string;
}
