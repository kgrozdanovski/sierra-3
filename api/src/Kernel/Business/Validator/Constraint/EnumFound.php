<?php

namespace App\Kernel\Business\Validator\Constraint;

use App\Kernel\Business\Validator\EnumFoundValidator;
use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

#[\Attribute]
final class EnumFound extends Constraint
{
    public const NOT_FOUND_ERROR = 'af1540bd-dfc3-4e63-a970-f56c5616bd0a';

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
