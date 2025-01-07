<?php
/**
 * Copyright (c) A.S Nassiry
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://github.com/nassiry/filesize-handler
 */

namespace Nassiry\FileSizeUtility;

use Nassiry\FileSizeUtility\Exceptions\FileSizeExceptions;
use Nassiry\FileSizeUtility\Sources\FileSourceAdapter;
use Nassiry\FileSizeUtility\Sources\FileSourceInterface;
use Nassiry\FileSizeUtility\Sources\LocalFiles;
use Nassiry\FileSizeUtility\Sources\NullFileSource;
use Nassiry\FileSizeUtility\Units\UnitHandler;

class FileSizeHandler
{
    /**
     * The base size unit for binary system (1024).
     */
    public const BASE_BINARY = 1024;

    /**
     * The base size unit for decimal system (1000).
     */
    public const BASE_DECIMAL = 1000;

    /**
     * The source interface that provides file data.
     *
     * @var FileSourceInterface
     */
    private FileSourceInterface $source;

    /**
     * The base for size calculation (default is BASE_BINARY).
     *
     * @var int
     */
    private int $base = self::BASE_BINARY;

    /**
     * The custom units for file size formatting, which should contain
     * 'binary_units' and 'decimal_units' arrays.
     *
     * @var array
     */
    private array $customUnits = [];

    /**
     * Cache for the formatted file size. It may be null if not cached.
     *
     * @var ?string
     */
    private ?string $formattedSizeCache = null;

    /**
     * The locale for the instance, defaults to null.
     * If not set, it will fall back to 'en_US'.
     *
     * @var ?string
     */
    private ?string $locale = null;


    /**
     * Constructor for initializing the file source and locale loader.
     *
     * @param FileSourceInterface $source The file source interface for handling files.
     */
    private function __construct(FileSourceInterface $source)
    {
        $this->source = $source;
    }

    /**
     * Returns the formatted file size as a string.
     *
     * If the formatted size is cached, it returns the cached value.
     * Otherwise, it computes the formatted size and returns it.
     *
     * @return string The formatted file size.
     */
    public function __toString(): string
    {
        return $this->formattedSizeCache ?? $this->formattedSize();
    }

    /**
     * Creates a new instance of the class with the specified locale and units.
     *
     * @param string|null $locale The locale string in the format 'en_US'. Defaults to 'en_US' if null.
     * @param array $units An array containing 'binary_units' and 'decimal_units'.
     * Each must be a non-empty array.
     *
     * @throws FileSizeExceptions If the locale format is invalid.
     * @throws FileSizeExceptions If the 'binary_units' or 'decimal_units' are not valid.
     *
     * @return self The created instance.
     */
    public static function create(
        ?string $locale = 'en_US',
        array $units = []
    ): self
    {
        $instance =  new self(new NullFileSource());

        if (!is_null($locale) && !preg_match('/^[a-z]{2}_[A-Z]{2}$/', $locale)) {
            throw FileSizeExceptions::localeFormat($locale);
        }

        $instance->locale = $locale ?? 'en_US';

        if (empty($units)) {
            $units = [
                'binary_units' => UnitHandler::getDefaultUnits(self::BASE_BINARY),
                'decimal_units' => UnitHandler::getDefaultUnits(self::BASE_DECIMAL),
            ];
        }

        UnitHandler::validateUnits($units);

        $instance->customUnits = $units;

        return $instance;
    }

    /**
     * Returns the formatted size of the file with the specified precision.
     *
     * This method calculates the size of the file, formats it based on the
     * appropriate units (either binary or decimal), and applies the specified
     * precision for the number of decimal places. It also uses the `locale`
     * property for formatting the number according to the specified locale.
     * The default locale is used for formatting if no specific locale is set,
     * and the units are formatted in the English system (`KB`, `MB` for decimal
     * and `KiB`, `MiB` for binary).
     *
     * If the formatted size has been previously cached, it returns the cached value.
     * Otherwise, it calculates the formatted size, caches it, and then returns it.
     *
     * @param int $precision The number of decimal places for the formatted size (default is 2).
     *
     * @return string The formatted size with the appropriate unit.
     */
    public function formattedSize(int $precision = 2): string
    {
        $bytes = $this->sizeInBytes();
        $units = $this->getUnits();

        $unitIndex = (int)floor(log($bytes, $this->base));
        $formattedSize = $bytes / pow($this->base, $unitIndex);

        $formatter = new \NumberFormatter($this->locale, \NumberFormatter::DECIMAL);
        $formatter->setAttribute(\NumberFormatter::FRACTION_DIGITS, $precision);

        $this->formattedSizeCache = sprintf('%s %s', $formatter->format($formattedSize), $units[$unitIndex]);

        return $this->formattedSizeCache;
    }

    /**
     * Returns the size of the file in bytes.
     *
     * @return int The size of the file in bytes.
     */
    public function sizeInBytes(): int
    {
        return $this->source->getSizeInBytes();
    }

    /**
     * Sets the base to the binary system (1024).
     *
     * @return self
     */
    public function baseBinary(): self
    {
        $this->base = self::BASE_BINARY;
        return $this;
    }

    /**
     * Sets the base to the decimal system (1000).
     *
     * @return self
     */
    public function baseDecimal(): self
    {
        $this->base = self::BASE_DECIMAL;
        return $this;
    }

    /**
     * Creates an instance from a generic file source.
     *
     * @param FileSourceInterface $source The file source interface.
     *
     * @return self
     */
    public function from(FileSourceInterface $source): self
    {
        $this->source = new FileSourceAdapter($source);
        return $this;
    }

    /**
     * Creates an instance from a local file path.
     *
     * @param string $filePath The path to the local file.
     *
     * @return self
     */
    public function local(string $filePath): self
    {
        $this->source = new LocalFiles($filePath);
        return $this;
    }

    /**
     * Retrieves the units for file size formatting based on the current base setting.
     *
     * This method returns an array of file size units. It will use the custom units
     * provided in the `$customUnits` property if they exist, otherwise, it will return
     * the default units based on the `BASE_BINARY` or `BASE_DECIMAL` base.
     *
     * If the base is set to binary (`BASE_BINARY`), the method returns the binary units
     * (`B`, `KiB`, `MiB`, ...). If the base is decimal, it returns decimal units
     * (`B`, `KB`, `MB`, ...).
     *
     * @return array The array of units for file size formatting.
     */
    private function getUnits(): array
    {
        if (!empty($this->customUnits)) {
            return $this->base === self::BASE_BINARY
                ? ($this->customUnits['binary_units'] ?? [])
                : ($this->customUnits['decimal_units'] ?? []);
        }

        return UnitHandler::getDefaultUnits($this->base);
    }
}