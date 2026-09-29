<?php

declare(strict_types=1);

namespace Tueen\Telegram\Flow\Attributes;

use Attribute;
use Tueen\Telegram\Enums\UpdateType;

/**
 * Declarative attribute to specify allowed update types for a Flow.
 *
 * Example:
 * #[AllowedUpdates('message', 'callback_query')]
 * or:
 * #[AllowedUpdates(UpdateType::MESSAGE, UpdateType::CALLBACK_QUERY)]
 */
#[Attribute(Attribute::TARGET_CLASS)]
class AllowedUpdates
{
    /** @var list<string|UpdateType> */
    public array $types;

    /**
     * @param string|UpdateType|list<string|UpdateType> ...$types
     */
    public function __construct(string|UpdateType|array ...$types)
    {
        $flattened = [];
        foreach ($types as $type) {
            if (is_array($type)) {
                $flattened = array_merge($flattened, $type);
            } else {
                $flattened[] = $type;
            }
        }
        $this->types = $flattened;
    }
}
