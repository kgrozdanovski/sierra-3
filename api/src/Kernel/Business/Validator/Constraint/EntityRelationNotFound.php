<?php

namespace App\Kernel\Business\Validator\Constraint;

use App\Kernel\Business\Validator\EntityRelationNotFoundValidator;
use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

#[\Attribute]
final class EntityRelationNotFound extends Constraint
{
    public const string IS_FOUND_ERROR = 'b7b3341d-fa9f-490d-8d33-dee8eb52af9d';

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
