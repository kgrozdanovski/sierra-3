<?php

/*
 * (c) Darko Poposki <poposki.darko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Kernel\Business\ValueObject;

use Symfony\Component\Serializer\Annotation\SerializedName;

final class Sorting
{
    public const int SORT_ASC = 0;

    public const int SORT_DESC = 1;

    public const array DEFAULT_SORT_BY = ['id'];

    public const array DEFAULT_SORT_DIR = [self::SORT_ASC];

    private array $sortBy;

    private array $sortDir;

    /**
     * @return string[]
     */
    public function getSortBy(): array
    {
        return $this->sortBy;
    }

    /**
     * @param array $sortBy
     *
     * @return $this
     */
    public function setSortBy(array $sortBy): self
    {
        $this->sortBy = $sortBy;

        return $this;
    }

    /**
     * @return int[]
     */
    public function getSortDir(): array
    {
        return $this->sortDir;
    }

    /**
     * @param array $sortDir
     *
     * @return $this
     */
    public function setSortDir(array $sortDir): self
    {
        $this->sortDir = $sortDir;

        return $this;
    }

    /**
     * @param int $sortDir
     *
     * @return string
     */
    private function getSortDirAsString(int $sortDir): string
    {
        return $sortDir == self::SORT_ASC ? 'ASC' : 'DESC';
    }

    /**
     * @param string $entityName
     *
     * @return array
     */
    public function getCollectionByEntityName(string $entityName): array
    {
        $sorting = [];

        foreach (array_combine($this->sortBy, $this->sortDir) as $sortBy => $sortDir) {
            $sortArgument = $this->findSortArgument($sortBy, $entityName);

            if ($sortArgument !== null) {
                $sorting[$sortArgument] = $this->getSortDirAsString($sortDir);
            }
        }

        return $sorting;
    }

    /**
     * @param string $argument
     * @param string $entityName
     *
     * @return string|null
     */
    private function findSortArgument(string $argument, string $entityName): ?string
    {
        try {
            $reflectionClass = new \ReflectionClass($entityName);

            if ($reflectionClass->hasProperty($argument)) {
                return $argument;
            }

            foreach ($reflectionClass->getProperties() as $property) {
                foreach ($property->getAttributes(SerializedName::class) as $attribute) {
                    if (in_array($argument, $attribute->getArguments())) {
                        return $property->getName();
                    }
                }
            }

            return null;
        } catch (\ReflectionException $exception) {
            // todo: log

            return null;
        }
    }
}
