UPGRADE 3.x
===========

UPGRADE FROM 3.x to 3.x
=======================

## Formatters for writers

- Added `Sonata\Exporter\Formatter\BoolFormatter`, `Sonata\Exporter\Formatter\DateIntervalFormatter`, `Sonata\Exporter\Formatter\DateTimeFormatter`,
  `Sonata\Exporter\Formatter\EnumFormatter`, `Sonata\Exporter\Formatter\IterableFormatter`, `Sonata\Exporter\Formatter\StringableFormatter` and
  `Sonata\Exporter\Formatter\SymfonyTranslationFormatter`
  classes to be used within implementations of `Sonata\Exporter\Formatter\Writer\FormatAwareInterface`.
- Deprecated `Sonata\Exporter\Writer\FormattedBoolWriter`, use `Sonata\Exporter\Formatter\BoolFormatter` instead.
- Deprecated arguments `dateTimeFormat` and `useBackedEnumValue` in `Sonata\Exporter\Source\AbstractPropertySourceIterator::__construct()` and
  their children classes. To disable the source formatting you MUST pass `true` in argument `disableSourceFormatters` and use
  `Sonata\Exporter\Formatter\Writer\FormatAwareInterface::addFormatter()` in your writers instead.

## Symfony Bridge

- Added `sonata_exporter.writers.{writer}.formatters` configuration in order to determine which formatters will be used by each writer.

  ```yaml
  sonata_exporter:
      writers:
          csv:
              formatters:
                  - datetime
                  - enum
                  # - ...
  ```

  By default, "bool", "dateinterval", "datetime", "enum", "iterable" and "stringable" formatters are configured.
  If "symfony/translations-contracts" is installed, "symfony_translator" formatter is also enabled.
