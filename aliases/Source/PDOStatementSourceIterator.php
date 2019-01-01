<?php

namespace Exporter\Source;

class_alias(
    '\Sonata\\'.__NAMESPACE__.'\PDOStatementSourceIterator',
    __NAMESPACE__.'\PDOStatementSourceIterator'
);

if (false) {
    class PDOStatementSourceIterator extends \Sonata\Exporter\Source\PDOStatementSourceIterator
    {
    }
}
