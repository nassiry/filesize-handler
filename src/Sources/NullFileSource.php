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

use Nassiry\FileSizeUtility\Exceptions\FileSizeExceptions;

class NullFileSource implements FileSourceInterface
{
    /**
     * Throws a FileSizeExceptions as no file source has been set.
     *
     * @throws FileSizeExceptions Always throws an exception to signal that no file source
     * has been set. This method is meant to be used as a placeholder
     * in the absence of a valid file source.
     *
     * @return int This method will never return a value, as it always throws an exception.
     */
    public function getSizeInBytes(): int
    {
        throw FileSizeExceptions::sourceNotFound();
    }
}