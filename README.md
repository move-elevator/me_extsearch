# TYPO3 Extension "me_extsearch"

Extends indexed_search to clear search index records older than 3 and more days. Add autocomplete for search form and prepare pagebrowser to used twitter bootstrap.

## Installation

1. just upload and activate the extension 

## Configuration

### Used clear search index service

1. Set count of days as minimum age of search cache records to be deleted
2. Add scheduler task "Extbase CommandController Task" -> "MeExtsearch Index: clear"

### Add autocomplete in search word field

1. Include "m:e ExtSearch Autocomplete example (me_extsearch)" as static file in page template.

### Overwrite pagebrowser

1. Activate "overwritePagebrowser" in after activate extension in extension manager.
2. Set count of list records with constants "plugin.tx_meextsearch.search_autocomplet.maxResults". Default number is 3.
3. Activate 

## Contact

* typo3@move-elevator.de
* Company: http://www.move-elevator.de
* Issue-Tracker - https://github.com/move-elevator/me_placeholder

## Change Log

2015-04-17 Jan Maennig <jma@move-elevator.de>

	* Initialize Release to TER.

2012-09-11 Jan Maennig <jma@move-elevator.de>

	* Add service to clear serach index 