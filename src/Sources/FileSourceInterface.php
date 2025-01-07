<?php
/**
 * Copyright (c) A.S Nassiry
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://github.com/nassiry/filesize-handler
 */

namespace Nassiry\FileSizeUtility\Sources;

interface FileSourceInterface
{
    /**
     * Gets the size of the file in bytes from the local file system.
     *
     * @return int The size of the file in bytes.
     */
    public function getSizeInBytes(): int;
}