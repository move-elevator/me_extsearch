# TYPO3 Extension "me_extsearch"

Extends indexed_search to clear search index records older than 3 and more days. Add autocomplete for search form and prepare pagebrowser to used twitter bootstrap.

## Installation

1. just upload and activate the extension

## Configuration

### Used clear search index service

1. Set count of days as minimum age of search cache records to be deleted
2. Add scheduler task "Extbase CommandController Task" -> "MeExtsearch Index: clear"

### Add autocomplete in search word field

1. Include "m:e ExtSearch Autocomplete jQuery (me_extsearch)" as static file in page template if needed.
2. Include "m:e ExtSearch Autocomplete jQuery UI (me_extsearch)" as static file in page template if needed.
3. Include "m:e ExtSearch Autocomplete example (me_extsearch)" as static file in page template.
4. The autocomplete example was written for template "sysext/indexed_search/pi/template_css.tmpl". It triggers by changing a field with id "#tx-indexedsearch-searchbox-sword".

### Overwrite pagebrowser

1. Activate "overwritePagebrowser" in after activate extension in extension manager.
2. Set count of list records with constants "plugin.tx_meextsearch.search_autocomplet.maxResults". Default number is 3.
3. Activate exactCount flag with "plugin.tx_indexedsearch.search.exactCount = 1"

## Contact

* typo3@move-elevator.de
* Company: http://www.move-elevator.de
* Issue-Tracker - https://github.com/move-elevator/me_extsearch

## Change Log

2015-05-22  Steve Schütze  <sts@move-elevator.de>

	* add level3 language label overwrite for drop down

2015-05-08  Jan Maennig  <jma@move-elevator.de>

	* Update composer.json to fixed problems at extension activation

2015-04-17 Jan Maennig <jma@move-elevator.de>

	* Initialize Release to TER.

2013-10-25 Thomas Scholze <ts@move-elevator.de>

	* Add service to clear search index

2013-10-11 Thomas Scholze <ts@move-elevator.de>

	* Overwrite pagination to used bootstrap

2012-09-11 Jan Maennig <jma@move-elevator.de>

	* Add service to clear search index
