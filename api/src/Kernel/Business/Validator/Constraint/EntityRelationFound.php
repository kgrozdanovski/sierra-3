<?php

namespace App\Kernel\Business\Validator\Constraint;

use App\Kernel\Business\Validator\EntityRelationFoundValidator;
use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

#[\Attribute]
final class EntityRelationFound extends Constraint
{
    public const string NOT_FOUND_ERROR = '9138e8aa-aac3-4dfe-aa26-e83b498821a3';

    public string $message = 'Referenced entity instance relation is not found.';

    public string $entityName;

    public string $relation;

    #[HasNamedArguments]
    public function __construct(string $entityName, string $relation, array $groups = null, mixed $payload = null)
    {
        parent::__construct([], $groups, $payload);

        $this->entityName = $entityName;
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
