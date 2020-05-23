jQuery(document).ready( $ => {
	document.querySelectorAll( ".flatpickr" ).forEach( element => {
		const baseConfig = { 
			minDate: "today",
			enableTime: true,
			time_24hr: true,
			dateFormat: "d-m-Y H:i",
		};

		/**
		 * If the start of the event changes, make sure to update the min and maxDates of all other pickers accordingly.
		 */
		if ( element.id === "van" ) {
			baseConfig.onChange = ( _, dateString ) => {
				document.querySelector( "#tot" )._flatpickr.config.minDate = dateString;
				[ "#afmeld", "#inschrijf" ].map( id => document.querySelector( id )._flatpickr.config.maxDate = dateString );
			}
		}

		flatpickr( element, baseConfig );
	} );
	
	var slag = $( "#slag");

	$( "#soort" ).change( function() {
		// show current
		if ( $( this ).val() === "nszk" ) {
			$( "#nszk" ).removeClass( "d-none" );
		} else {
			$( "#nszk" ).addClass( "d-none" );
		}

	} );

	// Define the HTML for the additional input field.
	const additionalInput = '<div class="input-group date"><input type="text" class="form-control" name="slagen[]"><span class="input-group-addon"><i class="glyphicon glyphicon-trash"></i></span></div>';

	$( "#add_button" ).click( function( e ) { //on add input button click
		e.preventDefault();
		slag.append( additionalInput ); //add input box
	} );
} );