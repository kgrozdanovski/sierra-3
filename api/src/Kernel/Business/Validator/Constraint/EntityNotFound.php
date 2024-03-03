<?php

namespace App\Kernel\Business\Validator\Constraint;

use App\Kernel\Business\Validator\EntityNotFoundValidator;
use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

#[\Attribute]
final class EntityNotFound extends Constraint
{
    public const string IS_FOUND_ERROR = '06179157-9cc6-4b54-84f7-ec029e8471db';

    public string $message = 'Referenced entity instance is found.';

    public string $entityName;

    #[HasNamedArguments]
    public function __construct(string $entityName, array $groups = null, mixed $payload = null)
    {
        parent::__construct([], $groups, $payload);

        $this->entityName = $entityName;
    }

    /**
     * {@inheritdoc}
     */
    public function validatedBy(): string
    {
        return EntityNotFoundValidator::class;
    }
}
