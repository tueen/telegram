<?php

declare(strict_types=1);

namespace Tueen\Telegram\Context;

use Closure;
use ReflectionClass;
use ReflectionParameter;
use Tueen\Telegram\Enums\ChatAction;
use Tueen\Telegram\Exceptions\TelegramException;
use Tueen\Telegram\Formatting\Text;
use Tueen\Telegram\Keyboards\InlineKeyboard;
use Tueen\Telegram\Keyboards\ReplyKeyboard;
use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Types\Custom\InputFile;
use Tueen\Telegram\Types\Type;
use Tueen\Telegram\Types\Update;

/**
 * Contextual Parameter Resolver for Tueen Telegram Bot API Client.
 *
 * Automatically resolves and injects default contextual values (such as chat_id,
 * business_connection_id, message_thread_id, user_id, message_id, etc.)
 * from the active Telegram Update into API method calls.
 */
class ContextResolver
{
    /**
     * Active Telegram Update instance.
     */
    private ?Update $update = null;

    /**
     * Custom parameter resolver callbacks.
     * @var array<string, callable>
     */
    private array $customResolvers = [];

    /**
     * In-memory cache of constructor parameters for each method class.
     * @var array<string, list<string>>
     */
    private static array $constructorParamsCache = [];

    /**
     * Contextual parameter names mapped to their resolution handlers.
     */
    public const array CONTEXTUAL_PARAMETERS = [
        'chat_id',
        'chatId',
        'business_connection_id',
        'businessConnectionId',
        'message_thread_id',
        'messageThreadId',
        'direct_messages_topic_id',
        'directMessagesTopicId',
        'user_id',
        'userId',
        'message_id',
        'messageId',
        'inline_message_id',
        'inlineMessageId',
        'callback_query_id',
        'callbackQueryId',
        'inline_query_id',
        'inlineQueryId',
        'shipping_query_id',
        'shippingQueryId',
        'pre_checkout_query_id',
        'preCheckoutQueryId',
        'guest_query_id',
        'guestQueryId',
    ];

    /**
     * Edit methods that support the dual chat_id + message_id OR inline_message_id resolution.
     */
    private const array EDIT_METHODS = [
        'editMessageText',
        'editMessageCaption',
        'editMessageMedia',
        'editMessageReplyMarkup',
        'stopPoll',
        'editMessageLiveLocation',
        'stopMessageLiveLocation',
    ];

    public function __construct(?Update $update = null)
    {
        $this->update = $update;
    }

    /**
     * Updates the active Update instance.
     */
    public function setUpdate(?Update $update): static
    {
        $this->update = $update;
        return $this;
    }

    /**
     * Gets the current active Update instance.
     */
    public function getUpdate(): ?Update
    {
        return $this->update;
    }

    /**
     * Registers a custom default resolver for a parameter name.
     */
    public function bind(string $paramName, callable $resolver): static
    {
        $this->customResolvers[Type::toCamelCase($paramName)] = $resolver;
        $this->customResolvers[Type::toSnakeCase($paramName)] = $resolver;
        return $this;
    }

    /**
     * Resolves the current chat ID from the active update.
     */
    public function resolveChatId(): ?int
    {
        return $this->update?->findChatId();
    }

    /**
     * Resolves the business connection ID from the active update.
     */
    public function resolveBusinessConnectionId(): ?string
    {
        return $this->update?->findBusinessConnectionId();
    }

    /**
     * Resolves the message thread (forum topic) ID from the active update.
     */
    public function resolveMessageThreadId(): ?int
    {
        return $this->update?->findMessageThreadId();
    }

    /**
     * Resolves the acting user ID from the active update.
     */
    public function resolveUserId(): ?int
    {
        return $this->update?->findUserId();
    }

    /**
     * Resolves the primary message ID from the active update.
     */
    public function resolveMessageId(): ?int
    {
        return $this->update?->findMessageId();
    }

    /**
     * Resolves the inline message ID from the active update.
     */
    public function resolveInlineMessageId(): ?string
    {
        return $this->update?->findInlineMessageId();
    }

    /**
     * Resolves the callback query ID from the active update.
     */
    public function resolveCallbackQueryId(): ?string
    {
        return $this->update?->findCallbackQueryId();
    }

    /**
     * Resolves the inline query ID from the active update.
     */
    public function resolveInlineQueryId(): ?string
    {
        return $this->update?->findInlineQueryId();
    }

    /**
     * Resolves the shipping query ID from the active update.
     */
    public function resolveShippingQueryId(): ?string
    {
        return $this->update?->findShippingQueryId();
    }

    /**
     * Resolves the pre-checkout query ID from the active update.
     */
    public function resolvePreCheckoutQueryId(): ?string
    {
        return $this->update?->findPreCheckoutQueryId();
    }

    /**
     * Resolves the direct messages topic ID from the active update.
     */
    public function resolveDirectMessagesTopicId(): ?int
    {
        return $this->update?->findDirectMessagesTopicId();
    }

    /**
     * Resolves the guest query ID from the active update.
     */
    public function resolveGuestQueryId(): ?string
    {
        return $this->update?->findGuestQueryId();
    }

    /**
     * Resolves a contextual parameter value by name.
     */
    public function resolveParameter(string $paramName, ?string $endpoint = null): mixed
    {
        // 1. Check custom resolvers first
        if (isset($this->customResolvers[$paramName])) {
            return ($this->customResolvers[$paramName])($this->update, $endpoint);
        }

        $camel = Type::toCamelCase($paramName);
        if (isset($this->customResolvers[$camel])) {
            return ($this->customResolvers[$camel])($this->update, $endpoint);
        }

        // 2. Built-in resolvers
        return match ($camel) {
            'chatId' => $this->resolveChatId(),
            'businessConnectionId' => $this->resolveBusinessConnectionId(),
            'messageThreadId' => $this->resolveMessageThreadId(),
            'userId' => $this->resolveUserId(),
            'messageId' => $this->resolveMessageId(),
            'inlineMessageId' => $this->resolveInlineMessageId(),
            'callbackQueryId' => $this->resolveCallbackQueryId(),
            'inlineQueryId' => $this->resolveInlineQueryId(),
            'shippingQueryId' => $this->resolveShippingQueryId(),
            'preCheckoutQueryId' => $this->resolvePreCheckoutQueryId(),
            'directMessagesTopicId' => $this->resolveDirectMessagesTopicId(),
            'guestQueryId' => $this->resolveGuestQueryId(),
            default => null,
        };
    }

    /**
     * Resolves and normalizes arguments for instantiating a Method class.
     *
     * Handles:
     * - Named arguments missing contextual parameters or with null values.
     * - Positional arguments intelligently mapped to required content fields.
     * - Edit methods dual resolution (inline_message_id vs chat_id + message_id).
     *
     * @param class-string<Method> $className
     * @param array<array-key, mixed> $arguments
     * @return array<string, mixed>
     */
    public function resolveArguments(string $className, array $arguments): array
    {
        $shortName = lcfirst(basename(str_replace('\\', '/', $className)));

        // If single associative array passed, e.g. $telegram->sendMessage([...])
        if (count($arguments) === 1 && isset($arguments[0]) && is_array($arguments[0])) {
            $arguments = $arguments[0];
        }

        $paramNames = $this->getMethodParamNames($className);

        // 1. Check if arguments are positional (numeric keys)
        $isPositional = !empty($arguments) && array_is_list($arguments);

        if ($isPositional) {
            $arguments = $this->mapPositionalArguments($shortName, $paramNames, $arguments);
        }

        // 2. Handle edit methods special dual choice (inline_message_id vs chat_id + message_id)
        if (in_array($shortName, self::EDIT_METHODS, true)) {
            $hasChat = isset($arguments['chat_id']) || isset($arguments['chatId']);
            $hasMsg = isset($arguments['message_id']) || isset($arguments['messageId']);
            $hasInline = isset($arguments['inline_message_id']) || isset($arguments['inlineMessageId']);

            if (!$hasChat && !$hasMsg && !$hasInline) {
                $inlineId = $this->resolveInlineMessageId();
                if ($inlineId !== null) {
                    $arguments['inline_message_id'] = $inlineId;
                } else {
                    $chatId = $this->resolveChatId();
                    $msgId = $this->resolveMessageId();
                    if ($chatId !== null) {
                        $arguments['chat_id'] = $chatId;
                    }
                    if ($msgId !== null) {
                        $arguments['message_id'] = $msgId;
                    }
                }
            }
        }

        // 3. Auto-inject missing or null contextual parameters
        foreach ($paramNames as $pName) {
            $snake = Type::toSnakeCase($pName);

            $alreadyPassed = array_key_exists($pName, $arguments) || array_key_exists($snake, $arguments);
            $val = $arguments[$pName] ?? ($arguments[$snake] ?? null);

            // If not provided OR provided as null, attempt to resolve from context
            if (!$alreadyPassed || $val === null) {
                $resolved = $this->resolveParameter($pName, $shortName);
                if ($resolved !== null) {
                    $arguments[$pName] = $resolved;
                }
            }
        }

        return $arguments;
    }

    /**
     * Injects contextual defaults onto an already instantiated Method instance.
     */
    public function resolveMethod(Method $method): Method
    {
        if ($this->update === null) {
            return $method;
        }

        $endpoint = $method->getEndpoint();
        $reflection = new ReflectionClass($method);

        // Special handling for edit methods
        if (in_array($endpoint, self::EDIT_METHODS, true)) {
            $chatIdProp = $reflection->hasProperty('chatId') ? $reflection->getProperty('chatId') : null;
            $msgIdProp = $reflection->hasProperty('messageId') ? $reflection->getProperty('messageId') : null;
            $inlineProp = $reflection->hasProperty('inlineMessageId') ? $reflection->getProperty('inlineMessageId') : null;

            $hasChat = $chatIdProp && $chatIdProp->isInitialized($method) && $chatIdProp->getValue($method) !== null;
            $hasMsg = $msgIdProp && $msgIdProp->isInitialized($method) && $msgIdProp->getValue($method) !== null;
            $hasInline = $inlineProp && $inlineProp->isInitialized($method) && $inlineProp->getValue($method) !== null;

            if (!$hasChat && !$hasMsg && !$hasInline) {
                $inlineId = $this->resolveInlineMessageId();
                if ($inlineId !== null && $inlineProp) {
                    $inlineProp->setValue($method, $inlineId);
                } else {
                    if ($chatIdProp && $this->resolveChatId() !== null) {
                        $chatIdProp->setValue($method, $this->resolveChatId());
                    }
                    if ($msgIdProp && $this->resolveMessageId() !== null) {
                        $msgIdProp->setValue($method, $this->resolveMessageId());
                    }
                }
            }
        }

        // Standard properties resolution
        foreach ($reflection->getProperties() as $prop) {
            $name = $prop->getName();
            if ($name === 'parameters' || $name === 'files') {
                continue;
            }

            $currentVal = $prop->isInitialized($method) ? $prop->getValue($method) : null;
            if ($currentVal === null) {
                $resolved = $this->resolveParameter($name, $endpoint);
                if ($resolved !== null) {
                    $prop->setValue($method, $resolved);
                }
            }
        }

        return $method;
    }

    /**
     * Maps positional arguments to named parameters intelligently based on method semantics.
     *
     * @param string $endpoint
     * @param list<string> $paramNames
     * @param list<mixed> $arguments
     * @return array<string, mixed>
     */
    private function mapPositionalArguments(string $endpoint, array $paramNames, array $arguments): array
    {
        $count = count($arguments);
        if ($count === 0) {
            return [];
        }

        $mapped = [];

        // Single argument shortcuts (e.g. sendMessage('Hello'), sendPhoto($photo), deleteMessage())
        if ($count === 1) {
            $arg0 = $arguments[0];

            switch ($endpoint) {
                case 'sendMessage':
                case 'editMessageText':
                    if ($this->isProbableChatId($arg0)) {
                        $mapped['chat_id'] = $arg0;
                    } else {
                        $mapped['text'] = $arg0;
                    }
                    return $mapped;

                case 'sendPhoto':
                    if ($this->isProbableChatId($arg0)) {
                        $mapped['chat_id'] = $arg0;
                    } else {
                        $mapped['photo'] = $arg0;
                    }
                    return $mapped;

                case 'sendAudio':
                    $mapped['audio'] = $arg0;
                    return $mapped;

                case 'sendDocument':
                    $mapped['document'] = $arg0;
                    return $mapped;

                case 'sendVideo':
                    $mapped['video'] = $arg0;
                    return $mapped;

                case 'sendAnimation':
                    $mapped['animation'] = $arg0;
                    return $mapped;

                case 'sendVoice':
                    $mapped['voice'] = $arg0;
                    return $mapped;

                case 'sendVideoNote':
                    $mapped['video_note'] = $arg0;
                    return $mapped;

                case 'sendSticker':
                    $mapped['sticker'] = $arg0;
                    return $mapped;

                case 'sendChatAction':
                    $mapped['action'] = $arg0;
                    return $mapped;

                case 'answerCallbackQuery':
                    $mapped['text'] = $arg0;
                    return $mapped;

                case 'answerInlineQuery':
                    $mapped['results'] = $arg0;
                    return $mapped;

                case 'deleteMessage':
                    $mapped['message_id'] = $arg0;
                    return $mapped;

                case 'getUserProfilePhotos':
                case 'getUserProfileAudios':
                case 'getUserGifts':
                    $mapped['user_id'] = $arg0;
                    return $mapped;
            }
        }

        // Two arguments shortcuts (e.g. sendMessage(12345, 'Hello') or forwardMessage(999, 10))
        if ($count === 2) {
            if ($endpoint === 'sendMessage') {
                if ($this->isProbableChatId($arguments[0])) {
                    $mapped['chat_id'] = $arguments[0];
                    $mapped['text'] = $arguments[1];
                    return $mapped;
                }
            } elseif ($endpoint === 'sendChatAction') {
                if ($this->isProbableChatId($arguments[0])) {
                    $mapped['chat_id'] = $arguments[0];
                    $mapped['action'] = $arguments[1];
                    return $mapped;
                }
            } elseif ($endpoint === 'sendPhoto') {
                if ($this->isProbableChatId($arguments[0])) {
                    $mapped['chat_id'] = $arguments[0];
                    $mapped['photo'] = $arguments[1];
                    return $mapped;
                }
            }
        }

        // Standard sequential mapping as defined in constructor
        foreach ($arguments as $idx => $val) {
            if (isset($paramNames[$idx])) {
                $mapped[$paramNames[$idx]] = $val;
            }
        }

        return $mapped;
    }

    /**
     * Checks if a value looks like a Telegram chat_id (integer, numeric string, or @channelusername).
     */
    private function isProbableChatId(mixed $value): bool
    {
        if (is_int($value)) {
            return true;
        }

        if (is_string($value)) {
            if (str_starts_with($value, '@')) {
                return true;
            }
            if (is_numeric($value)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Cached reflection helper to retrieve constructor parameter names.
     *
     * @param class-string<Method> $className
     * @return list<string>
     */
    private function getMethodParamNames(string $className): array
    {
        if (isset(self::$constructorParamsCache[$className])) {
            return self::$constructorParamsCache[$className];
        }

        if (!class_exists($className)) {
            return [];
        }

        $ref = new ReflectionClass($className);
        $ctor = $ref->getConstructor();
        if ($ctor === null) {
            return self::$constructorParamsCache[$className] = [];
        }

        $names = [];
        foreach ($ctor->getParameters() as $p) {
            if ($p->isVariadic()) {
                continue;
            }
            $names[] = $p->getName();
        }

        return self::$constructorParamsCache[$className] = $names;
    }
}
