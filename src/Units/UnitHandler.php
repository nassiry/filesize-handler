<?php
/**
 * Copyright (c) A.S Nassiry
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://github.com/nassiry/filesize-handler
 */

namespace Nassiry\FileSizeUtility\Units;

use Nassiry\FileSizeUtility\Exceptions\FileSizeExceptions;
use Nassiry\FileSizeUtility\FileSizeHandler;

class UnitHandler
{
    /**
     * Returns the default file size units (binary or decimal) based on the provided base.
     *
     * @param string|int $base The base for unit calculation: either self::BASE_BINARY or self::BASE_DECIMAL.
     *
     * @return array The default units for either binary or decimal.
     */
    public static function getDefaultUnits(string|int $base): array
    {
        if ($base === FileSizeHandler::BASE_BINARY) {
            return ['B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB'];
        }

        return ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB', 'RB', 'QB'];
    }

    /**
     * Validates that the provided units are in the correct format.
     *
     * @param array $units The units to validate.
     * @throws FileSizeExceptions If the units are not in the correct format.
     */
    public static function validateUnits(array $units): void
    {
        if (
            !isset($units['binary_units'], $units['decimal_units']) ||
            !is_array($units['binary_units']) ||
            !is_array($units['decimal_units']) ||
            empty($units['binary_units']) ||
            empty($units['decimal_units'])
        ) {
            throw FileSizeExceptions::localeUnits($units);
        }
    }
}