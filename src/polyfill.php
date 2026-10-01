<?php

declare(strict_types=1);

if (!function_exists('array_first')) {
    /**
     * Polyfill for PHP 8.5 array_first.
     * Returns the value of the first element of an array, or null if the array is empty.
     *
     * @param array<mixed> $array
     */
    function array_first(array $array): mixed
    {
        return !empty($array) ? $array[array_key_first($array)] : null;
    }
}

if (!function_exists('array_last')) {
    /**
     * Polyfill for PHP 8.5 array_last.
     * Returns the value of the last element of an array, or null if the array is empty.
     *
     * @param array<mixed> $array
     */
    function array_last(array $array): mixed
    {
        return !empty($array) ? $array[array_key_last($array)] : null;
    }
}
