<?php

/*
 * (c) Darko Poposki <poposki.darko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Kernel\Business\ValueObject;

use App\Kernel\Communication\HttpKernel\Exception\ValidationException;  // todo: change namespace
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\GroupSequence;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class ValueObjectValidator
{
    public function __construct(
        private ValidatorInterface $validator
    ) {}

    /**
     * @param ValueObjectInterface $valueObject
     * @param Constraint|array|null $constraints
     * @param string|GroupSequence|array|null $groups
     *
     * @return ValueObjectInterface
     * @throws ValidationException
     */
    public function validate(
        ValueObjectInterface $valueObject,
        Constraint|array $constraints = null,
        string|GroupSequence|array $groups = null
    ): ValueObjectInterface {
        $violations = $this->validator->validate($valueObject, $constraints, $groups);

        if ($violations->count() > 0) {
            throw new ValidationException(json_encode(
                $this->getViolationMap($violations)
            ));
        }

        return $valueObject;
    }

    /**
     * @param ConstraintViolationListInterface $violationList
     *
     * @return array
     */
    private function getViolationMap(ConstraintViolationListInterface $violationList): array
    {
        $map = [];

        foreach ($violationList as $violation) {
            $map[$this->convertToSnakeCase($violation->getPropertyPath())] = ValidationException::map($violation->getCode());
        }

        return $map;
    }

    /**
     * @param string $input
     *
     * @return string
     */
    private function convertToSnakeCase(string $input): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }
}
