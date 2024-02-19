<?php

namespace App\Kernel\Business\Validator\Constraint;

use App\Kernel\Business\Validator\EntityRelationNotFoundValidator;
use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

#[\Attribute]
final class EntityRelationNotFound extends Constraint
{
    public const IS_FOUND_ERROR = '28d719cf-782e-427d-857b-4d1216e3a539';

    public string $message = 'Referenced entity instance relation is found.';

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
        return EntityRelationNotFoundValidator::class;
    }
}
