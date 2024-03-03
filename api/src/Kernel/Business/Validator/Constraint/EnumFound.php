<?php

namespace App\Kernel\Business\Validator\Constraint;

use App\Kernel\Business\Validator\EnumFoundValidator;
use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

#[\Attribute]
final class EnumFound extends Constraint
{
    public const string NOT_FOUND_ERROR = '7e15cc4b-19fb-44af-b7e5-6d4b36912d25';

    public string $message = 'Referenced enum value is not found.';

    public string $enum;

    #[HasNamedArguments]
    public function __construct(string $enum, array $groups = null, mixed $payload = null)
    {
        parent::__construct([], $groups, $payload);

        $this->enum = $enum;
    }

    /**
     * {@inheritdoc}
     */
    public function validatedBy(): string
    {
        return EnumFoundValidator::class;
    }
}
