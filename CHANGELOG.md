# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [3.2.0](https://github.com/sonata-project/exporter/compare/3.1.1...3.2.0) - 2023-07-22
### Removed
- [[#631](https://github.com/sonata-project/exporter/pull/631)] Drop support for older versions of doctrine orm and mongodb-odm ([@jordisala1991](https://github.com/jordisala1991))

## [3.1.1](https://github.com/sonata-project/exporter/compare/3.1.0...3.1.1) - 2023-01-02
### Fixed
- [[#621](https://github.com/sonata-project/exporter/pull/621)] Missing alias for ExporterInterface ([@pkameisha](https://github.com/pkameisha))

## [3.1.0](https://github.com/sonata-project/exporter/compare/3.0.0...3.1.0) - 2023-01-02
### Added
- [[#618](https://github.com/sonata-project/exporter/pull/618)] Added ExporterInterface; ([@pkameisha](https://github.com/pkameisha))
- [[#618](https://github.com/sonata-project/exporter/pull/618)] Added implementation ExporterInterface by Exporter. ([@pkameisha](https://github.com/pkameisha))

## [3.0.0](https://github.com/sonata-project/exporter/compare/3.0.0-alpha-1...3.0.0) - 2022-07-27
- No significant changes

## [3.0.0-alpha-1](https://github.com/sonata-project/exporter/compare/2.x...3.0.0-alpha-1) - 2022-06-14
### Removed
- [[#587](https://github.com/sonata-project/exporter/pull/587)] Support for PHP 7.4 ([@VincentLanglet](https://github.com/VincentLanglet))

See UPGRADE-3.0.md for all changes

## [2.13.0](https://github.com/sonata-project/exporter/compare/2.12.0...2.13.0) - 2022-06-10
### Deprecated
- [[#595](https://github.com/sonata-project/exporter/pull/595)] Passing  an instance of `Doctrine\DBAL\Driver\Connection` to `DoctrineDBALConnectionSourceIterator::__construct()` ([@VincentLanglet](https://github.com/VincentLanglet))

### Removed
- [[#596](https://github.com/sonata-project/exporter/pull/596)] Support of Symfony 5.3 ([@franmomu](https://github.com/franmomu))

## [2.12.0](https://github.com/sonata-project/exporter/compare/2.11.0...2.12.0) - 2022-05-24
### Deprecated
- [[#581](https://github.com/sonata-project/exporter/pull/581)] PropelCollectionSourceIterator ([@VincentLanglet](https://github.com/VincentLanglet))
- [[#580](https://github.com/sonata-project/exporter/pull/580)] Deprecated custom bundle file for flex recipe. ([@jordisala1991](https://github.com/jordisala1991))

### Removed
- [[#583](https://github.com/sonata-project/exporter/pull/583)] Support for PHP 7.3 ([@VincentLanglet](https://github.com/VincentLanglet))

## [2.11.0](https://github.com/sonata-project/exporter/compare/2.10.1...2.11.0) - 2022-02-13
### Changed
- [[#573](https://github.com/sonata-project/exporter/pull/573)] Some typehint from SourceIteratorInterface to \Iterator to allow using this library without deprecation. ([@VincentLanglet](https://github.com/VincentLanglet))

## [2.10.1](https://github.com/sonata-project/exporter/compare/2.10.0...2.10.1) - 2022-01-03
### Fixed
- [[#567](https://github.com/sonata-project/exporter/pull/567)] SourceIteratorInterface unresolvable generics ([@VincentLanglet](https://github.com/VincentLanglet))

## [2.10.0](https://github.com/sonata-project/exporter/compare/2.9.1...2.10.0) - 2022-01-02
### Added
- [[#560](https://github.com/sonata-project/exporter/pull/560)] Added class `XlsxWriter` that uses "phpoffice/phpspreadsheet" as suggested package. ([@willemverspyck](https://github.com/willemverspyck))
- [[#560](https://github.com/sonata-project/exporter/pull/560)] Default the XLSX export is not enabled. You can enable it by adding "xlsx" to "default_writers" in configuration. ([@willemverspyck](https://github.com/willemverspyck))
- [[#560](https://github.com/sonata-project/exporter/pull/560)] Added tests for the `XlsxWriter` class. ([@willemverspyck](https://github.com/willemverspyck))

### Deprecated
- [[#565](https://github.com/sonata-project/exporter/pull/565)] AbstractTypedWriterTestCase ([@VincentLanglet](https://github.com/VincentLanglet))

### Fixed
- [[#562](https://github.com/sonata-project/exporter/pull/562)] DBAL v3 compatibility ([@VincentLanglet](https://github.com/VincentLanglet))

## [2.9.1](https://github.com/sonata-project/exporter/compare/2.9.0...2.9.1) - 2021-11-12
### Fixed
- [[#550](https://github.com/sonata-project/exporter/pull/550)] Fixed compatibility with doctrine/dbal ^2.13 ([@jordisala1991](https://github.com/jordisala1991))

## [2.9.0](https://github.com/sonata-project/exporter/compare/2.8.0...2.9.0) - 2021-11-06
### Added
- [[#540](https://github.com/sonata-project/exporter/pull/540)] Added compatibility with Doctrine DBAL 3. ([@jordisala1991](https://github.com/jordisala1991))
- [[#517](https://github.com/sonata-project/exporter/pull/517)] Added support for Symfony 6. ([@jordisala1991](https://github.com/jordisala1991))

### Changed
- [[#535](https://github.com/sonata-project/exporter/pull/535)] Clear document manager every 100 results to improve ODM iterator performances ([@nicolas-joubert](https://github.com/nicolas-joubert))

### Deprecated
- [[#532](https://github.com/sonata-project/exporter/pull/532)] `SourceIteratorInterface` ([@VincentLanglet](https://github.com/VincentLanglet))

### Fixed
- [[#533](https://github.com/sonata-project/exporter/pull/533)] Added missing conflict with doctrine/orm < 2.8 ([@VincentLanglet](https://github.com/VincentLanglet))

## [2.8.0](https://github.com/sonata-project/exporter/compare/2.7.0...2.8.0) - 2021-09-21
### Changed
- [[#519](https://github.com/sonata-project/exporter/pull/519)] `SourceIteratorInterface` is not generic anymore ([@VincentLanglet](https://github.com/VincentLanglet))

### Fixed
- [[#519](https://github.com/sonata-project/exporter/pull/519)] Doctrine/orm deprecation ([@VincentLanglet](https://github.com/VincentLanglet))
- [[#518](https://github.com/sonata-project/exporter/pull/518)] Fixed deprecations with Symfony 5.4 ([@jordisala1991](https://github.com/jordisala1991))
- [[#514](https://github.com/sonata-project/exporter/pull/514)] `AbstractPropertySourceIterator::$iterator` phpdoc ([@VincentLanglet](https://github.com/VincentLanglet))

### Removed
- [[#518](https://github.com/sonata-project/exporter/pull/518)] Removed support for Symfony 5.2 ([@jordisala1991](https://github.com/jordisala1991))

## [2.7.0](https://github.com/sonata-project/exporter/compare/2.6.2...2.7.0) - 2021-06-27
### Changed
- [[#485](https://github.com/sonata-project/exporter/pull/485)] Clear entity manager every 100 results to improve ORM iterator performance ([@EmmanuelVella](https://github.com/EmmanuelVella))

## [2.6.2](https://github.com/sonata-project/exporter/compare/2.6.1...2.6.2) - 2021-04-09
### Fixed
- [[#475](https://github.com/sonata-project/exporter/pull/475)] Allow `AbstractPropertySourceIterator::getValue()` to return `bool|int|float` value ([@VincentLanglet](https://github.com/VincentLanglet))

## [2.6.1](https://github.com/sonata-project/exporter/compare/2.6.0...2.6.1) - 2021-03-26
### Fixed
- [[#465](https://github.com/sonata-project/exporter/pull/465)] Restrict `SourceIteratorInterface` template ([@VincentLanglet](https://github.com/VincentLanglet))

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
