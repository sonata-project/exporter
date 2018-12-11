# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

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
