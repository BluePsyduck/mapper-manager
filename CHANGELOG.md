# Changelog

## 1.3.0 - 2022-02-22

### Added

- Method `mapList` to the `MapperManager` and the `MapperManagerInterface`, which allows to map a list of sources at 
- once.

### Removed

- Support for PHP 7.4. The minimal required version is now PHP 8.0.

## 1.2.0 - 2020-01-15

### Added

- Support for PHP 8.
- Generic doc-blocks to help with static analysers.
- Return value to `MapperManagerInterface->map()`, which now returns the passed in `$destination` back. This allows for 
  shorter code like `return $mapperManager->map($data, new Response());`.

### Removed

- Support for PHP 7.2 and 7.3.

## 1.1.1 - 2019-03-25

### Fixed

- Zend config adding unwanted aliases to the Service Manager.

## 1.1.0 - 2019-02-24

### Added

- `MapperManagerAwareInterface` to let the `MapperManager` be injected into mappers and adapters.

## 1.0.0 - 2019-02-08

- Initial version of the mapper manager.
