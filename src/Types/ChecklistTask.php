<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * Describes a task in a checklist.
 *
 * @link https://core.telegram.org/bots/api#checklisttask
 */
class ChecklistTask extends Type
{
    /**
     * Unique identifier of the task
     */
    #[Field('id', required: true)]
    public private(set) int $id;

    /**
     * Text of the task
     */
    #[Field('text', required: true)]
    public private(set) string $text;

    /**
     * Optional. Special entities that appear in the task text
     * @var MessageEntity[]|null
     */
    #[Field('text_entities', required: false)]
    #[ArrayOf(MessageEntity::class)]
    public private(set) ?array $textEntities = null;

    /**
     * Optional. User that completed the task; omitted if the task wasn't completed by a user
     */
    #[Field('completed_by_user', required: false)]
    public private(set) ?User $completedByUser = null;

    /**
     * Optional. Chat that completed the task; omitted if the task wasn't completed by a chat
     */
    #[Field('completed_by_chat', required: false)]
    public private(set) ?Chat $completedByChat = null;

    /**
     * Optional. Point in time (Unix timestamp) when the task was completed; 0 if the task wasn't completed
     */
    #[Field('completion_date', required: false)]
    public private(set) ?int $completionDate = null;

}
