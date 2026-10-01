<?php

declare(strict_types=1);

namespace Tueen\Telegram\Types\Concerns;

trait HasUserHelpers
{
    /**
     * Display name of the user (first_name + optional last_name).
     */
    public string $fullName {
        get {
            $parts = array_filter(
                [$this->firstName, $this->lastName ?? null],
                fn(?string $val) => $val !== null && trim($val) !== ''
            );

            return implode(' ', $parts);
        }
    }
}
