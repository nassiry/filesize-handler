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

class FileSourceAdapter implements FileSourceInterface
{
    /**
     * The source file object implementing FileSourceInterface.
     *
     * @var FileSourceInterface
     */
    private FileSourceInterface $source;

    /**
     * FileSourceAdapter constructor.
     *
     * Initializes the adapter with the given file source.
     *
     * @param FileSourceInterface $source The file source object to be adapted.
     */
    public function __construct(FileSourceInterface $source)
    {
        $this->source = $source;
    }

    /**
     * Gets the size of the file in bytes by delegating to the underlying file source.
     *
     * @return int The size of the file in bytes.
     */
    public function getSizeInBytes(): int
    {
        return $this->source->getSizeInBytes();
    }
}