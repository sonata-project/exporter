Data Exporter
=============

[![Build Status](https://secure.travis-ci.org/sonata-project/exporter.png)](https://secure.travis-ci.org/#!/sonata-project/exporter)

Data Exporter is a lightweight library to export data into different formats.

### Installation using Composer

```bash
composer require sonata-project/exporter
```

### Usage

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

### Note for Symfony2 users

* For Symfony >=2.3, use tag `^1.4`
* For Symfony 2.2, use tag 1.3.1
* For Symfony 2.1, use tag 1.2.3
* For Symfony 2.0, use tag 1.1.0
