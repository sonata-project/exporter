# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [2.6.0](https://github.com/sonata-project/exporter/compare/2.5.2...2.6.0) - 2021-03-24
### Added
- [[#463](https://github.com/sonata-project/exporter/pull/463)] Added template typehint for SourceIteratorInterface ([@VincentLanglet](https://github.com/VincentLanglet))

## [2.5.2](https://github.com/sonata-project/exporter/compare/2.5.1...2.5.2) - 2021-03-21
### Fixed
- [[#458](https://github.com/sonata-project/exporter/pull/458)] Fixed iterating over documents when using `DoctrineODMQuerySourceIterator` ([@franmomu](https://github.com/franmomu))

## [2.5.1](https://github.com/sonata-project/exporter/compare/2.5.0...2.5.1) - 2021-02-15
### Fixed
- [[#431](https://github.com/sonata-project/exporter/pull/431)] Php version constraint ([@franmomu](https://github.com/franmomu))

## [2.5.0](https://github.com/sonata-project/exporter/compare/2.4.1...2.5.0) - 2021-01-04
### Added
- [[#424](https://github.com/sonata-project/exporter/pull/424)] Support for PHP8 ([@VincentLanglet](https://github.com/VincentLanglet))

## [2.4.1](https://github.com/sonata-project/exporter/compare/2.4.0...2.4.1) - 2020-10-09
### Fixed
- [[#387](https://github.com/sonata-project/exporter/pull/387)] Redeclared class `Sonata\Exporter\Bridge\Symfony\Bundle\SonataExporterBundle` ([@phansys](https://github.com/phansys))

## [2.4.0](sonata-project/exporter/compare/2.3.0...2.4.0) - 2020-10-09
### Added
- [[#371](https://github.com/sonata-project/exporter/pull/371)] Add the ability to call the `rewind()` method multiple times for `DoctrineDBALConnectionSourceIterator`, `DoctrineODMQuerySourceIterator`, `DoctrineORMQuerySourceIterator` ([@VincentLanglet](https://github.com/VincentLanglet))
- [[#375](https://github.com/sonata-project/exporter/pull/375)] Added conflict with `doctrine/mongodb-odm` <1.3 ([@franmomu](https://github.com/franmomu))
- [[#345](https://github.com/sonata-project/exporter/pull/345)] Added `Sonata\Exporter\Bridge\Symfony\SonataExporterBundle` ([@phansys](https://github.com/phansys))
- [[#345](https://github.com/sonata-project/exporter/pull/345)] Added `Sonata\Exporter\Bridge\Symfony\SonataExporterSymfonyBundle` alias in order to fix Symfony Flex autodiscovery ([@phansys](https://github.com/phansys))

### Deprecated
- [[#345](https://github.com/sonata-project/exporter/pull/345)] Deprecated `Sonata\Exporter\Bridge\Symfony\Bundle\SonataExporterBundle` in favor of `Sonata\Exporter\Bridge\Symfony\SonataExporterBundle` ([@phansys](https://github.com/phansys))

### Fixed
- [[#380](https://github.com/sonata-project/exporter/pull/380)] Memory leaks in Doctrine source iterators ([@ossinkine](https://github.com/ossinkine))

### Removed
- [[#346](https://github.com/sonata-project/exporter/pull/346)] Removed support for "symfony/*:<4.4" ([@phansys](https://github.com/phansys))

## [2.3.0](https://github.com/sonata-project/exporter/compare/2.2.0...2.3.0) - 2020-07-13
### Added
- [[#343](https://github.com/sonata-project/exporter/pull/343)] Added support for array and traversable in `DoctrineORMQuerySourceIterator`, `DoctrineODMQuerySourceIterator` and `PropelCollectionSourceIterator` ([@VincentLanglet](https://github.com/VincentLanglet))

## [2.2.0](https://github.com/sonata-project/exporter/compare/2.1.0...2.2.0) - 2020-03-17
### Added
- Compatibility with Symfony 5

## [2.1.0](https://github.com/sonata-project/exporter/compare/2.0.1...2.1.0) - 2020-02-06
### Changed
- Exceptions extending `\RuntimeException` now do so indirectly through
  `Sonata\Exporter\Exception\RuntimeException`

### Fixed
- Fix deprecation for symfony/config 4.2+

## [2.0.1](https://github.com/sonata-project/exporter/compare/2.0.0...2.0.1) - 2019-01-26
### Fixed
- Fixed wrong namespace usage

## [2.0.0](https://github.com/sonata-project/exporter/compare/1.10.0...2.0.0) - 2018-12-15
## Added
- parameter and return type hints

## Changed
- The namespace was changed from `Exporter` to `Sonata\Exporter`.
- Many classes have been made final.

## Removed
- Symfony 2.3 to 2.7 support dropped
- php < 7.2 support

## [1.10.0](https://github.com/sonata-project/exporter/compare/1.9.1...1.10.0) - 2018-12-11
### Changed
- Added support for exporting `DateInterval` values

## [1.9.1](https://github.com/sonata-project/exporter/compare/1.9.0...1.9.1) - 2018-07-04

- Made `sonata.exporter.exporter` service public

## [1.9.0](https://github.com/sonata-project/exporter/compare/1.8.0...1.9.0) - 2018-05-10

### Added
- Added support for custom terminators with `CsvWriter` class

### Fixed
- CsvWriter actually uses the escape parameter.

## [1.8.0](https://github.com/sonata-project/exporter/compare/1.7.1...1.8.0) - 2017-11-30
### Fixed
- Allow `\DateTimeImmutable` values
- It is now allowed to install Symfony 4

### Removed
- Support for old versions of PHP and Symfony.

## [1.7.1](https://github.com/sonata-project/exporter/compare/1.7.0...1.7.1) - 2017-02-09
### Fixed
- \Exporter\Exporter::addWriter is now public as needed by the related bundle

## [1.7.0](https://github.com/sonata-project/exporter/compare/1.6.0...1.7.0) - 2016-08-17
### Added
- Added some `Exporter::getAvailableFormats` to retrieve the list of the formats of the registered writers.

## [1.6.0](https://github.com/sonata-project/exporter/compare/1.5.0...1.6.0) - 2016-08-01
### Added
- Added `Exporter\Exporter` class to provide a Symfony `StreamedResponse`.
- Added a `sonata.exporter.exporter` service to deprecate the one defined in the admin bundle

### Deprecated
- Deprecate `Test\Writer\AbstractTypedWriterTestCase` in favor of `Test\AbstractTypedWriterTestCase`

## [1.5.0](https://github.com/sonata-project/exporter/compare/1.4.1...1.5.0) - 2016-06-16
### Added
- `MimeTypedWriterInterface` can be implemented to indicate the suitable `Content-Type` header and format for a writer.

### Changed
- Rename `lib` folder to `src` and make this project PSR-4 compliant.

## [1.4.0](https://github.com/sonata-project/exporter/compare/1.3.4...1.4.0) - 2015-06-09
### Added
- Add possibility to set custom tag names on `XmlSourceIterator`

### Changed
- Replaced deprecated `PropertyAccess::getPropertyAccessor()` method `PropertyAccess::createPropertyAccessor()`.

### Removed
- Symfony 2.2 support dropped.

## [1.2.2](https://github.com/sonata-project/exporter/compare/1.2.1...1.2.2) - 2013-05-02
### Added
- Add new argument in method \Exporter\Writer\SitemapWriter::generateSitemapIndex to handle absolute URL.
