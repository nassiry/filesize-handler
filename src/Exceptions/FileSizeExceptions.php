<?php
/**
 * Copyright (c) A.S Nassiry
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://github.com/nassiry/filesize-handler
 */

namespace Nassiry\FileSizeUtility\Exceptions;

use RuntimeException;

class FileSizeExceptions extends RuntimeException
{
    // Source not found.
    public static function sourceNotFound(): self
    {
        return new self('No file source has been set.');
    }

    // File not found locally
    public static function fileNotFound($filePath): self
    {
        return new self("File not found: {$filePath}");
    }

    // Unsupported locale format.
    public static function localeFormat($locale): self
    {
        return new self("Invalid locale format: {$locale}. Expected format is 'en_US'.");
    }

    // Invalid locale units array.
    public static function localeUnits($units): self
    {
        return new self("The {$units} array must contain non-empty 'binary_units' and 'decimal_units' keys as arrays.");
    }
}