<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types;

use Tueen\Telegram\Attributes\ArrayOf;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Enums\ParseMode;

/**
 * Describes a checklist to create.
 *
 * @link https://core.telegram.org/bots/api#inputchecklist
 */
class InputChecklist extends Type
{
    /**
     * Title of the checklist; 1-255 characters after entities parsing
     */
    #[Field('title', required: true)]
    private(set) string $title;

    /**
     * Optional. Mode for parsing entities in the title. See formatting options for more details.
     */
    #[Field('parse_mode', required: false)]
    private(set) ParseMode|string|null $parseMode = null;

    /**
     * Optional. List of special entities that appear in the title, which can be specified instead of parse_mode. Currently, only bold, italic, underline, strikethrough, spoiler, custom_emoji, and date_time entities are allowed.
     * @var MessageEntity[]|null
     */
    #[Field('title_entities', required: false)]
    #[ArrayOf(MessageEntity::class)]
    private(set) ?array $titleEntities = null;

    /**
     * List of 1-30 tasks in the checklist
     * @var InputChecklistTask[]|null
     */
    #[Field('tasks', required: true)]
    #[ArrayOf(InputChecklistTask::class)]
    private(set) array $tasks;

    /**
     * Optional. Pass True if other users can add tasks to the checklist
     */
    #[Field('others_can_add_tasks', required: false)]
    private(set) ?bool $othersCanAddTasks = null;

    /**
     * Optional. Pass True if other users can mark tasks as done or not done in the checklist
     */
    #[Field('others_can_mark_tasks_as_done', required: false)]
    private(set) ?bool $othersCanMarkTasksAsDone = null;

}
