<?php

/*
 * (c) Darko Poposki <poposki.darko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace App\Kernel\Business\Serializer\Context\Builder;

use App\Kernel\Business\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Context\ContextBuilderInterface;
use Symfony\Component\Serializer\Context\ContextBuilderTrait;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

final class EntityContextBuilder implements ContextBuilderInterface
{
    use ContextBuilderTrait;

    /**
     * @param mixed $object
     *
     * @return EntityContextBuilder
     */
    public function withObjectToPopulate(mixed $object): EntityContextBuilder
    {
        return $this->with(AbstractNormalizer::OBJECT_TO_POPULATE, $object);
    }

    /**
     * @param bool $deepObjectToPopulate
     *
     * @return EntityContextBuilder
     */
    public function withDeepObjectToPopulate(bool $deepObjectToPopulate): EntityContextBuilder
    {
        return $this->with(AbstractObjectNormalizer::DEEP_OBJECT_TO_POPULATE, $deepObjectToPopulate);
    }

    /**
     * @param \Closure $callback
     *
     * @return EntityContextBuilder
     */
    public function withCircularReferenceHandler(\Closure $callback): EntityContextBuilder
    {
        return $this->with(AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER, $callback);
    }

    /**
     * @param \Closure $callback
     *
     * @return EntityContextBuilder
     */
    public function withMaxDepthHandler(\Closure $callback): EntityContextBuilder
    {
        return $this->with(AbstractObjectNormalizer::MAX_DEPTH_HANDLER, $callback);
    }

    /**
     * @param \Closure $callback
     *
     * @return EntityContextBuilder
     */
    public function withMaxTreeDepthHandler(\Closure $callback): EntityContextBuilder
    {
        return $this->with(ObjectNormalizer::MAX_TREE_DEPTH_HANDLER, $callback);
    }

    /**
     * @param string $format
     *
     * @return EntityContextBuilder
     */
    public function withDateTimeFormat(string $format): EntityContextBuilder
    {
        return $this->with(DateTimeNormalizer::FORMAT_KEY, $format);
    }

    /**
     * @param int $limit
     *
     * @return EntityContextBuilder
     */
    public function withCircularReferenceLimit(int $limit): EntityContextBuilder
    {
        return $this->with(AbstractNormalizer::CIRCULAR_REFERENCE_LIMIT, $limit);
    }

    /**
     * @param bool $allow
     *
     * @return EntityContextBuilder
     */
    public function withExtraAttributes(bool $allow): EntityContextBuilder
    {
        return $this->with(AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES, $allow);
    }

    /**
     * @param array $groups
     *
     * @return EntityContextBuilder
     */
    public function withGroups(array $groups): EntityContextBuilder
    {
        return $this->with(AbstractNormalizer::GROUPS, $groups);
    }

    /**
     * @param bool $skip
     *
     * @return EntityContextBuilder
     */
    public function withNullValues(bool $skip): EntityContextBuilder
    {
        return $this->with(AbstractObjectNormalizer::SKIP_NULL_VALUES, $skip);
    }

    /**
     * @param bool $enable
     *
     * @return $this
     */
    public function withMaxDepth(bool $enable): EntityContextBuilder
    {
        return $this->with(AbstractObjectNormalizer::ENABLE_MAX_DEPTH, $enable);
    }

    /**
     * @param bool $enable
     *
     * @return $this
     */
    public function withMaxTreeDepth(bool $enable): EntityContextBuilder
    {
        return $this->with(ObjectNormalizer::ENABLE_MAX_TREE_DEPTH, $enable);
    }

    /**
     * @param int $limit
     *
     * @return $this
     */
    public function withTreeDepthLimit(int $limit): EntityContextBuilder
    {
        return $this->with(ObjectNormalizer::TREE_DEPTH_LIMIT, $limit);
    }
}
