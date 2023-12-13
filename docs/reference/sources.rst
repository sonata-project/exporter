=======
Sources
=======

You may export data from various sources:

* Chain (can aggregate data from several different iterators)
* CSV
* Doctrine Query (ORM & ODM supported)
* PDO Statement
* PHP Array
* PHP Iterator instance
* PHP Iterator with a callback on current
* Sitemap (Takes another iterator)
* XML
* XLS XML
* XLSX (SpreadsheetML format for Microsoft Excel)

You may also create your own. To do so, create a class that implements ``\Iterator``.
