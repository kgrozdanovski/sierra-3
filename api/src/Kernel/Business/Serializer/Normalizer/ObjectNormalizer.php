<?php

namespace App\Kernel\Business\Serializer\Normalizer;

use App\Kernel\Business\Serializer\Normalizer\Exception\MaxTreeDepthException;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\PropertyInfo\PropertyTypeExtractorInterface;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Mapping\AttributeMetadata;
use Symfony\Component\Serializer\Mapping\ClassDiscriminatorResolverInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

/**
 * Adds a MAX_TREE_DEPTH limitation to symfony's objectNormalizer.
 *
 * The built-in ObjectNormalizer from Symfony only supports a MAX_DEPTH attribute
 * which handles circular references to the same class.
 *
 * <br>
 * Inspired by the ObjectNormalizer implementation in [zusam/zusam](https://github.com/zusam/zusam)
 */
class ObjectNormalizer extends AbstractObjectNormalizer
{
    /**
     * How deep the resulting normalized tree can be.
     * The default value is 1.
     */
    public const string TREE_DEPTH_LIMIT = 'tree_depth_limit';

    /**
     * Set to true to respect the max tree depth.
     */
    public const string ENABLE_MAX_TREE_DEPTH = 'enable_max_tree_depth';

    /**
     * Handler to call when max tree depth is reached.
     *
     * If you specify no handler, a MaxTreeDepthException is thrown.
     *
     * The method will be called with ($object, $format, $context) and its
     * return value is returned as the result of the normalize() call.
     */
    public const string MAX_TREE_DEPTH_HANDLER = 'max_tree_depth_handler';

    /**
     * @internal
     */
    protected const string TREE_DEPTH_LIMIT_COUNTERS = 'tree_depth_limit_counters';

    protected PropertyAccessorInterface $propertyAccessor;

    private readonly \Closure $objectClassResolver;

    public function __construct(
        ClassMetadataFactoryInterface $classMetadataFactory = null,
        NameConverterInterface $nameConverter = null,
        PropertyAccessorInterface $propertyAccessor = null,
        PropertyTypeExtractorInterface $propertyTypeExtractor = null,
        ClassDiscriminatorResolverInterface $classDiscriminatorResolver = null,
        callable $objectClassResolver = null,
        array $defaultContext = []
    ) {
        if (!class_exists(PropertyAccess::class)) {
            throw new LogicException('The ObjectNormalizer class requires the "PropertyAccess" component. Try running "composer require symfony/property-access".');
        }

        parent::__construct($classMetadataFactory, $nameConverter, $propertyTypeExtractor, $classDiscriminatorResolver, $objectClassResolver, $defaultContext);

        $this->propertyAccessor = $propertyAccessor ?: PropertyAccess::createPropertyAccessor();

        $this->objectClassResolver = ($objectClassResolver ?? static fn ($class) => \is_object($class) ? $class::class : $class)(...);

        $this->defaultContext[self::TREE_DEPTH_LIMIT] = 1;
    }

    public function getSupportedTypes(?string $format): array
    {
        return ['object' => true];
    }

    protected function extractAttributes(object $object, ?string $format = null, array $context = []): array
    {
        if (\stdClass::class === $object::class) {
            return array_keys((array) $object);
        }

        // If not using groups, detect manually
        $attributes = [];

        // methods
        $class = ($this->objectClassResolver)($object);
        $reflClass = new \ReflectionClass($class);

        foreach ($reflClass->getMethods(\ReflectionMethod::IS_PUBLIC) as $reflMethod) {
            if (
                0 !== $reflMethod->getNumberOfRequiredParameters()
                || $reflMethod->isStatic()
                || $reflMethod->isConstructor()
                || $reflMethod->isDestructor()
            ) {
                continue;
            }

            $name = $reflMethod->name;
            $attributeName = null;

            if (str_starts_with($name, 'get') || str_starts_with($name, 'has') || str_starts_with($name, 'can')) {
                // getters, hassers and canners
                $attributeName = substr($name, 3);

                if (!$reflClass->hasProperty($attributeName)) {
                    $attributeName = lcfirst($attributeName);
                }
            } elseif (str_starts_with($name, 'is')) {
                // issers
                $attributeName = substr($name, 2);

                if (!$reflClass->hasProperty($attributeName)) {
                    $attributeName = lcfirst($attributeName);
                }
            }

            if (null !== $attributeName && $this->isAllowedAttribute($object, $attributeName, $format, $context)) {
                $attributes[$attributeName] = true;
            }
        }

        // properties
        foreach ($reflClass->getProperties() as $reflProperty) {
            if (!$reflProperty->isPublic()) {
                continue;
            }

            if ($reflProperty->isStatic() || !$this->isAllowedAttribute($object, $reflProperty->name, $format, $context)) {
                continue;
            }

            $attributes[$reflProperty->name] = true;
        }

        return array_keys($attributes);
    }

    protected function getAttributeValue(object $object, string $attribute, ?string $format = null, array $context = []): mixed
    {
        $mapping = $this->classDiscriminatorResolver?->getMappingForMappedObject($object);

        return $attribute === $mapping?->getTypeProperty()
            ? $mapping
            : $this->propertyAccessor->getValue($object, $attribute);
    }

    protected function setAttributeValue(object $object, string $attribute, mixed $value, ?string $format = null, array $context = []): void
    {
        try {
            $this->propertyAccessor->setValue($object, $attribute, $value);
        } catch (NoSuchPropertyException) {
            // Properties not found are ignored
        }
    }

    protected function getAllowedAttributes(string|object $classOrObject, array $context, bool $attributesAsString = false): array|bool
    {
        if (false === $allowedAttributes = parent::getAllowedAttributes($classOrObject, $context, $attributesAsString)) {
            return false;
        }

        if (null !== $this->classDiscriminatorResolver) {
            $class = \is_object($classOrObject) ? $classOrObject::class : $classOrObject;
            if (null !== $discriminatorMapping = $this->classDiscriminatorResolver->getMappingForMappedObject($classOrObject)) {
                $allowedAttributes[] = $attributesAsString ? $discriminatorMapping->getTypeProperty() : new AttributeMetadata($discriminatorMapping->getTypeProperty());
            }

            if (null !== $discriminatorMapping = $this->classDiscriminatorResolver->getMappingForClass($class)) {
                $attributes = [];
                foreach ($discriminatorMapping->getTypesMapping() as $mappedClass) {
                    $attributes[] = parent::getAllowedAttributes($mappedClass, $context, $attributesAsString);
                }
                $allowedAttributes = array_merge($allowedAttributes, ...$attributes);
            }
        }

        return $allowedAttributes;
    }

    /**
     * @param $object
     * @param $format
     * @param array $context
     *
     * @return array|\ArrayObject|bool|float|int|string|null
     *
     * @throws MaxTreeDepthException
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize(
        $object,
        $format = null,
        array $context = []
    ): array|\ArrayObject|bool|float|int|null|string {
        if ($this->atMaxTreeDepth($context)) {
            return $this->handleMaxTreeDepth($object, $format, $context);
        }

        return parent::normalize($object, $format, $context);
    }

    /**
     * Detects if the configured max tree depth limit is reached.
     *
     * @param array $context
     *
     * @return bool
     */
    protected function atMaxTreeDepth(array &$context): bool
    {
        $enableMaxTreeDepth = $context[self::ENABLE_MAX_TREE_DEPTH]
            ?? $this->defaultContext[self::ENABLE_MAX_TREE_DEPTH]
            ?? false;
        if (!$enableMaxTreeDepth) {
            return false;
        }

        $treeDepthLimit = $context[self::TREE_DEPTH_LIMIT] ?? $this->defaultContext[self::TREE_DEPTH_LIMIT] ?? $this->treeDepthLimit;
        if (isset($context[self::TREE_DEPTH_LIMIT_COUNTERS])) {
            if ($context[self::TREE_DEPTH_LIMIT_COUNTERS] >= $treeDepthLimit) {
                unset($context[self::TREE_DEPTH_LIMIT_COUNTERS]);

                return true;
            }
            ++$context[self::TREE_DEPTH_LIMIT_COUNTERS];
        } else {
            $context[self::TREE_DEPTH_LIMIT_COUNTERS] = 1;
        }

        return false;
    }

    /**
     * Handler function called when the configured MAX_TREE_DEPTH is reached.
     *
     * If a MAX_TREE_DEPTH_HANDLER is not set, the normalizer will throw a MaxTreeDepthException
     * upon reaching the MAX_TREE_DEPTH value.
     *
     * @param object $object
     * @param string|null $format
     * @param array $context
     *
     * @return mixed
     *
     * @throws MaxTreeDepthException
     */
    protected function handleMaxTreeDepth($object, string $format = null, array $context = []): mixed
    {
        $maxTreeDepthHandler =
            $context[self::MAX_TREE_DEPTH_HANDLER]
            ?? $this->defaultContext[self::MAX_TREE_DEPTH_HANDLER]
            ?? $this->maxTreeDepthHandler;
        if ($maxTreeDepthHandler) {
            return $maxTreeDepthHandler($object, $format, $context);
        }

        throw new MaxTreeDepthException(sprintf('Max tree depth has been reached while serializing the object of class "%s" (configured limit: %d)', \get_class($object), $this->treeDepthLimit));
    }
}
