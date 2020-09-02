# Data Exporter

Data Exporter is a lightweight library to export data into different formats.

[![Latest Stable Version](https://poser.pugx.org/sonata-project/exporter/v/stable)](https://packagist.org/packages/sonata-project/exporter)
[![Latest Unstable Version](https://poser.pugx.org/sonata-project/exporter/v/unstable)](https://packagist.org/packages/sonata-project/exporter)
[![License](https://poser.pugx.org/sonata-project/exporter/license)](https://packagist.org/packages/sonata-project/exporter)

[![Total Downloads](https://poser.pugx.org/sonata-project/exporter/downloads)](https://packagist.org/packages/sonata-project/exporter)
[![Monthly Downloads](https://poser.pugx.org/sonata-project/exporter/d/monthly)](https://packagist.org/packages/sonata-project/exporter)
[![Daily Downloads](https://poser.pugx.org/sonata-project/exporter/d/daily)](https://packagist.org/packages/sonata-project/exporter)

Branch | Github Actions | Coverage |
------ | -------------- | -------- |
2.x    | [![Test][test_stable_badge]][test_stable_link]     | [![Coverage Status][coverage_stable_badge]][coverage_stable_link]     |
master | [![Test][test_unstable_badge]][test_unstable_link] | [![Coverage Status][coverage_unstable_badge]][coverage_unstable_link] |

## Installation using Composer

```bash
composer require sonata-project/exporter
```

## Usage

```php
<?php

use Exporter\Handler;
use Exporter\Source\PDOStatementSourceIterator;
use Exporter\Writer\CsvWriter;

// Prepare the data source
$dbh = new \PDO('sqlite:foo.db');
$stm = $dbh->prepare('SELECT id, username, email FROM user');
$stm->execute();

$source = new PDOStatementSourceIterator($stm);

// Prepare the writer
$writer = new CsvWriter('data.csv');

// Export the data
Handler::create($source, $writer)->export();
```

## Documentation

* [Introduction](docs/reference/introduction.rst)
* [Installation](docs/reference/installation.rst)
* [Sources](docs/reference/sources.rst)
* [Outputs](docs/reference/outputs.rst)
* [Symfony integration](docs/reference/symfony.rst)

## Support

For general support and questions, please use [StackOverflow](http://stackoverflow.com/questions/tagged/sonata).

If you think you found a bug or you have a feature idea to propose, feel free to open an issue
**after looking** at the [contributing guide](CONTRIBUTING.md).

## Note for Symfony2 users

* For Symfony >=2.3, use tag >= [1.4.0](https://github.com/sonata-project/exporter/releases/tag/1.4.0)
* For Symfony 2.2, use tag [1.3.1](https://github.com/sonata-project/exporter/releases/tag/1.3.1)
* For Symfony 2.1, use tag [1.2.3](https://github.com/sonata-project/exporter/releases/tag/1.2.3)
* For Symfony 2.0, use tag [1.1.0](https://github.com/sonata-project/exporter/releases/tag/1.1.0)

## License

This package is available under the [MIT license](LICENSE).

[test_stable_badge]: https://github.com/sonata-project/exporter/workflows/Test/badge.svg?branch=2.x
[test_stable_link]: https://github.com/sonata-project/exporter/actions?query=workflow:test+branch:2.x
[test_unstable_badge]: https://github.com/sonata-project/exporter/workflows/Test/badge.svg?branch=master
[test_unstable_link]: https://github.com/sonata-project/exporter/actions?query=workflow:test+branch:master

[coverage_stable_badge]: https://codecov.io/gh/sonata-project/exporter/branch/2.x/graph/badge.svg
[coverage_stable_link]: https://codecov.io/gh/sonata-project/exporter/branch/2.x
[coverage_unstable_badge]: https://codecov.io/gh/sonata-project/exporter/branch/master/graph/badge.svg
[coverage_unstable_link]: https://codecov.io/gh/sonata-project/exporter/branch/master
