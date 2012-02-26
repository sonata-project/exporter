Data Exporter
=============

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