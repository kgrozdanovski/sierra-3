<?php

/*
 * (c) Kristijan Grozdanovski <kgrozdanovski7@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * ValidationException class.
 *
 * @category GLS
 * @package  Validate
 */

namespace App\Kernel\Communication\HttpKernel\Exception;

use App\Kernel\Business\Validator\Constraint\EntityFound;
use App\Kernel\Business\Validator\Constraint\EntityNotFound;
use App\Kernel\Business\Validator\Constraint\EntityRelationFound;
use App\Kernel\Business\Validator\Constraint\EntityRelationNotFound;
use App\Kernel\Business\Validator\Constraint\EnumFound;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Type;

/**
 * Class ValidationException
 *
 * @package App\Content
 */
class ValidationException extends \Exception
{
    // Wrong type
    public const NOT_NUMERIC = 'not_numeric';   // deprecated
    public const NOT_ARRAY = 'not_array';   // deprecated
    public const NOT_BOOL = 'not_bool'; // deprecated
    public const NOT_STRING = 'not_string'; // deprecated

    // General
    public const INVALID_PARAMETER = 'invalid_parameter';
    public const INVALID_VALUE = 'invalid_value';
    public const INVALID_TYPE = 'invalid_type';
    public const EMPTY = 'empty';
    public const IS_NULL = 'is_null';

    // Strings
    public const TOO_SHORT = 'too_short';
    public const TOO_LONG = 'too_long';
    public const NOT_EQUAL_LENGTH = 'not_equal_length';
    public const BAD_FORMAT = 'bad_format';

    // Entity
    public const ALREADY_IN_USE = 'already_in_use';
    public const DUPLICATE = 'duplicate';
    public const NONEXISTENT = 'nonexistent';
    public const INVALID_STATE = 'invalid_state';

    /**
     * ValidationException constructor.
     *
     * @param string|array $message <empty>
     * @param \Throwable|null $previous <null> Exception chaining
     */
    public function __construct($message = '', \Throwable $previous = null)
    {
        $isJson = is_string($message) && is_array(json_decode($message, true)) && (json_last_error() == JSON_ERROR_NONE);

        if (!$isJson) {
            $message = json_encode($message);
        }

        parent::__construct($message, 0, $previous);
    }

    /**
     * Map Symfony ConstraintViolation error codes (UUID)
     * to internal string constants.
     *
     * @param string $code
     *
     * @return string
     */
    public static function map(string $code): string
    {
        return match ($code) {
            NotBlank::IS_BLANK_ERROR => self::EMPTY,
            NotNull::IS_NULL_ERROR => self::IS_NULL,
            Type::INVALID_TYPE_ERROR => self::INVALID_TYPE,
            Length::TOO_SHORT_ERROR => self::TOO_SHORT,
            Length::TOO_LONG_ERROR => self::TOO_LONG,
            Length::NOT_EQUAL_LENGTH_ERROR => self::NOT_EQUAL_LENGTH,
            Length::INVALID_CHARACTERS_ERROR => self::BAD_FORMAT,
            Email::INVALID_FORMAT_ERROR => self::BAD_FORMAT,
            GreaterThan::TOO_LOW_ERROR => self::INVALID_VALUE,
            LessThanOrEqual::TOO_HIGH_ERROR => self::INVALID_VALUE,
            EntityFound::NOT_FOUND_ERROR => self::NONEXISTENT,
            EntityNotFound::IS_FOUND_ERROR => self::ALREADY_IN_USE,
            EntityRelationFound::NOT_FOUND_ERROR => self::NONEXISTENT,
            EntityRelationNotFound::IS_FOUND_ERROR => self::ALREADY_IN_USE,
            EnumFound::NOT_FOUND_ERROR => self::NONEXISTENT,
            default => self::INVALID_VALUE,
        };
    }
}
