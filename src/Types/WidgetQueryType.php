<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

/**
 * This object describes the type of a query from a widget. Currently, it can be one of
 * - WidgetQueryTypeData
 * - WidgetQueryTypeSignData
 * - WidgetQueryTypeSentTransaction
 *
 * @link https://core.telegram.org/bots/api#widgetquerytype
 */
class WidgetQueryType extends Type
{


    /**
     * Resolves concrete child class dynamically based on discriminator fields.
     */
    public static function resolveChildClass(array $data): string
    {
        if (($data['type'] ?? '') === 'data') return WidgetQueryTypeData::class;
        if (($data['type'] ?? '') === 'sign_data') return WidgetQueryTypeSignData::class;
        if (($data['type'] ?? '') === 'sent_transaction') return WidgetQueryTypeSentTransaction::class;
        return static::class;
    }
}
