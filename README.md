Data Exporter
=============

[![Build Status](https://secure.travis-ci.org/sonata-project/exporter.png)](https://secure.travis-ci.org/#!/sonata-project/exporter)


Data Exporter is a lightweight library to export data into different formats.


```php

<?php
// Prepare the data source
$dbh = new \PDO('sqlite:foo.db');
$stm = $dbh->prepare('SELECT id, username, email FROM user');
$stm->execute();

$source = new PDOStatementSource($stm);

// Prepare the writer
$writer = new CsvWriter('data.csv');

// Export the data
Handler::create($source, $writer)->export();

```


**Google Groups**: For questions and proposals you can post on this google groups

* [Sonata Users](https://groups.google.com/group/sonata-users): Only for user questions
* [Sonata Devs](https://groups.google.com/group/sonata-devs): Only for devs