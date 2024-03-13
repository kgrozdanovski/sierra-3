<?php

/*
 * (c) Darko Poposki <poposki.darko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Kernel\Business\ValueObject;

use App\Kernel\Communication\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

readonly class ValueObjectSerializer
{
    public function __construct(
        private SerializerInterface $serializer
    ) {}

    /**
     * @param mixed $data
     * @param string $type
     * @param string $format
     * @param array $context
     *
     * @return ValueObjectInterface
     */
    public function deserialize(
        mixed $data,
        string $type,
        string $format = JsonEncoder::FORMAT,
        array $context = []
    ): ValueObjectInterface {
        try {
            return $this->serializer->deserialize($data, $type, $format, $context);
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }
}
