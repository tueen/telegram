<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Types\UniqueGift;

/**
 * Use this method to get information about a unique gift by its name. Returns a UniqueGift object on success.
 *
 * @link https://core.telegram.org/bots/api#getuniquegift
 */
#[ApiMethod('getUniqueGift', 'POST')]
#[ReturnType(UniqueGift::class, isArray: false)]
class GetUniqueGift extends Method
{
    /**
     * Unique name of the gift
     */
    #[Field('name', required: true)]
    public string $name;

    public function __construct(
        string $name,
        mixed ...$extra
    )
    {
        $this->name = $name;
        if ($extra) $this->handleExtraParameters($extra);
    }
}
