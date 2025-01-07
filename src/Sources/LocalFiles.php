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

class LocalFiles implements FileSourceInterface
{
    /**
     * The locale file path.
     *
     * @var string
     */
    private string $filePath;

    /**
     * LocalFiles constructor.
     *
     * Initializes the file path and checks if the file exists.
     *
     * @param string $filePath The path to the local file.
     *
     * @throws FileSizeExceptions If the file does not exist.
     */
    public function __construct(string $filePath)
    {
        if (!file_exists($filePath)) {
            throw FileSizeExceptions::fileNotFound($filePath);
        }
        $this->filePath = $filePath;
    }

    /**
     * Gets the size of the file in bytes from the local file system.
     *
     * @return int The size of the file in bytes.
     */
    public function getSizeInBytes(): int
    {
        return filesize($this->filePath);
    }
}