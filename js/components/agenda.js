jQuery(document).ready(function($) {
	var van = $( '#van' ).datetimepicker( {
		useCurrent: 'hour',
	} );

	var tot = $( '#tot' ).datetimepicker( {
		useCurrent: false,
	} );

	var inschrijf = $( '#inschrijf' ).datetimepicker( {
		useCurrent: false,
	} );
	var afmeld = $( '#afmeld' ).datetimepicker( {
		useCurrent: false,
	} );

	var slag = $( '#slag');

	van.on( "dp.change", function( e ) {
		tot.data( "DateTimePicker" ).minDate( e.date );
		inschrijf.data( "DateTimePicker" ).maxDate( e.date );
		afmeld.data( "DateTimePicker" ).maxDate( e.date );
	} );

	$( '#soort' ).change( function() {
		// show current
		if ( $( this ).val() === 'nszk' ) {
			$( '#nszk' ).toggleClass( 'hidden', false );
		} else {
			$( '#nszk' ).toggleClass( 'hidden', true );
		}

	} );

	$( '#add_button' ).click( function( e ) { //on add input button click
		e.preventDefault();
		slag.append( '<div class="input-group date"><input type="text" class="form-control" name="slagen[]"><span class="input-group-addon"><i class="glyphicon glyphicon-trash"></i></span></div>' ); //add input box
	} );
});