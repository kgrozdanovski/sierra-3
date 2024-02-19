<?php

namespace App\Kernel\Business\Validator;

use App\Kernel\Business\Validator\Constraint\EntityNotFound;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class EntityNotFoundValidator extends ConstraintValidator
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    /**
     * @param $value
     * @param Constraint $constraint
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof EntityNotFound) {
            throw new UnexpectedTypeException($constraint, EntityNotFound::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_int($value)) {
            throw new UnexpectedValueException($value, 'int');
        }

        $entity = $this->entityManager->getRepository($constraint->entity)->find($value);

        if ($entity !== null) {
            $this->context->buildViolation($constraint->message)
                ->setCode(EntityNotFound::IS_FOUND_ERROR)
                ->addViolation();
        }
    }
}
