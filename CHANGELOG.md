# Changelog

All notable changes to this project will be documented in this file. See [standard-version](https://github.com/conventional-changelog/standard-version) for commit guidelines.

## [1.1.0] - 2025-04-20
### Changed
- Renamed public methods for improved clarity:
    - `baseBinary()` ➜ `binary()`
    - `baseDecimal()` ➜ `decimal()`
    - `formattedSize()` ➜ `format()`
    - `sizeInBytes()` ➜ `bytes()`

### Deprecated
- Old method names (`baseBinary()`, `baseDecimal()`, `formattedSize()`, `sizeInBytes()`) are now deprecated and will be removed in a future major release (v2.0.0). They will continue to work but trigger a deprecation warning.

## [1.0.0] - 2025-01-07
### Added
- Initial stable release