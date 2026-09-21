<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types\Concerns;

trait HasUserHelpers
{
    /**
     * Display name of the user (first_name + optional last_name).
     */
    public string $fullName {
        get => $this->getFullName();
    }

    /**
     * Resolves the full name of the user.
     */
    public function getFullName(): string
    {
        $parts = array_filter(
            [$this->firstName, $this->lastName ?? null],
            fn(?string $val) => $val !== null && trim($val) !== ''
        );

        return implode(' ', $parts);
    }
}
