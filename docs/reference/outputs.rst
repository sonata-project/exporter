=======
Outputs
=======

Several output writers are supported:

* `CSV`_
* `GSA Feed`_ (Google Search Appliance)
* In Memory (for test purposes mostly)
* `JSON`_
* `Sitemap`_ (Sitemaps XML)
* `XLS XML`_ (Microsoft Excel 5.0/95 Workbook)
* `XLSX`_ (Excel Workbook)
* `XML`_

You may also create your own. To do so, simply create a class that implements the ``Exporter\Writer\WriterInterface``,
or better, if you know what ``Content-Type`` header should be used along with your output and what format it produces, ``TypedWriterInterface``.

You can transform the output through the following formatters:

* ``BoolFormatter``: Transforms boolean values to the configured strings (defaults to ``true`` => "yes", ``false`` => "no")
* ``DateIntervalFormatter``: Transforms ``\DateInterval`` objects to their ISO-8601 duration representation
* ``DateTimeFormatter``: Transforms ``\DateTimeInterface`` objects to the configured date representation (defaults to ``\DateTimeInterface::RFC2822``)
* ``EnumFormatter``: Transforms enumeration cases to a string representation (from the enum cases or values) depending on the enumeration type
  (``\UnitEnum`` or ``\BackedEnum``) and the ``$useBackedEnumValue`` parameter (defaults to ``true``)
* ``IterableFormatter``: Transforms an iterable value to their string representation
* ``StringableFormatter``: Transforms stringable objects to their string representation (the one configured in the ``__toString()`` method)
* ``SymfonyTranslationFormatter``: Transforms messages (strings or objects implementing ``TranslatableInterface``) into their translation based
  on the given configuration (parameters, domain, locale). It requires the "symfony/translation-contracts" package.

.. _`CSV`: https://datatracker.ietf.org/doc/html/rfc4180
.. _`GSA Feed`: https://developers.google.com/search-appliance
.. _`JSON`: https://www.json.org/json-en.html
.. _`Sitemap`: https://www.sitemaps.org/protocol.html
.. _`XLS XML`: https://support.microsoft.com/en-us/office/file-formats-that-are-supported-in-excel-0943ff2c-6014-4e8d-aaea-b83d51d46247#ID0EDT
.. _`XLSX`: https://support.microsoft.com/en-us/office/file-formats-that-are-supported-in-excel-0943ff2c-6014-4e8d-aaea-b83d51d46247#ID0EDT
.. _`XML`: https://www.w3.org/TR/xml/
