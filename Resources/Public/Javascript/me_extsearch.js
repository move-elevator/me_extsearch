$( document ).ready( function() {
	var searchbox_autocomplete_submit = function() {
		console.log('drin');
		$( "form#tx_indexedsearch" ).submit();
	};
	if ( $( "#tx-indexedsearch-searchbox-sword" ) !== undefined ) {
		$( "#tx-indexedsearch-searchbox-sword" ).autocomplete( {
		   source: "index.php?eID=me_extsearch_autocomplete&language=" + meExtSearchLanguage + "&limit=" + meExtSearchLimit,
		   minLength: 3,
		   select: function( event, ui ) {
			   $( "#tx-indexedsearch-searchbox-sword" ).value = ui.item.value;
			   window.setTimeout( function() {
				   searchbox_autocomplete_submit()
			   }, 10 );
		   }
	   } );
	}
} );
