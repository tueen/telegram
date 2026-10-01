<?php

declare(strict_types=1);

namespace Tueen\Telegram\Flow;

use ReflectionClass;
use ReflectionMethod;
use Throwable;
use Tueen\Telegram\Flow\Attributes\Action;
use Tueen\Telegram\Flow\Attributes\Breadcrumb;
use Tueen\Telegram\Flow\Attributes\Debounce;
use Tueen\Telegram\Flow\Attributes\Input;
use Tueen\Telegram\Flow\Attributes\RequireMember;
use Tueen\Telegram\Keyboards\InlineKeyboard;
use Tueen\Telegram\Types\InlineKeyboardMarkup;
use Tueen\Telegram\Types\Message;
use Tueen\Telegram\Types\Update;

/**
 * Base class for interactive, screen-based conversation flows.
 *
 * Provides in-place message rendering (zero-flicker transitions),
 * comprehensive lifecycle hooks (onCreate, onStart, onResume, onPause),
 * hierarchical navigation stack (push/pop/home), and declarative action binding (#[Action]).
 */
abstract class InteractiveFlow extends Flow
{
    protected ?array $pendingAlert = null;

    /**
     * Active Telegram message ID managed by this screen.
     */
    public ?int $messageId {
        get => $this->state->messageId;
        set {
            $this->state->messageId = $value;
        }
    }

    /**
     * Resolves the human-readable breadcrumb label for this flow.
     */
    public string $breadcrumbTitle {
        get {
            $reflection = new ReflectionClass($this);
            $attributes = $reflection->getAttributes(Breadcrumb::class);
            if (!empty($attributes)) {
                /** @var Breadcrumb $bc */
                $bc = $attributes[0]->newInstance();
                return $bc->title;
            }

            $shortName = $reflection->getShortName();
            return preg_replace('/Flow$/', '', $shortName) ?: $shortName;
        }
    }

    /**
     * Shows a modal alert dialog on the user's screen in response to a button click.
     */
    public function alert(string $text, ?string $url = null, ?int $cacheTime = null): static
    {
        $this->pendingAlert = [
            'text' => $text,
            'showAlert' => true,
            'url' => $url,
            'cacheTime' => $cacheTime,
        ];
        return $this;
    }

    /**
     * Shows a transient toast notification at the bottom of the user's screen in response to a button click.
     */
    public function toast(string $text, ?string $url = null, ?int $cacheTime = null): static
    {
        $this->pendingAlert = [
            'text' => $text,
            'showAlert' => false,
            'url' => $url,
            'cacheTime' => $cacheTime,
        ];
        return $this;
    }

    /**
     * Resends the screen at the bottom of the chat, optionally deleting the old message.
     * Useful when user text input has pushed the interactive menu up in chat history.
     */
    public function resendAtBottom(bool $deleteOld = true): void
    {
        if ($deleteOld && $this->messageId !== null) {
            try {
                $this->bot->deleteMessage(chatId: $this->chatId, messageId: $this->messageId);
            } catch (Throwable) {
                // Ignore if message was already deleted or expired
            }
        }

        $this->messageId = null;
        $this->renderScreen();
    }

    /**
     * Displays an in-place confirmation dialog with confirm and cancel buttons.
     */
    public function confirm(
        string $prompt,
        string $onConfirmedAction,
        string $confirmLabel = '✅',
        string $cancelLabel = '❌'
    ): void {
        $screen = Screen::make($prompt)
            ->inline(fn (InlineKeyboard $k) => $k
                ->action($confirmLabel, $onConfirmedAction)
                ->action($cancelLabel, Navigation::BACK_ACTION)
            )
            ->withNavigation(back: false, home: false);

        $this->renderScreen($screen);
    }

    /**
     * Toggles an item key inside an array stored in the flow state, then refreshes the screen.
     *
     * @return list<string> The updated array of selected keys
     */
    public function toggleChecklist(string $stateKey, string|int $itemKey): array
    {
        $current = (array) $this->get($stateKey, []);
        $current = array_map('strval', $current);
        $strKey = (string) $itemKey;

        if (in_array($strKey, $current, true)) {
            $current = array_values(array_filter($current, fn ($v) => $v !== $strKey));
        } else {
            $current[] = $strKey;
        }

        $this->set($stateKey, $current);
        $this->refresh();
        return $current;
    }

    /**
     * Verifies that the active user is a member of the required channel or group.
     */
    public function checkMembership(RequireMember $guard): bool
    {
        if ($this->userId === null) {
            return true;
        }

        try {
            $member = $this->bot->getChatMember(
                chatId: $guard->channel,
                userId: $this->userId
            );

            $status = $member->status ?? '';
            if (in_array($status, ['creator', 'administrator', 'member'], true)) {
                return true;
            }
            if ($status === 'restricted' && ($member->isMember ?? false)) {
                return true;
            }
        } catch (Throwable) {
            // Suppress errors during offline tests or unreachable channels
        }

        $channelName = (string) $guard->channel;
        $text = str_replace('{channel}', $channelName, $guard->fallbackMessage ?? "⚠️ You must join {$channelName} to continue.");
        $url = $guard->joinUrl ?? (str_starts_with($channelName, '@') ? 'https://t.me/' . ltrim($channelName, '@') : null);

        $screen = Screen::make($text)
            ->inline(function (InlineKeyboard $k) use ($url, $guard) {
                if ($url !== null) {
                    $k->url('📢 Join Channel', $url);
                }
                $k->action($guard->checkButtonLabel, 'flow:refresh');
            })
            ->withNavigation(back: true, home: true);

        $this->renderScreen($screen);
        return false;
    }

    /**
     * Default entry point for interactive flows.
     */
    #[\Override]
    public function start(Update $update): void
    {
        $this->onCreate();
        $this->onStart();
        $this->renderScreen();
    }

    /**
     * Lifecycle hook: executed once when the flow instance is created.
     */
    public function onCreate(): void
    {
        // Child classes can override this for initial data loading
    }

    /**
     * Lifecycle hook: executed when entering the start step.
     */
    public function onStart(): void
    {
        // Child classes can override this for initial setup
    }

    /**
     * Lifecycle hook: executed when returning to this flow from a pushed child flow.
     */
    public function onResume(mixed $result = null): void
    {
        // Child classes can override this to handle data returned by a child flow
    }

    /**
     * Lifecycle hook: executed before suspending this flow and pushing a child flow.
     */
    public function onPause(): void
    {
        // Child classes can override this to persist transient state
    }

    /**
     * Lifecycle hook: executed right before rendering or editing the screen.
     */
    public function beforeRender(Screen $screen): void
    {
        // Child classes can override to dynamically modify the screen
    }

    /**
     * Lifecycle hook: executed immediately after a successful render or edit.
     */
    public function afterRender(mixed $sentMessage): void
    {
        // Child classes can override for post-render logic
    }

    /**
     * Lifecycle hook: invoked when an update doesn't match any registered action or step.
     */
    public function onUnhandled(Update $update): void
    {
        // Default behavior: refresh the current screen to keep UI consistent
        $this->refresh();
    }

    /**
     * Determines whether the flow permits interruption by /start or global exit commands.
     * Return false during critical steps (e.g. checkout, forms) to lock the user in.
     */
    public function canInterrupt(Update $update): bool
    {
        return true;
    }

    /**
     * Defines the Screen to display for the current state.
     */
    public function render(): Screen
    {
        return Screen::make();
    }

    /**
     * Renders or edits the active screen in-place.
     */
    public function renderScreen(?Screen $screen = null): void
    {
        $screen ??= $this->render();
        $this->beforeRender($screen);

        if ($screen->enableBreadcrumbs) {
            $crumbs = [];
            foreach ($this->state->flowStack as $parent) {
                if (!empty($parent['breadcrumb'])) {
                    $crumbs[] = (string) $parent['breadcrumb'];
                }
            }
            $crumbs[] = $this->breadcrumbTitle;
            $header = implode($screen->breadcrumbsSeparator, $crumbs);
            $screen->text($header . "\n\n" . $screen->text);
        }

        $builtKeyboard = $screen->buildKeyboard();
        $isInlineMarkup = $builtKeyboard === null || $builtKeyboard instanceof InlineKeyboardMarkup;

        $mediaType = $screen->mediaType;
        $mediaSource = $screen->mediaSource;

        // 1. Media screen rendering (photo, video, animation)
        if ($mediaType !== null) {
            if ($this->messageId !== null && $screen->editIfPossible && $isInlineMarkup) {
                try {
                    $response = $this->bot->editMessageCaption(
                        chatId: $this->chatId,
                        messageId: $this->messageId,
                        caption: $screen->text,
                        parseMode: $screen->parseMode,
                        replyMarkup: $builtKeyboard,
                    );
                    $this->afterRender($response);
                    return;
                } catch (Throwable $e) {
                    if (str_contains(strtolower($e->getMessage()), 'message is not modified')) {
                        return;
                    }
                }
            }

            $params = [
                'chatId' => $this->chatId,
                'caption' => $screen->text,
            ];

            if ($screen->parseMode !== null) {
                $params['parseMode'] = $screen->parseMode;
            }

            if ($builtKeyboard !== null) {
                $params['replyMarkup'] = $builtKeyboard;
            }

            $response = match ($mediaType) {
                'video' => $this->bot->sendVideo(...array_merge($params, ['video' => $mediaSource])),
                'animation' => $this->bot->sendAnimation(...array_merge($params, ['animation' => $mediaSource])),
                default => $this->bot->sendPhoto(...array_merge($params, ['photo' => $mediaSource])),
            };

            if ($response instanceof Message && $response->messageId !== null) {
                $this->messageId = $response->messageId;
                $this->manager->saveState($this->sessionKey, $this->state, $this->ttl);
            }

            $this->afterRender($response);
            return;
        }

        // 2. Text screen in-place edit attempt
        if ($this->messageId !== null && $screen->editIfPossible && $isInlineMarkup) {
            try {
                $response = $this->bot->editMessageText(
                    chatId: $this->chatId,
                    messageId: $this->messageId,
                    text: $screen->text,
                    parseMode: $screen->parseMode,
                    replyMarkup: $builtKeyboard,
                );

                $this->afterRender($response);
                return;
            } catch (Throwable $e) {
                // Ignore if Telegram reports message content is identical
                if (str_contains(strtolower($e->getMessage()), 'message is not modified')) {
                    return;
                }
                // Otherwise fall through to send a new message
            }
        }

        // 3. Fallback or initial message dispatch
        $params = [
            'chatId' => $this->chatId,
            'text' => $screen->text,
        ];

        if ($screen->parseMode !== null) {
            $params['parseMode'] = $screen->parseMode;
        }

        if ($builtKeyboard !== null) {
            $params['replyMarkup'] = $builtKeyboard;
        }

        $response = $this->bot->sendMessage(...$params);

        if ($response instanceof Message && $response->messageId !== null) {
            $this->messageId = $response->messageId;
            $this->manager->saveState($this->sessionKey, $this->state, $this->ttl);
        }

        $this->afterRender($response);
    }

    /**
     * Re-renders the current screen in-place.
     */
    public function refresh(?string $notice = null): void
    {
        $this->renderScreen();
    }

    /**
     * Suspends the current flow, pushes it onto the navigation stack, and transitions to a child flow.
     * The child flow inherits the current messageId for zero-flicker UI updates.
     *
     * @param class-string<Flow> $flowClass
     * @param array<string, mixed> $data
     */
    public function push(string $flowClass, array $data = []): void
    {
        $this->onPause();

        // Push current state snapshot onto the stack
        $this->state->flowStack[] = [
            'flowClass' => static::class,
            'currentStep' => $this->state->currentStep,
            'data' => $this->state->data,
            'messageId' => $this->state->messageId,
            'breadcrumb' => $this->breadcrumbTitle,
        ];

        $this->state->flowClass = $flowClass;
        $this->state->currentStep = 'start';
        if (!empty($data)) {
            $this->state->data = array_merge($this->state->data, $data);
        }

        $this->manager->saveState($this->sessionKey, $this->state, $this->ttl);

        // Instantiate and start child flow
        $child = $this->manager->createFlowInstance($flowClass, $this->bot);
        $child->init($this->bot, $this->update, $this->chatId, $this->userId, $this->state, $this->manager);

        if ($child instanceof InteractiveFlow) {
            $child->onCreate();
            $child->onStart();
            $child->renderScreen();
        } elseif (method_exists($child, 'start')) {
            $child->start($this->update);
        }
    }

    /**
     * Pops the current flow from the navigation stack and resumes the parent flow on the same message.
     */
    public function pop(mixed $result = null): void
    {
        if (!empty($this->state->flowStack)) {
            $parentSnapshot = array_pop($this->state->flowStack);
            $this->onExit($this->update, 'popped');

            $this->state->flowClass = $parentSnapshot['flowClass'];
            $this->state->currentStep = $parentSnapshot['currentStep'];
            $this->state->data = $parentSnapshot['data'];
            // Retain active messageId for zero-flicker resume

            $this->manager->saveState($this->sessionKey, $this->state, $this->ttl);

            $parentFlow = $this->manager->createFlowInstance($parentSnapshot['flowClass'], $this->bot);
            $parentFlow->init($this->bot, $this->update, $this->chatId, $this->userId, $this->state, $this->manager);

            if ($parentFlow instanceof InteractiveFlow) {
                $parentFlow->onResume($result);
                $parentFlow->renderScreen();
            } else {
                $step = $parentFlow->state->currentStep;
                if (method_exists($parentFlow, $step)) {
                    $parentFlow->$step($this->update);
                }
            }

            return;
        }

        // If stack is empty, navigate back in step history or finish
        $this->back();
    }

    /**
     * Resets the entire flow stack and transitions to the configured rootFlow, or finishes.
     */
    public function home(): void
    {
        $this->state->flowStack = [];

        $rootFlow = $this->manager->rootFlow;
        if ($rootFlow !== null && $rootFlow !== static::class) {
            $this->jumpTo($rootFlow);
            return;
        }

        $this->finish();
    }

    /**
     * Seamlessly transitions the user to another Flow class while preserving the active messageId.
     *
     * @param class-string<Flow> $flowClass
     * @param string $initialStep
     * @param array<string, mixed> $initialData
     * @param array<string, mixed> $data
     */
    #[\Override]
    public function jumpTo(
        string $flowClass,
        string $initialStep = 'start',
        array $initialData = [],
        array $data = []
    ): void {
        $this->onExit($this->update, 'interrupted');
        $passedData = !empty($initialData) ? $initialData : $data;
        $mergedData = array_merge($this->state->data, $passedData);

        // Keep active messageId in state
        $this->state->flowClass = $flowClass;
        $this->state->currentStep = $initialStep;
        $this->state->data = $mergedData;

        $this->manager->saveState($this->sessionKey, $this->state, $this->ttl);

        $nextFlow = $this->manager->createFlowInstance($flowClass, $this->bot);
        $nextFlow->init($this->bot, $this->update, $this->chatId, $this->userId, $this->state, $this->manager);

        if ($nextFlow instanceof InteractiveFlow) {
            $nextFlow->onCreate();
            if ($initialStep === 'start') {
                $nextFlow->onStart();
                $nextFlow->renderScreen();
            } elseif (method_exists($nextFlow, $initialStep)) {
                $nextFlow->$initialStep($this->update);
            }
        } elseif (method_exists($nextFlow, $initialStep)) {
            $nextFlow->$initialStep($this->update);
        }
    }

    /**
     * Handles an incoming Update for this interactive flow.
     */
    public function handleUpdate(Update $update): bool
    {
        // Check class-level channel membership guards
        $classGuards = (new ReflectionClass($this))->getAttributes(RequireMember::class);
        foreach ($classGuards as $guardAttr) {
            /** @var RequireMember $guard */
            $guard = $guardAttr->newInstance();
            if (!$this->checkMembership($guard)) {
                return true;
            }
        }

        // 1. Handle Callback Query
        if ($update->callbackQuery !== null) {
            $callbackData = $update->callbackQuery->data ?? '';
            $callbackQueryId = $update->callbackQuery->id;
            $handled = false;

            // Check built-in navigation actions
            if ($callbackData === Navigation::BACK_ACTION) {
                $this->pop();
                $handled = true;
            } elseif ($callbackData === Navigation::HOME_ACTION) {
                $this->home();
                $handled = true;
            } elseif ($callbackData === 'noop') {
                $handled = true;
            } elseif ($callbackData === 'flow:refresh') {
                $this->refresh();
                $handled = true;
            } else {
                // Match #[Action] attributes
                $handled = $this->dispatchAction($callbackData, $update);
            }

            // Acknowledge callback query with alert/toast if queued, or default acknowledgement
            if ($callbackQueryId !== null) {
                try {
                    $answerParams = ['callbackQueryId' => $callbackQueryId];
                    if ($this->pendingAlert !== null) {
                        $answerParams['text'] = $this->pendingAlert['text'];
                        $answerParams['showAlert'] = $this->pendingAlert['showAlert'];
                        if ($this->pendingAlert['url'] !== null) {
                            $answerParams['url'] = $this->pendingAlert['url'];
                        }
                        if ($this->pendingAlert['cacheTime'] !== null) {
                            $answerParams['cacheTime'] = $this->pendingAlert['cacheTime'];
                        }
                        $this->pendingAlert = null;
                    }
                    $this->bot->answerCallbackQuery(...$answerParams);
                } catch (Throwable) {
                    // Suppress network or timeout errors on acknowledge
                }
            }

            if ($handled) {
                return true;
            }
        }

        // 2. Handle Text Input
        $text = trim($update->findAnyText() ?? '');
        if ($text !== '') {
            // Check built-in navigation text defaults (🔙 and 🏠)
            if ($text === Navigation::DEFAULT_BACK_LABEL) {
                $this->pop();
                return true;
            }

            if ($text === Navigation::DEFAULT_HOME_LABEL) {
                $this->home();
                return true;
            }

            // Match #[Input] attributes
            if ($this->dispatchInput($update)) {
                return true;
            }
        }

        // 3. Fallback to standard step method if present and not default start/render
        $currentStep = $this->state->currentStep;
        if ($currentStep !== 'start' && $currentStep !== 'render' && method_exists($this, $currentStep)) {
            $this->$currentStep($update);
            return true;
        }

        // 4. Trigger onUnhandled hook
        $this->onUnhandled($update);
        return !$this->isPassedThrough;
    }

    /**
     * Dispatches callback query to methods annotated with #[Action].
     */
    protected function dispatchAction(string $callbackData, Update $update): bool
    {
        $reflection = new ReflectionClass($this);

        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            $attributes = $method->getAttributes(Action::class);
            foreach ($attributes as $attribute) {
                /** @var Action $action */
                $action = $attribute->newInstance();

                $parameters = [];
                if ($this->matchesActionPattern($action->pattern, $callbackData, $parameters)) {
                    // Check method-level RequireMember guards
                    $methodGuards = $method->getAttributes(RequireMember::class);
                    foreach ($methodGuards as $guardAttr) {
                        /** @var RequireMember $guard */
                        $guard = $guardAttr->newInstance();
                        if (!$this->checkMembership($guard)) {
                            return true;
                        }
                    }

                    // Check Debounce guard
                    $debounceAttrs = $method->getAttributes(Debounce::class);
                    if (!empty($debounceAttrs)) {
                        /** @var Debounce $debounce */
                        $debounce = $debounceAttrs[0]->newInstance();
                        $debounceKey = '__debounce_' . $method->getName();
                        $lastTime = (float) $this->get($debounceKey, 0.0);
                        $now = microtime(true);

                        if (($now - $lastTime) < $debounce->seconds) {
                            if ($debounce->notice !== null) {
                                $this->pendingAlert = [
                                    'text' => $debounce->notice,
                                    'showAlert' => $debounce->showAlert,
                                    'url' => null,
                                    'cacheTime' => null,
                                ];
                            }
                            return true;
                        }

                        $this->set($debounceKey, $now);
                    }

                    $args = $this->resolveMethodArguments($method, $update, $parameters);
                    $method->invokeArgs($this, $args);
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Dispatches message to methods annotated with #[Input].
     */
    protected function dispatchInput(Update $update): bool
    {
        $reflection = new ReflectionClass($this);

        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            $attributes = $method->getAttributes(Input::class);
            if (!empty($attributes)) {
                $args = $this->resolveMethodArguments($method, $update, []);
                $method->invokeArgs($this, $args);
                return true;
            }
        }

        return false;
    }

    /**
     * Matches an action pattern (e.g. 'item_{id}') against callback data.
     *
     * @param array<string, string> $parameters
     */
    protected function matchesActionPattern(string $pattern, string $callbackData, array &$parameters = []): bool
    {
        $parameters = [];

        // Exact match
        if ($pattern === $callbackData) {
            return true;
        }

        // Parameterized pattern match, e.g. item_{id}
        if (str_contains($pattern, '{')) {
            $regex = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^:]+)', $pattern);
            $regex = "#^{$regex}$#u";

            if (preg_match($regex, $callbackData, $matches)) {
                foreach ($matches as $key => $value) {
                    if (is_string($key)) {
                        $parameters[$key] = $value;
                    }
                }
                return true;
            }
        }

        return false;
    }

    /**
     * Resolves arguments for an action or input method.
     *
     * @param array<string, string> $parameters
     * @return list<mixed>
     */
    protected function resolveMethodArguments(ReflectionMethod $method, Update $update, array $parameters): array
    {
        $args = [];

        foreach ($method->getParameters() as $param) {
            $name = $param->getName();
            $type = $param->getType();
            $typeName = $type !== null && method_exists($type, 'getName') ? $type->getName() : null;

            if ($typeName === Update::class) {
                $args[] = $update;
            } elseif (array_key_exists($name, $parameters)) {
                $val = $parameters[$name];
                if ($typeName === 'int') {
                    $args[] = (int) $val;
                } elseif ($typeName === 'float') {
                    $args[] = (float) $val;
                } elseif ($typeName === 'bool') {
                    $args[] = filter_var($val, FILTER_VALIDATE_BOOLEAN);
                } else {
                    $args[] = $val;
                }
            } elseif ($param->isDefaultValueAvailable()) {
                $args[] = $param->getDefaultValue();
            } else {
                $args[] = null;
            }
        }

        return $args;
    }
}
