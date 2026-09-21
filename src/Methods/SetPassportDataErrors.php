<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;

/**
 * Informs a user that some of the Telegram Passport elements they provided contains errors. The user will not be able to re-submit their Passport to you until the errors are fixed (the contents of the field for which you returned the error must change). Returns True on success.
 * Use this if the data submitted by the user doesn't satisfy the standards your service requires for any reason. For example, if a birthday date seems invalid, a submitted document is blurry, a scan shows evidence of tampering, etc. Supply some details in the error message to make sure the user knows how to correct the issues.
 *
 * @link https://core.telegram.org/bots/api#setpassportdataerrors
 */
#[ApiMethod('setPassportDataErrors', 'POST')]
#[ReturnType(Type::class, isArray: false)]
class SetPassportDataErrors extends Method
{
    /**
     * User identifier
     */
    #[Field('user_id', required: true)]
    public int $userId;

    /**
     * A JSON-serialized Array describing the errors
     */
    #[Field('errors', required: true)]
    public array $errors;

    public function __construct(
        int $userId,
        array $errors
    )
    {
        if ($userId !== null) $this->userId = $userId;
        if ($errors !== null) $this->errors = $errors;
    }

    public static function make(
        int $userId,
        array $errors
    ): static
    {
        return new static($userId, $errors);
    }
}
