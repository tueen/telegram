<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * Describes a service message about tasks added to a checklist.
 *
 * @link https://core.telegram.org/bots/api#checklisttasksadded
 */
class ChecklistTasksAdded extends Type
{
    /**
     * Optional. Message containing the checklist to which the tasks were added. Note that the Message object in this field will not contain the reply_to_message field even if it itself is a reply.
     */
    #[Field('checklist_message', required: false)]
    private(set) ?Message $checklistMessage = null;

    /**
     * List of tasks added to the checklist
     * @var ChecklistTask[]|null
     */
    #[Field('tasks', required: true)]
    #[ArrayOf(ChecklistTask::class)]
    private(set) array $tasks;

}
