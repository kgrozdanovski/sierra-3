<?php

namespace App\Kernel\Business\Validator\Constraint;

use App\Kernel\Business\Validator\EntityNotFoundValidator;
use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

#[\Attribute]
final class EntityNotFound extends Constraint
{
    public const IS_FOUND_ERROR = '9a5f8093-2218-4b1d-8bab-a3b29fef8fe6';

    public string $message = 'Referenced entity instance is found.';

    public string $entity;

    #[HasNamedArguments]
    public function __construct(string $entity, array $groups = null, mixed $payload = null)
    {
        parent::__construct([], $groups, $payload);

        $this->entity = $entity;
    }

    /**
     * {@inheritdoc}
     */
    public function validatedBy(): string
    {
        return EntityNotFoundValidator::class;
    }
}
