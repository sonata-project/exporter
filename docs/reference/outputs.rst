=======
Outputs
=======

Several output formatters are supported:

* CSV
* GSA Feed (Google Search Appliance)
* In Memory (for test purposes mostly)
* JSON
* Sitemap
* XML
* Excel XML
* XLS (MS Excel)
* PDF

You may also create your own. To do so, simply create a class that implements the ``Exporter\Writer\WriterInterface``.

The knplabs/knp-snappy writer
=============================

If you want to generate a pdf output, you can use the ``knplabs/knp-snappy`` writer.
You will have to `install the library and its dependencies <https://github.com/KnpLabs/snappy>`_
(including ``wkhtmltopdf``).

The instanciation of the writer goes like this:

.. code-block:: php

    <?php

    use Exporter\Writer\KnpSnappyWriter;
    use Knp\Snappy\Pdf;

    $writer = new KnpSnappyWriter(
        new Pdf(),
        'output.pdf',
        '/absolute/path/to/the/wkhtmltopdf/binary',
        true, // include a header
        true, // add borders
        array(
            'any_supported_snappy_option' => 'and its value',
        )
    );
