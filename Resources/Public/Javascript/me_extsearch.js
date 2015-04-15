$( document ).ready( function() {
	var searchbox_autocomplete_submit = function() {
		$( "#tx-indexedsearch-searchbox" ).submit();
	};

	$( "#tx-indexedsearch-searchbox-sword" ).autocomplete( {
		source: "index.php?eID=me_extsearch_autocomplete&language=" + language + "&limit=" + limit,
		minLength: 3,
		select: function( event, ui ) {
			$( "#tx-indexedsearch-searchbox-sword" ).value = ui.item.value;
			window.setTimeout( function() {
				searchbox_autocomplete_submit()
			}, 10 );
		}
	} );
} );
