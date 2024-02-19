<?php

namespace App\Kernel\Business\Validator;

use App\Kernel\Business\Validator\Constraint\EnumFound;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class EnumFoundValidator extends ConstraintValidator
{
    /**
     * @param $value
     * @param Constraint $constraint
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof EnumFound) {
            throw new UnexpectedTypeException($constraint, EnumFound::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_int($value) && !is_string($value)) {
            throw new UnexpectedValueException($value, 'int|string');
        }

        try {
            $constraint->enum::from($value);
        } catch (\Throwable $exception) {
            $this->context->buildViolation($constraint->message)
                ->setCode(EnumFound::NOT_FOUND_ERROR)
                ->addViolation();
        }
    }
}
