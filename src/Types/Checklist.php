<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;

/**
 * Describes a checklist.
 *
 * @link https://core.telegram.org/bots/api#checklist
 */
class Checklist extends Type
{
    /**
     * Title of the checklist
     */
    #[Field('title', required: true)]
    public private(set) string $title;

    /**
     * Optional. Special entities that appear in the checklist title
     * @var MessageEntity[]|null
     */
    #[Field('title_entities', required: false)]
    #[ArrayOf(MessageEntity::class)]
    public private(set) ?array $titleEntities = null;

    /**
     * List of tasks in the checklist
     * @var ChecklistTask[]|null
     */
    #[Field('tasks', required: true)]
    #[ArrayOf(ChecklistTask::class)]
    public private(set) array $tasks;

    /**
     * Optional. True, if users other than the creator of the list can add tasks to the list
     */
    #[Field('others_can_add_tasks', required: false)]
    public private(set) ?bool $othersCanAddTasks = null;

    /**
     * Optional. True, if users other than the creator of the list can mark tasks as done or not done
     */
    #[Field('others_can_mark_tasks_as_done', required: false)]
    public private(set) ?bool $othersCanMarkTasksAsDone = null;

}
