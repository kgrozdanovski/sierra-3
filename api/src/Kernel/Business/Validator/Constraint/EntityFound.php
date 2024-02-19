<?php

namespace App\Kernel\Business\Validator\Constraint;

use App\Kernel\Business\Validator\EntityFoundValidator;
use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

#[\Attribute]
final class EntityFound extends Constraint
{
    public const NOT_FOUND_ERROR = '205a43fa-3a7c-48e6-9b51-9656e58b3744';

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
