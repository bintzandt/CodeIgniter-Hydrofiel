jQuery(document).ready(function($) {
	$( "#van" ).flatpickr({
		enableTime: true,
		time_24hr: true,
		dateFormat: "d-m-Y H:i",
	});

	$( "#tot" ).flatpickr({
		enableTime: true,
		time_24hr: true,
		dateFormat: "d-m-Y H:i",
	});

	$( "#inschrijf" ).flatpickr({
		enableTime: true,
		time_24hr: true,
		dateFormat: "d-m-Y H:i",
	});
	$( "#afmeld" ).flatpickr({
		enableTime: true,
		time_24hr: true,
		dateFormat: "d-m-Y H:i",
	});

	var slag = $( "#slag");

	$( "#soort" ).change( function() {
		// show current
		if ( $( this ).val() === "nszk" ) {
			$( "#nszk" ).toggleClass( "hidden", false );
		} else {
			$( "#nszk" ).toggleClass( "hidden", true );
		}

	} );

	$( "#add_button" ).click( function( e ) { //on add input button click
		e.preventDefault();
		slag.append( '<div class="input-group date"><input type="text" class="form-control" name="slagen[]"><span class="input-group-addon"><i class="glyphicon glyphicon-trash"></i></span></div>' ); //add input box
	} );
});