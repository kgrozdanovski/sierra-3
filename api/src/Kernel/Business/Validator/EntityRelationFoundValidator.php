<?php

namespace App\Kernel\Business\Validator;

use App\Kernel\Business\Validator\Constraint\EntityRelationFound;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class EntityRelationFoundValidator extends ConstraintValidator
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
        if (!$constraint instanceof EntityRelationFound) {
            throw new UnexpectedTypeException($constraint, EntityRelationFound::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_int($value) && !is_string($value)) {
            throw new UnexpectedValueException($value, 'int|string');
        }

        $entity = $this->entityManager->getRepository($constraint->entity)->findOneBy([$constraint->relation => $value]);

        if ($entity === null) {
            $this->context->buildViolation($constraint->message)
                ->setCode(EntityRelationFound::NOT_FOUND_ERROR)
                ->addViolation();
        }
    }
}
