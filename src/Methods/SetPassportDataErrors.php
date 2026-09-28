<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\Custom\BooleanResult;

/**
 * Informs a user that some of the Telegram Passport elements they provided contains errors. The user will not be able to re-submit their Passport to you until the errors are fixed (the contents of the field for which you returned the error must change). Returns True on success.
 * Use this if the data submitted by the user doesn't satisfy the standards your service requires for any reason. For example, if a birthday date seems invalid, a submitted document is blurry, a scan shows evidence of tampering, etc. Supply some details in the error message to make sure the user knows how to correct the issues.
 *
 * @link https://core.telegram.org/bots/api#setpassportdataerrors
 */
#[ApiMethod('setPassportDataErrors', 'POST')]
#[ReturnType(BooleanResult::class, isArray: false)]
class SetPassportDataErrors extends Method
{
    /**
     * User identifier
     */
    #[Field('user_id', required: true)]
    public ?int $userId = null;

    /**
     * A JSON-serialized Array describing the errors
     */
    #[Field('errors', required: true)]
    public ?array $errors = null;

    public function __construct(
        ?int $userId = null,
        ?array $errors = null,
        mixed ...$extra
    )
    {
        if ($userId !== null) $this->userId = $userId;
        if ($errors !== null) $this->errors = $errors;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
