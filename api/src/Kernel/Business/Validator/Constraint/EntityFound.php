<?php

namespace App\Kernel\Business\Validator\Constraint;

use App\Kernel\Business\Validator\EntityFoundValidator;
use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

#[\Attribute]
final class EntityFound extends Constraint
{
    public const string NOT_FOUND_ERROR = 'e1252fff-2f55-4712-b0fc-35f1fee15605';

    public string $message = 'Referenced entity instance is not found.';

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
        return EntityFoundValidator::class;
    }
}
