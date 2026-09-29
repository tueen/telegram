<?php

declare(strict_types=1);

namespace Tueen\Telegram\App;

use ReflectionClass;
use ReflectionMethod;
use Tueen\Telegram\Routing\Attributes\OnCallbackQuery;
use Tueen\Telegram\Routing\Attributes\OnCommand;
use Tueen\Telegram\Routing\Attributes\OnInlineQuery;
use Tueen\Telegram\Routing\Attributes\OnMessage;
use Tueen\Telegram\Routing\Attributes\OnUpdate;
use Tueen\Telegram\Telegram;

/**
 * Scans directories to automatically discover and register attribute-decorated controllers.
 */
class ControllerDiscovery
{
    /**
     * Discovers and registers all controller classes within a directory.
     *
     * @return list<class-string> List of registered controller class names
     */
    public static function discover(string $directory, Telegram $bot): array
    {
        if (!is_dir($directory)) {
            return [];
        }

        $registered = [];
        $files = glob(rtrim($directory, '/\\') . '/*.php');
        if ($files === false) {
            return [];
        }

        foreach ($files as $file) {
            $className = self::extractClassFromFile($file);
            if ($className === null) {
                continue;
            }

            if (!class_exists($className, false)) {
                require_once $file;
            }

            if (class_exists($className) && self::isControllerClass($className)) {
                $bot->registerController($className);
                $registered[] = $className;
            }
        }

        return $registered;
    }

    /**
     * Checks if a class contains any routing attributes on its public methods.
     */
    private static function isControllerClass(string $className): bool
    {
        try {
            $ref = new ReflectionClass($className);
            if ($ref->isAbstract() || $ref->isInterface() || $ref->isTrait()) {
                return false;
            }

            foreach ($ref->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
                if (
                    !empty($method->getAttributes(OnCommand::class)) ||
                    !empty($method->getAttributes(OnCallbackQuery::class)) ||
                    !empty($method->getAttributes(OnMessage::class)) ||
                    !empty($method->getAttributes(OnInlineQuery::class)) ||
                    !empty($method->getAttributes(OnUpdate::class))
                ) {
                    return true;
                }
            }
        } catch (\Throwable) {
            return false;
        }

        return false;
    }

    /**
     * Extracts fully-qualified class name from a PHP file using token analysis.
     */
    public static function extractClassFromFile(string $filePath): ?string
    {
        $contents = @file_get_contents($filePath);
        if ($contents === false) {
            return null;
        }

        $tokens = token_get_all($contents);
        $namespace = '';
        $class = '';
        $gettingNamespace = false;
        $gettingClass = false;

        foreach ($tokens as $token) {
            if (is_array($token)) {
                if ($token[0] === T_NAMESPACE) {
                    $gettingNamespace = true;
                } elseif ($token[0] === T_CLASS) {
                    $gettingClass = true;
                } elseif ($gettingNamespace) {
                    if ($token[0] === T_NAME_QUALIFIED || $token[0] === T_STRING) {
                        $namespace .= $token[1];
                    }
                } elseif ($gettingClass && $token[0] === T_STRING) {
                    $class = $token[1];
                    break;
                }
            } elseif ($token === ';' && $gettingNamespace) {
                $gettingNamespace = false;
            }
        }

        if (empty($class)) {
            return null;
        }

        return $namespace !== '' ? "{$namespace}\\{$class}" : $class;
    }
}
