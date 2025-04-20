<div align="center">

# PHP FileSizeHandler

![Packagist Downloads](https://img.shields.io/packagist/dt/nassiry/filesize-handler)
![Packagist Version](https://img.shields.io/packagist/v/nassiry/filesize-handler)
![PHP](https://img.shields.io/badge/PHP-%5E8.0-blue)
![License](https://img.shields.io/github/license/nassiry/filesize-handler)

</div>

The `FileSizeHandler` class is a utility designed to fetch and format file sizes across various file storage systems. It offers cross-platform compatibility with support for both **SI (decimal)** and **IEC (binary)** standards, making it HDD-agnostic and highly flexible.
- **Local Files**
- **Remote Files (HTTP/HTTPS)** (via [Remote File Extension](https://github.com/nassiry/filesize-handler-remote-extension))
- **FTP** (via [FTP Extension](https://github.com/nassiry/filesize-handler-ftp-extension))
- **Amazon S3** (via [S3 Extension](https://github.com/nassiry/filesize-handler-s3-extension))
- **Google Cloud Storage** (via [Google Cloud Extension](https://github.com/nassiry/filesize-handler-google-cloud-extension))
- **Custom Sources** (via user-implemented interfaces)

This package addresses the discrepancies in file size measurement (binary vs decimal units) and provides flexible APIs for developers.

### Features
1. **Supports Local, Remote, and FTP Files:**
    - Easily calculate file sizes from multiple sources.
2. **Binary and Decimal Calculations:**
    - Switch between binary (KiB, MiB, etc.) and decimal (KB, MB, etc.) bases with simple methods.
3. **Fluent Interface:**
    - Intuitive method chaining for base selection and size formatting.
4. **Extensibility:**
    - Ready for integration with cloud storage systems like S3 and Google Cloud.
5. **i18n Support:**
    - Format file size with custom localized units.
    - Dynamically provide translations for units.
6. **Chaining and Echo Support:**
    - Supports method chaining for cleaner code.
    - Directly `echo` the handler instance to get the formatted size.
7. **Custom Source Support**:
    - Implement `FileSourceInterface` and register your custom source.

## Table Of Contents
1. [Why Was This Class Created?](#why-was-this-class-created)
2. [Useful Links](#useful-links)
3. [Requirements](#requirements)
4. [Installation](#installation)
5. [Usage](#usage)
    - [Local File](#local-file)
    - [Extending the Library](#extending-the-library)
6. [Binary vs Decimal Units](#binary-vs-decimal-units)
    - [Switching Units](#switching-units)
    - [Dynamic Locale and Unit Customization](#dynamic-locale-and-unit-customization)
7. [API Reference](#api-reference)
    - [Source Methods](#source-methods)
    - [Configuration Methods](#configuration-methods)
    - [Output Methods](#output-methods)
8. [Extensions](#extensions)
    - [Available Extensions](#available-extensions)
9. [Contributing](#contributing)
10. [License](#license)

### Why Was This Class Created?
When working with file sizes, differences in measurement units across systems can cause confusion:
- **Cross-OS Consistency**: File size interpretations vary between operating systems (Windows, Linux, macOS) and hardware (HDD/SSD manufacturers often use decimal, while OS file systems may use binary).
- **Unified Interface**: Simplifies handling file size retrieval from diverse sources.
- **Extensibility**: Easily extend functionality by integrating additional storage systems via a simple, well-defined interface.


#### Useful Links
1. [Wikipedia: Binary Prefixes](https://en.wikipedia.org/wiki/Binary_prefix?utm_source=filesize-handler)
    - Comprehensive explanation of binary (KiB, MiB) vs. decimal (KB, MB) prefixes.
2. [NIST: Prefixes for Binary Multiples](https://physics.nist.gov/cuu/Units/binary.html?utm_source=filesize-handler)
    - Official standards for binary-based units.
3. [IBM: Units of Measurement for Storage Data](https://www.ibm.com/docs/en/storage-insights?topic=overview-units-measurement-storage-data)
    - Insights into storage measurement across platforms.
4. [Google: Byte Units](https://developers.google.com/style/units-of-measure#byte-units)
    - Guidelines for using decimal and binary units consistently.
5. [Metric View: What are binary prefixes?](https://metricviews.uk/2024/02/18/what-are-binary-prefixes/?utm_source=filesize-handler)
    - Provides an overview of binary prefixes and their usage

    
### Requirements
- **PHP**: Version 8.0 or higher
- **PHP Extension** - `ext-intl`: Required

## Installation
The recommended way use Composer to install the package:

```bash
composer require nassiry/filesize-handler
```
### Usage
### Local File
By default, the library supports fetching file sizes for local files.
```php
use Nassiry\FileSizeUtility\FileSizeHandler;

$handler = FileSizeHandler::create()
    ->local('/path/to/file')
    ->binary();

// Get the formatted size
echo $handler->format(); // Example output: "1.23 MiB"

// Or use directly with echo
echo FileSizeHandler::create()
    ->local('/path/to/file')
    ->binary()
    ->format();
```

### Extending the Library
To add support for a new file source, implement the `FileSourceInterface`:
```php
use Nassiry\FileSizeUtility\Sources\FileSourceInterface;

class CustomCloudSource implements FileSourceInterface
{
    public function __construct(private string $apiKey, private string $filePath) {}

    public function getSizeInBytes(): int
    {
        // Implement API logic to get file size
        return 123456789;
    }
}

$customSource = new CustomCloudSource('api-key', '/path/to/file');

// Once implemented, register your custom source using:
$handler = FileSizeHandler::create()
    ->from($customSource)
    ->binary();
    
echo $handler->format(); // Example output: "1.23 MiB"
```
> For more information on extending the library, check the [official extensions](#available-extensions) available.

## Binary vs Decimal Units
### Switching Units
```php
$handler = FileSizeHandler::create()
    ->local('/path/to/file.txt');

// Default: Binary (1024-based)
echo $handler->format(); // Output: "1.23 MiB"

// Switch to Decimal (1000-based)
echo $handler->decimal()->format(); // Output: "1.30 MB"
```

### Dynamic Locale and Unit Customization

```php
$customUnits = [
    'binary_units' => ['o', 'Kio', 'Mio', 'Gio', 'Tio', 'Pio', 'Eio', 'Zio', 'Yio'],
    'decimal_units' => ['o', 'Ko', 'Mo', 'Go', 'To', 'Po', 'Eo', 'Zo', 'Yo'],
];

$handler = FileSizeHandler::create('fr_FR', $customUnits)
    ->local('/path/to/file')
    ->binary();

echo $handler->format(1); // Example output: "1,2 Mio"
```
#### Key Points:
Two-letter country codes are based on the [ISO 3166-1 alpha-2](https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2) standard and are used in locale naming conventions.

- **Examples of Supported Locales**: `en_US`, `fr_FR`, `de_DE`.
- **The Default**: units and locale are set to `en_US`

## API Reference

#### `FileSizeHandler::create(string $locale = 'en_US', array $units = [])`
Initializes a new `FileSizeHandler` instance.

#### Source Methods
- `local(string $filePath): self`  
  Creates an instance for a local file.

- `from(FileSourceInterface $source): self`  
  Allows integration of a custom file source by implementing the `FileSourceInterface`.

#### Configuration Methods
- `binary(): self`  
  Switches unit calculation to binary (1024-based).

- `decimal(): self`  
  Switches unit calculation to decimal (1000-based).
  
#### Output Methods

- `bytes(): int`  
  Returns the file size in bytes.

- `format(int $precision = 2): string`  
  Returns the formatted file size with the specified precision.

### Extensions

`FileSizeHandler` is extensible via optional extensions for advanced integrations. These extensions add support for remote and cloud-based file sources. Install the extensions via Composer.

#### Available Extensions:

| Extension      | Description                                     | Installation Command                                               | Documentation     |
|----------------|-------------------------------------------------|-------------------------------------------------------------------|--------------------|
| **FTP**        | Support for FTP file size retrieval.           | `composer require nassiry/filesize-handler-ftp-extension`        |  [See Full Documentation](https://github.com/nassiry/filesize-handler-ftp-extension) |
| **Amazon S3**  | Fetch file sizes from Amazon S3 buckets.       | `composer require nassiry/filesize-handler-s3-extension`         |  [See Full Documentation](https://github.com/nassiry/filesize-handler-s3-extension) |
| **Google Cloud** | Retrieve file sizes from Google Cloud Storage. | `composer require nassiry/filesize-handler-googlecloud-extension` |  [See Full Documentation](https://github.com/nassiry/filesize-handler-google-cloud-extension) |
| **Remote**     | Support for files accessible via HTTP/HTTPS.   | `composer require nassiry/filesize-handler-remote-extension`     |  [See Full Documentation](https://github.com/nassiry/filesize-handler-remote-extension) |


### Contributing
Feel free to submit issues or pull requests to improve the package. Contributions are welcome!


### Changelog

See [CHANGELOG](CHANGELOG.md) for release details.

#### ⚠️ Deprecated Methods

As of version 1.1.0, the following methods are deprecated and will be removed in version 2.0.0:

| Deprecated        | Use Instead |
|------------------|-------------|
| `baseBinary()`    | `binary()`  |
| `baseDecimal()`   | `decimal()` |
| `formattedSize()` | `format()`  |
| `sizeInBytes()`   | `bytes()`   |

### License
This package is open-source software licensed under the [MIT license](LICENSE).
