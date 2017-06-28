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

### Google Groups

For questions and proposals you can post on this google groups

* [Sonata Users](https://groups.google.com/group/sonata-users): Only for user questions
* [Sonata Devs](https://groups.google.com/group/sonata-devs): Only for devs


### Note for Symfony2 users

* For Symfony >=2.3, use tag `^1.4`
* For Symfony 2.2, use tag 1.3.1
* For Symfony 2.1, use tag 1.2.3
* For Ssymfony 2.0, use tag 1.1.0
