<?php

/*
 * (c) Darko Poposki <poposki.darko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace App\Kernel\Business\Serializer\Normalizer\Builder;

use App\Kernel\Business\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\Normalizer\BackedEnumNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

final class EntityNormalizerBuilder
{
    private array $normalizers = [];

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return EntityNormalizerBuilder
     */
    private function withNormalizer(string $key, mixed $value): EntityNormalizerBuilder
    {
        $instance = new EntityNormalizerBuilder();
        $instance->normalizers = array_merge($this->normalizers, [$key => $value]);

        return $instance;
    }

    /**
     * @return EntityNormalizerBuilder
     */
    public function withDateTimeNormalizer(): EntityNormalizerBuilder
    {
        return $this->withNormalizer(DateTimeNormalizer::class, new DateTimeNormalizer());
    }

    /**
     * @return EntityNormalizerBuilder
     */
    public function withBackedEnumNormalizer(): EntityNormalizerBuilder
    {
        return $this->withNormalizer(BackedEnumNormalizer::class, new BackedEnumNormalizer());
    }

    /**
     * @return EntityNormalizerBuilder
     */
    public function withObjectNormalizer(): EntityNormalizerBuilder
    {
        $classMetadataFactory = new ClassMetadataFactory(new AttributeLoader());
        $metadataAwareNameConverter = new MetadataAwareNameConverter($classMetadataFactory);

        $extractor = new PropertyInfoExtractor([], [new PhpDocExtractor(), new ReflectionExtractor()]);

        return $this->withNormalizer(
            ObjectNormalizer::class,
            new ObjectNormalizer($classMetadataFactory, $metadataAwareNameConverter, null, $extractor)
        );
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->normalizers;
    }
}
