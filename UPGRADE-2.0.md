UPGRADE FROM 1.x to 2.0
=======================

## PHP

PHP 7.2 is required.
Added types and return types.

## Symfony

Symfony support is dropped from 2.3 to 3.3 included.

## Namespace

The namespace was changed from `Exporter` to `Sonata\Exporter`.

## Closed API

Many classes have been made final, meaning you can no longer extend them.
Consider using decoration instead.
