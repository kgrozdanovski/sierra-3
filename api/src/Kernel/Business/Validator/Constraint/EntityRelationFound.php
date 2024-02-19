<?php

namespace App\Kernel\Business\Validator\Constraint;

use App\Kernel\Business\Validator\EntityRelationFoundValidator;
use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

#[\Attribute]
final class EntityRelationFound extends Constraint
{
    public const NOT_FOUND_ERROR = 'a61617a1-9950-48d3-b983-04d97474bc65';

    public string $message = 'Referenced entity instance relation is not found.';

    public string $entity;

    public string $relation;

    #[HasNamedArguments]
    public function __construct(string $entity, string $relation, array $groups = null, mixed $payload = null)
    {
        parent::__construct([], $groups, $payload);

        $this->entity = $entity;
        $this->relation = $relation;
    }

    /**
     * {@inheritdoc}
     */
    public function validatedBy(): string
    {
        return EntityRelationFoundValidator::class;
    }
}
